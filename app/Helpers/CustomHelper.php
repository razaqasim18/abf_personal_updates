<?php // Code within app\Helpers\SettingHelper.php
namespace App\Helpers;

use App\Http\Controllers\Admin\VendorOrder;
use App\Models\Admin;
use App\Models\Commission;
use App\Models\EpinRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorOrder as ModelsVendorOrder;
use App\Models\VendorOrderDetail;
use App\Models\VendorProduct;
use App\Models\VendorWallet;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\OrderStatus;
use App\Notifications\VendorOrderNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomHelper
{
    public static function createNewEpin()
    {
        $count = 1;
        do {
            $newepin = Str::substr(Str::replace("-", "", Str::uuid()), 0, 12);
            $response = EpinRequest::where('epin', $newepin)->first();
            $count = (!$response) ? 0 : 1;
        } while ($count);
        return $newepin;
    }

    public static function getUserWalletAmountByid($id)
    {
        $wallet = Wallet::where('user_id', $id)->first();
        return ($wallet) ? $wallet->amount : "0";
    }

    public static function getVendorWalletAmountByid($id)
    {
        $wallet = VendorWallet::where('user_id', $id)->first();
        return ($wallet) ? $wallet->amount : "0";
    }

    public static function getUserWalletGiftByid($id)
    {
        $wallet = Wallet::where('user_id', $id)->first();
        return ($wallet) ? $wallet->gift : "0";
    }



    public static function orderWalletTrasection($userid, $totalpay)
    {
        $wallet = Wallet::where('user_id', $userid)->first();
        $wallet->amount = $wallet->amount - $totalpay;
        $walletresponse = $wallet->save();

        $wallettransaction = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $totalpay,
            'status' => 0,
        ]);

        $walletresponse = $wallettransaction = true;
        if (!($walletresponse && $wallettransaction)) {
            return false;
        }
        return true;
    }

    public static function orderWalletGiftTrasection($userid, $totalpay)
    {
        $wallet = Wallet::where('user_id', $userid)->first();
        $wallet->gift = $wallet->gift - $totalpay;
        $walletresponse = $wallet->save();

        $wallettransaction = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $totalpay,
            'status' => 0,
            'is_gift' => '1',
        ]);

        $walletresponse = $wallettransaction = true;
        if (!($walletresponse && $wallettransaction)) {
            return false;
        }
        return true;
    }

    public static function strWordCut($string, $length, $end = '....')
    {
        $string = strip_tags($string);

        if (strlen($string) > $length) {

            // truncate string
            $stringCut = substr($string, 0, $length);

            // make sure it ends in a word so assassinate doesn't become ass...
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . $end;
        }
        return $string;
    }

    public static function assignPointsToUserAndParents($orgid, $userid, $points)
    {
        $user = User::find($userid);
        if ($orgid != $userid) {
            $point = Point::updateOrCreate(
                ['user_id' => $userid],
                ['point' => DB::raw('point + ' . $points)],
            );
            PointTransaction::insert([
                'user_id' => $userid,
                'point_id' => $point->id,
                'point' => $points,
                'status' => 1,
                'is_child' => ($orgid == $userid) ? 0 : 1,
            ]);
        }
        if ($user->sponserid !== null) {
            self::assignPointsToUserAndParents($orgid, $user->sponserid, $points);
        }
        return true;
    }

    public static function productStock()
    {
        Product::where('stock', '<=', '0')->update(['in_stock' => 0]);
    }

    // all users
    public static function calculateUsersRank()
    {
        $user = User::select('commission_id', 'users.id AS userid', 'point')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->get();
        $commission = Commission::all();
        foreach ($user as $userrow) {
            foreach ($commission as $commissionrow) {
                if ($userrow->point >= $commissionrow->points) {
                    Point::updateOrCreate(
                        ['user_id' => $userrow->userid],
                        ['commission_id' => $commissionrow->id]
                    );

                    $pointtransactions = DB::table('point_transactions')
                        ->select(DB::raw("SUM(point) as point"))
                        ->where('status', '1')
                        ->where('user_id', $userrow->userid)
                        ->where('is_child', 0)
                        // ->whereMonth('created_at', '=', $currentMonth)
                        // ->whereYear('created_at', '=', $currentYear)
                        ->first();

                    if ($userrow->commission_id == null || $userrow->commission_id < $commissionrow->id) {
                        if ($pointtransactions->point >= 100) {
                            DB::transaction(function () use ($userrow, $commissionrow) {
                                $wallet = Wallet::updateOrCreate(
                                    ['user_id' => $userrow->userid],
                                    ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                                );
                                WalletTransaction::insert([
                                    'wallet_id' => $wallet->id,
                                    'amount' => $commissionrow->gift,
                                    'is_gift' => 1,
                                    'status' => 1,
                                ]);
                            });
                        }
                    }
                }
            }
        }
    }

    public static function calculateUserRank($userid)
    {
        $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $userid)->first();
        if ($user) {
            $commission = Commission::all();
            foreach ($commission as $commissionrow) {
                if ($user->point >= $commissionrow->points) {
                    Point::updateOrCreate(
                        ['user_id' => $userid],
                        ['commission_id' => $commissionrow->id]
                    );

                    $pointtransactions = DB::table('point_transactions')
                        ->select(DB::raw("SUM(point) as point"))
                        ->where('status', '1')
                        ->where('user_id', $userid)
                        ->where('is_child', 0)
                        // ->whereMonth('created_at', '=', $currentMonth)
                        // ->whereYear('created_at', '=', $currentYear)
                        ->first();

                    if ($user->commission_id == null || $user->commission_id < $commissionrow->id) {
                        if ($pointtransactions->point >= 100) {
                            DB::transaction(function () use ($userid, $commissionrow) {
                                $wallet = Wallet::updateOrCreate(
                                    ['user_id' => $userid],
                                    ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                                );
                                WalletTransaction::insert([
                                    'wallet_id' => $wallet->id,
                                    'amount' => $commissionrow->gift,
                                    'is_gift' => 1,
                                    'status' => 1,
                                    'detail' => 'reward when admin add points',
                                ]);
                            });
                        }
                    }
                }
            }
        }
    }

    public static function calculateAllPoints($userid)
    {
        $date = date("Y-m-d");
        $usertotalpoint = DB::table('point_transactions')
            ->select(DB::raw("SUM(point) as totapoint"))
            ->where('status', '1')
            ->where('user_id', $userid)
            ->where(DB::raw("MONTH(created_at) = MONTH($date)"))
            ->first();

        $childuser = DB::table('users')
            ->where('sponserid', $userid)
            ->where('is_deleted', 0)
            ->where('is_blocked', 0)
            ->get();

        // Calculate the points for each child user recursively
        $childPoints = 0;
        foreach ($childuser as $row) {
            $childPoints = (int) $childPoints + self::calculateAllPoints($row->id);
        }

        return $usertotalpoint->totapoint + $childPoints;
    }

    public static function calculatePoint($userid)
    {
        $date = date("Y-m-d");
        $usertotalpoint = DB::table('point_transactions')
            ->select(DB::raw("SUM(point) as totalpoint"))
            ->where('status', '1')
            ->where('user_id', $userid)
            ->where(DB::raw("MONTH(created_at) = MONTH($date)"))
            ->first();
        return $usertotalpoint->totalpoint;
    }

    public static function directChildCommission()
    {
        $output = '';
        $user = User::where('is_blocked', '0')->where('is_deleted', '0')->get();
        foreach ($user as $row) {
            // $childpoints = self::calculateChildPoint($row->id);
            $childcommision = self::calculateCommission($row->id);
            $output .= "commission " . $childcommision . " - user : " . $row->id . "\n";
            if ($childcommision) {
                $wallet = Wallet::updateOrCreate(
                    ['user_id' => $row->id],
                    ['amount' => DB::raw('amount + ' . $childcommision)]
                );
                WalletTransaction::insert([
                    'wallet_id' => $wallet->id,
                    'amount' => $childcommision,
                    'status' => 2,
                    'detail' => 'comission',
                ]);
            }
        }
        $currentDate = Carbon::now();
        // $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
        $filePath =  $currentYear . "-" .  $currentMonth  . '.txt'; // File path where you want to store the output
        Storage::disk('local')->put($filePath, $output);
    }

    public static function calculateChildPoint($userid)
    {
        $childuser = DB::table('users')
            ->where('sponserid', $userid)
            ->where('is_deleted', 0)
            ->where('is_blocked', 0)
            ->get();
        $userpoint = Point::where('user_id', $userid)->first();
        $childPoints = 0;

        if ($userpoint && $userpoint->commission_id != null) {
            $usercommissionid = $userpoint->commission_id;
            $currentDate = Carbon::now()->subMonth();
            // $currentDate = Carbon::now();
            $currentMonth = $currentDate->month;
            $currentYear = $currentDate->year;
            foreach ($childuser as $row) {
                $usertotalpoint = DB::table('point_transactions')
                    ->select(DB::raw("SUM(point) as totalpoint"))
                    ->where('status', '1')
                    // ->where('is_child', '1')
                    ->where('user_id', $row->id)
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->first();

                $rowpoint = Point::where('user_id', $row->id)->first();
                if ($rowpoint && $usercommissionid > $rowpoint->commission_id && $rowpoint != null) {
                    $childPoints = $childPoints + $usertotalpoint->totalpoint;
                }
            }
        }
        return $childPoints;
    }

    // updated code
    public static function calculateCommission($userid)
    {
        $point = Point::where('user_id', $userid)->first();
        $calculatedcommision = 0;
        if ($point) {
            if ($point->commission_id != null) {
                $commission = Commission::findorFail($point->commission_id);
                $currentDate = Carbon::now()->subMonth();
                // $currentDate = Carbon::now();
                $currentMonth = $currentDate->month;
                $currentYear = $currentDate->year;

                //current user points old this not return null if no record found
                $usertotalpoint = DB::table('point_transactions')
                    ->select(DB::raw("SUM(point) as totalpoint"))
                    ->where('status', '1')
                    ->where('is_child', '0')
                    ->where('user_id', $userid)
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->first();


                // echo  "User personal point " . $usertotalpoint->totalpoint . "<br/>";
                $childuser = DB::table('users')
                    ->where('sponserid', $userid)
                    ->where('is_deleted', 0)
                    ->where('is_blocked', 0)
                    ->get();

                $childcommision = $nonchildcommission = 0;
                foreach ($childuser as $row) {

                    $childpoint = Point::where('user_id', $row->id)->first();
                    $childpointtransactions = DB::table('point_transactions')
                        ->select(DB::raw("SUM(point) as point"))
                        ->where('status', '1')
                        ->where('user_id', $row->id)
                        ->whereMonth('created_at', '=', $currentMonth)
                        ->whereYear('created_at', '=', $currentYear)
                        ->first();
                    if ($childpoint) {
                        if ($childpoint->commission_id != null && $childpoint->commission_id < $point->commission_id) {
                            echo "1 step" . "<br/>";
                            $childcommission = Commission::findorFail($childpoint->commission_id);
                            if ($commission->ptp == 0) {
                                echo "1 step commission ptp" . "<br/>";

                                if ($usertotalpoint->totalpoint == null) {
                                    echo "1 step usertotal point totalpoint is null" . "<br/>";
                                    $calculatedcommision = ($childpointtransactions->point * (($commission->profit - $childcommission->profit) / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                    $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                                    $childcommision = $childcommision + $calculatedcommision;
                                    echo "1 step commission " . $childcommision . "<br/>";
                                } else {
                                    echo "1 step usertotalpoint totalpoint is null" . "<br/>";
                                    $calculatedcommision = ($childpointtransactions->point * (($commission->profit - $childcommission->profit) / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                    $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                                    $childcommision = $childcommision + $calculatedcommision;
                                    echo "1 step commission " . $childcommision . "<br/>";
                                }
                            } else {
                                if ($usertotalpoint->totalpoint >= $commission->ptp) {
                                    echo "1 step commission ptp is not null" . "<br/>";
                                    $calculatedcommision = ($childpointtransactions->point * (($commission->profit - $childcommission->profit) / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                    $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                                    $childcommision = $childcommision + $calculatedcommision;
                                    echo "1 step ptp is not null " . $childcommision . "<br/>";
                                }
                            }
                        } else if ($childpoint->commission_id != null && $childpoint->commission_id >= $point->commission_id) {
                            echo "2 step" . "<br/>";
                            $childcommision = $childcommision + 0;
                        } else if ($childpoint->commission_id == null) {
                            echo "3 step" . "<br/>";
                            $calculatedcommision = ($childpointtransactions->point * (($commission->profit) / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                            $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                            $childcommision = $childcommision + $calculatedcommision;
                            echo "3 step commission " . $childcommision . "<br/>";
                        }
                    }
                }
                $calculatedcommision = $childcommision + $nonchildcommission;
            }
        }
        return $calculatedcommision;
    }

    public static function calculateUserPersonalPoint($userid)
    {
        $result = PointTransaction::select(DB::raw("SUM(point) as count"))
            ->where('user_id', $userid)
            ->where('status', 1)
            ->where('is_child', 0)
            ->first();
        return $result;
    }


    public static function calculateAllUserChildPersonalPoint($userid)
    {
        $date = date("Y-m-d");
        $usertotalpoint = DB::table('point_transactions')
            ->select(DB::raw("SUM(point) as totapoint"))
            ->where('status', '1')
            ->where('user_id', $userid)
            ->where(DB::raw("MONTH(created_at) = MONTH($date)"))
            ->first();

        $childuser = DB::table('users')
            ->where('sponserid', $userid)
            ->where('is_deleted', 0)
            ->where('is_blocked', 0)
            ->get();

        // Calculate the points for each child user recursively
        $childPoints = 0;
        foreach ($childuser as $row) {
            $childPoints = (int) $childPoints + self::calculateAllUserChildPersonalPoint($row->id);
        }

        return $usertotalpoint->totapoint + $childPoints;
    }

    public static function normalOrderCancelPolicy($status, $id, $type)
    {

        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->user_id);

        if ($status == "-1") {
            $msg = 'Your order no ' . $order->order_no . " is Cancelled";
            $orderShipping = $order->orderShippingDetail;

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
                        'detail' => 'order cancel by client payment was by wallet',
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
                        'detail' => 'order cancel by client payment was by reward',
                    ]);
                });
            }
            $user->notify(new OrderStatus($order, $msg, $orderShipping));
        } else {
            $msg = 'Your order no ' . $order->order_no . " is Re-Ordered";
            $detail = 'Order ' . $order->order_no . ' is reorders';
            $admin = Admin::find(1);
            $type = 4;
            $link = 'admin/order/detail/' . $order->id;
            $adminnotification = new AdminNotification($msg, $type, $link, $detail);
            Notification::send($admin, $adminnotification);
        }
        $order->status = $status;
        $response = $order->save();
        if ($response) {
            return $msg;
        } else {
            return false;
        }
    }

    public static function vendorOrderCancelPolicy($status, $id, $type)
    {
        try {
            //code...

            $order = ModelsVendorOrder::findOrFail($id);
            $user = User::findOrFail($order->user_id);

            if ($status == "-1") {
                $msg = 'Your order no ' . $order->order_no . " is Cancelled";
                $orderShipping = $order->orderShippingDetail;

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
                            'detail' => 'order cancel by client payment was by wallet',
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
                            'detail' => 'order cancel by client payment was by reward',
                        ]);
                    });
                }
                $user->notify(new OrderStatus($order, $msg, $orderShipping));
            } else {
                $msg = 'Your order no ' . $order->order_no . " is Re-Ordered";
                $detail = 'Order ' . $order->order_no . ' is reorders';
                $admin = Admin::find(1);
                $type = 4;
                $link = 'admin/vendor/order/detail/' . $order->id;
                $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
                Notification::send($admin, $adminnotification);

                $link = 'vendor/order/detail/' . $order->id;
                $user = User::find($order->seller_id);
                $vendornotification = new VendorOrderNotification($msg, $type, $link, $detail);
                Notification::send($user, $vendornotification);
            }
            $order->status = $status;
            $response = $order->save();
            if ($response) {
                return $msg;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
    }
}
