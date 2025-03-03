<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\VendorOrder as ModelsVendorOrder;
use App\Models\VendorOrderDetail;
use App\Models\VendorRequest;
use App\Models\Wallet;
use App\Notifications\VendorRequestApprovedNotification;
use App\Notifications\VendorRequestFailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\OrderStatus;
use App\Models\WalletTransaction;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Jobs\assignPointsToUserAndParentsJob;
use App\Models\AdminAccountTransection;
use App\Models\Commission;
use App\Models\PSPReward;
use App\Models\VendorAccountTransection;
use App\Models\VendorProduct;
use App\Models\VendorWallet;
use App\Models\VendorWalletTransaction;
use App\Notifications\VendorOrderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Auth;

class VendorOrder extends Controller
{
    public function index()
    {
        $order = ModelsVendorOrder::select("*", "vendor_orders.id AS id", "vendor_orders.created_at AS created_at")
            ->join('users', 'users.id', '=', 'vendor_orders.user_id')
            ->where("vendor_orders.seller_id", Auth::guard("web")->user()->id)
            ->where("vendor_orders.vendor_id", Auth::guard("web")->user()->vendor->id)
            ->orderBy('vendor_orders.id', 'DESC')->get();
        return view('user.vendor.order.list', ['order' => $order]);
    }

    public function detail($id)
    {
        $order = ModelsVendorOrder::findOrFail($id);
        return view('user.vendor.order.detail', compact('order'));
    }

    public function printPDF($id)
    {
        $order = ModelsVendorOrder::findOrFail($id);
        $orderDetail = ModelsVendorOrder::findOrFail($id)->orderDetail;
        $orderShippingDetail = ModelsVendorOrder::findOrFail($id)->orderShippingDetail;
        $orderVendorDetail = ModelsVendorOrder::with('vendorDetail.user')->findOrFail($id);

        $data = [
            'order' => $order->toArray(),
            'orderDetail' => $orderDetail->toArray(),
            'orderShippingDetail' => $orderShippingDetail->toArray(),
            'orderVendorDetail' => $orderVendorDetail->toArray(),
            'ordertype' => 'vendor'
        ];
        // return view('vendor.print.pdf', compact('data'));
        $pdf = Pdf::loadView('vendor.print.pdf', $data);
        return $pdf->download($order->order_no . '_print.pdf');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if (ModelsVendorOrder::destroy($id)) {
            $json = ['type' => 1, 'msg' => 'Order is deleted'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function changeStatus($status, $id)
    {
        $order = ModelsVendorOrder::findOrFail($id);
        $orderDetail = VendorOrderDetail::where('vendor_order_id', $id)->get();
        $user = User::findOrFail($order->user_id);
        $ordershipping = $order->orderShippingDetail;
        $wallet =  Wallet::where('user_id', $order->user_id)->first();
        $msg = '';

        if ($status == "1") {  //order proccessing
            $msg = 'Your order no ' . $order->order_no . " is in Process";

            $notificationmsg = " is in Process";
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



                $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $user->id)->first();
                if ($user) {


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
            $notificationmsg = "Order is delivered";
            if ($status == "6") { // ordered redelivered
                User::where('id', $order->user_id)->update(['order_return' =>  DB::raw('order_return -' . $order->order_return)]);
                $notificationmsg = "Order is redelivered";
            }

            // giving commission to admin
            $adminmsg = $order->order_no . " order's comission, handle by the ";
            $usermsg = $order->order_no . " order's profit, handle by the ";
            if ($order->is_order_handle_by_admin) {
                $adminmsg .=  "admin";
                $usermsg .=  "admin";
            } else {
                $adminmsg .= "user";
                $usermsg .=  "user";
            }
            AdminAccountTransection::create([
                'amount' => $order->commission_amount,
                'is_credit' => 1,
                'description' => $adminmsg,
            ]);
            VendorAccountTransection::create([
                'user_id' => $order->seller_id,
                'vendor_id' => $order->vendor_id,
                'amount' => $order->vendor_amount,
                'is_credit' => 1,
                'description' => $usermsg,
            ]);
            $order->delivery_at = date("Y-m-d H:i:s");

            // order is COD and is manage by self don't make entry on vendor wallet 
            if (!($order->payment_by == '0' && $order->is_order_handle_by_admin == '0')) {
                $wallet = VendorWallet::updateOrCreate(
                    [
                        'user_id' => $order->seller_id,
                        'vendor_id' => $order->vendor_id
                    ],
                    ['amount' => DB::raw('amount + ' . $order->vendor_amount)]
                );
                VendorWalletTransaction::insert([
                    'wallet_id' => $wallet->id,
                    'user_id' => $order->seller_id,
                    'vendor_id' => $order->vendor_id,
                    'amount' => $order->vendor_amount,
                    'is_gift' => 0,
                    'status' => 1,
                    'detail' => 'Amount received from ' . $order->order . "handle by user",
                ]);
            }

            // adding outstanding amount in
            if ($order->is_order_handle_by_admin == "0") {
                $vendor = Vendor::findOrFail($order->vendor_id);
                // Increment the outstanding_amount directly
                $vendor->increment('outstanding_amount', $order->commission_amount);
            }
            //
        } elseif ($status == "-1") { //order cancelled
            $msg = 'Your order no ' . $order->order_no . " is Cancelled";
            // getting back the item from order detail
            $OrderDetail = VendorOrderDetail::where('vendor_order_id', $id)->get();
            foreach ($OrderDetail as $item) {
                $product = VendorProduct::find($item->vendor_product_id);
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
            $notificationmsg = "Order is cancelled";
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
                    ModelsVendorOrder::where('id', $order->id)->update(
                        ['order_return' => DB::raw('order_return + 1')]
                    );
                });
            }
            $user->notify(new OrderStatus($order, $msg, $ordershipping));
            $notificationmsg = "Order is returned";
        }
        $order->status = $status;
        $response = $order->save();
        if ($response) {
            // $user->notify(new OrderStatus($order, $msg, $ordershipping));
            $detail = $order->order_no . " " . $notificationmsg;
            $type = 4;
            $link = 'vendor/order/detail/' . $order->id;
            $user = User::find($order->seller_id);
            $vendornotification = new VendorOrderNotification($msg, $type, $link, $detail);
            Notification::send($user, $vendornotification);
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
        $order = ModelsVendorOrder::findOrFail($request->orderid);
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
        $order = ModelsVendorOrder::findOrFail($id);
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
