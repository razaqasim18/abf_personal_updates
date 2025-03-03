<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Jobs\assignPointsToUserAndParentsJob;
use App\Models\Commission;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Product;
use App\Models\PSPReward;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::select("*", "orders.id AS id", "orders.created_at AS created_at")->join('users', 'users.id', '=', 'orders.user_id')->orderBy('orders.id', 'DESC')->get();
        return view('admin.order.list', ['order' => $order]);
    }

    public function detail($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.detail', compact('order'));
    }

    public function printPDF($id)
    {
        $order = Order::findOrFail($id);
        $orderDetail = Order::findOrFail($id)->orderDetail;
        $orderShippingDetail = Order::findOrFail($id)->orderShippingDetail;

        $data = [
            'order' => $order->toArray(),
            'orderDetail' => $orderDetail->toArray(),
            'orderShippingDetail' => $orderShippingDetail->toArray(),
            'orderVendorDetail' => [],
            'ordertype' => 'normal'
        ];
        // return view('vendor.print.pdf', compact('data'));
        $pdf = Pdf::loadView('vendor.print.pdf', $data);
        return $pdf->download($order->order_no . '_print.pdf');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if (Order::destroy($id)) {
            $json = ['type' => 1, 'msg' => 'Order is deleted'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function changeStatus($status, $id)
    {
        $order = Order::findOrFail($id);
        $orderDetail = OrderDetail::where('order_id', $id)->get();
        $user = User::findOrFail($order->user_id);
        $ordershipping = $order->orderShippingDetail;
        $wallet =  Wallet::where('user_id', $order->user_id)->first();
        $msg = '';

        if ($status == "1") {  //order proccessing
            $msg = 'Your order no ' . $order->order_no . " is in Process";
        } elseif ($status == "3" || $status == "6") { // order delivered
            if ($order->status == '2' || $order->status == '5') { // if order is approved
                $msg = ($status == "3") ? 'Your order no ' . $order->order_no . " is Delivered" : 'Your order no ' . $order->order_no . " is Re-Delivered";

                // ASSIGNING THE POINTS TO ORDER USER
                $point = Point::updateOrCreate(
                    ['user_id' => $user->id],
                    ['point' => DB::raw('point + ' . $order->points)],
                );
                PointTransaction::insert([
                    'user_id' => $user->id,
                    'point_id' => $point->id,
                    'point' => $order->points,
                    'status' => 1,
                    'is_child' =>  0,
                ]);
                // ASSIGNING THE POINTS TO ORDER USER

                $assignPointsToUserAndParentsJob = new assignPointsToUserAndParentsJob($user->id, $user->id, $order->points);
                Queue::push($assignPointsToUserAndParentsJob);

                // its not working for two tables
                // $singleUserRankJob = new singleUserRankJob($user->id);
                // Queue::push($singleUserRankJob);

                $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $user->id)->first();
                if ($user) {

                    // discount reward
                    $countDiscount = 0;
                    $eligibleForDiscount = false;
                    $orderDetaildiscount = OrderDetail::where('order_id', $id)->where('product_is_coupon', '1')->get();
                    foreach ($orderDetaildiscount as $row) {
                        if ($row->product_is_coupon == '1' && $row->product_is_coupon_used == "0") {
                            $product = Product::findorFail($row->product_id);
                            $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ?
                                ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;
                            $discount = $price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100);
                            $countDiscount =  $countDiscount + ($discount * $row->quantity);
                            $eligibleForDiscount = true;
                            // $price = $price - ($price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100));
                        } else {
                            $countDiscount =  0;
                            $eligibleForDiscount = false;
                            break;
                        }
                    }
                    // dd($row->product_is_coupon == '1' && $row->product_is_coupon_used == "0");

                    if ($eligibleForDiscount && $countDiscount) {
                        DB::transaction(function () use ($user, $countDiscount) {
                            $wallet = Wallet::updateOrCreate(
                                ['user_id' => $user->userid],
                                ['amount' => DB::raw('amount + ' . $countDiscount)]
                            );
                            WalletTransaction::insert([
                                'wallet_id' => $wallet->id,
                                'amount' => $countDiscount,
                                'is_gift' => 0,
                                'status' => 1,
                                'detail' => 'got product discount',
                                'reward_type' => '0'
                            ]);
                        });
                    }
                    // discount reward

                    $pointtransactions = DB::table('point_transactions')
                        ->select(DB::raw("SUM(point) as point"))
                        ->where('status', '1')
                        ->where('user_id', $user->userid)
                        ->where('is_child', 0)
                        // ->whereMonth('created_at', '=', $currentMonth)
                        // ->whereYear('created_at', '=', $currentYear)
                        ->first();


                    $commission = Commission::all();
                    //giving commission reward if rank is reeached
                    foreach ($commission as $commissionrow) {
                        if ($user->point >= $commissionrow->points) {
                            if ($user->commission_id == null || $user->commission_id < $commissionrow->id) {
                                if ($pointtransactions != null && $pointtransactions->point >= 100) {
                                    Point::updateOrCreate(
                                        ['user_id' => $user->userid],
                                        ['commission_id' => $commissionrow->id]
                                    );
                                    DB::transaction(function () use ($user, $commissionrow) {
                                        $wallet = Wallet::updateOrCreate(
                                            ['user_id' => $user->userid],
                                            ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                                        );
                                        WalletTransaction::insert([
                                            'wallet_id' => $wallet->id,
                                            'amount' => $commissionrow->gift,
                                            'is_gift' => 1,
                                            'status' => 1,
                                            'detail' => 'user rank reward when order is completed by admin',
                                            'reward_type' => '6'
                                        ]);
                                    });
                                }
                            }
                        }
                    }
                    //giving commission reward if rank is reeached --end

                    // psp reward
                    // $currentDate = Carbon::now();
                    // $currentYear = $currentDate->year;
                    // $currentMonth = $currentDate->month;

                    $personalpoints = DB::table('point_transactions')
                        ->select(DB::raw("SUM(point) as point"))
                        ->where('status', '1')
                        ->where('user_id', $user->userid)
                        ->where('is_child', 0)
                        ->whereMonth('created_at', '>=', '11')
                        ->whereYear('created_at', '>=', '2023')
                        ->first();

                    $userpersonalpoints = ($personalpoints) ? $personalpoints->point : 0;
                    $pspreward  =  PSPReward::all();
                    foreach ($pspreward as $psprewardrow) {
                        if ($userpersonalpoints >= $psprewardrow->points) {
                            if ($user->psp_id == null || $user->psp_id < $psprewardrow->id) {
                                Point::updateOrCreate(
                                    ['user_id' => $user->userid],
                                    ['psp_id' => $psprewardrow->id]
                                );
                                DB::transaction(function () use ($user, $psprewardrow) {
                                    $wallet = Wallet::updateOrCreate(
                                        ['user_id' => $user->userid],
                                        ['gift' => DB::raw('gift + ' . $psprewardrow->reward)]
                                    );
                                    WalletTransaction::insert([
                                        'wallet_id' => $wallet->id,
                                        'amount' => $psprewardrow->reward,
                                        'status' => 1,
                                        'is_gift' => 1,
                                        'detail' => 'PSP reward',
                                        'reward_type' => '4',
                                    ]);
                                });
                            }
                        }
                    }
                    // psp reward end
                }
            }
            // }
            // CustomHelper::calculateUserRank($user->id);
            if ($status == "6") { // ordered redelivered
                User::where('id', $order->user_id)->update(['order_return' =>  DB::raw('order_return -' . $order->order_return)]);
            }
        } elseif ($status == "-1") { //order cancelled
            $msg = 'Your order no ' . $order->order_no . " is Cancelled";
            // getting back the item from order detail
            $OrderDetail = OrderDetail::where('order_id', $id)->get();
            foreach ($OrderDetail as $item) {
                $product = Product::find($item->product_id);
                $product->stock = $product->stock + $item->quantity;
                $product->in_stock = 1;
                $product->save();
            }

            if ($order->payment_by == '1') {
                DB::transaction(function () use ($user, $order) {
                    $wallet = Wallet::updateOrCreate(
                        ['user_id' => $user->id],
                        ['amount' => DB::raw('amount + ' . $order->total_bill)]
                    );
                    WalletTransaction::insert([
                        'wallet_id' => $wallet->id,
                        'amount' => $order->total_bill,
                        'is_gift' => 0,
                        'status' => 1,
                        'detail' => 'order cancel by admin payment was by wallet',
                    ]);
                });
            } else if ($order->payment_by == '2') {
                DB::transaction(function () use ($user, $order) {
                    $wallet = Wallet::updateOrCreate(
                        ['user_id' => $user->id],
                        ['gift' => DB::raw('gift + ' . $order->total_bill)]
                    );
                    WalletTransaction::insert([
                        'wallet_id' => $wallet->id,
                        'amount' => $order->total_bill,
                        'is_gift' => 1,
                        'status' => 1,
                        'detail' => 'order cancel by admin payment was by reward',
                    ]);
                });
            }
        } elseif ($status == "-2") { //  order returned
            $msg = "Your order has been Returned";
            $amount = (SettingHelper::getSettingValueBySLug('return_charges')) ? SettingHelper::getSettingValueBySLug('return_charges') : 100;
            if ($amount) {
                if ($wallet && $wallet->amount >= $amount) {
                    $columnname = "wallet";
                    $column = ['amount' => DB::raw('amount - ' . $amount)];
                    $is_gift = 0;
                } elseif ($wallet && $wallet->gift >= $amount) {
                    $columnname = "gift";
                    $column = ['gift' => DB::raw('gift - ' . $amount)];
                    $is_gift = 1;
                } else {
                    $columnname = "wallet";
                    $column = ['amount' => DB::raw('amount - ' . $amount)];
                    $is_gift = 0;
                }
                DB::transaction(function () use ($user, $order, $amount, $column, $is_gift, $columnname) {
                    $wallet = Wallet::updateOrCreate(
                        ['user_id' => $user->id],
                        $column
                    );
                    WalletTransaction::insert([
                        'wallet_id' => $wallet->id,
                        'amount' => $amount,
                        'is_gift' =>   $is_gift,
                        'status' => 0,
                        'detail' => "order was returned by admin," . $amount . " amount is deducted from your " . $columnname,
                    ]);
                    User::where('id', $user->id)->update(
                        ['order_return' => DB::raw('order_return + 1')]
                    );
                    Order::where('id', $order->id)->update(
                        ['order_return' => DB::raw('order_return + 1')]
                    );
                });
            }
            $user->notify(new OrderStatus($order, $msg, $ordershipping));
        }
        $order->status = $status;
        $response = $order->save();
        if ($response) {
            // $user->notify(new OrderStatus($order, $msg, $ordershipping));
            $json = ['type' => 1, 'msg' => $msg];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function orderApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_trackingid' => 'required|unique:orders',
            'delivery_by' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'type' => 0,
                'validator_error' => 1,
                'errors' => $validator->errors(),
            ]);
        }
        $order = Order::findOrFail($request->orderid);
        $user = User::findOrFail($order->user_id);
        $order->delivery_by = $request->delivery_by;
        $order->delivery_trackingid = $request->delivery_trackingid;
        $order->status = $request->status;
        $response = $order->save();
        $ordershipping = $user->orderShippingDetail;
        $msg = ($request->status == "2") ? 'Your order no ' . $order->order_no . " is Approved" : 'Your order no ' . $order->order_no . " is Re-Approved";
        $user->notify(new OrderStatus($order, $msg, $ordershipping));

        if ($response) {
            $json = ['type' => 1, 'msg' => $msg];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function changeDelivery(Request $request)
    {
        $id = $request->orderid;
        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->user_id);
        $order->delivery_by = $request->delivery_by;
        $order->delivery_trackingid = $request->delivery_trackingid;
        $response = $order->save();
        if ($response) {
            $json = ['type' => 1, 'msg' => "Delivery tracking id is changed"];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }
}
