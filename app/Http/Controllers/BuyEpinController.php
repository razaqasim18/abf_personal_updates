<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\EpinRequest;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\EpinRequestApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Rules\UserAmount;
use App\Rules\UserOrderGift;
use App\Rules\UserOrderWallet;
use DB;
use Auth;

class BuyEpinController extends Controller
{
    public function epinList()
    {
        $epin = EpinRequest::where('referred_by', Auth::guard('web')->user()->id)->get();
        return view('user.buy_epin.list', compact('epin'));
    }

    public function epinAdd()
    {
        return view('user.buy_epin.add');
    }

    public function epinInsert(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required|unique:epin_requests',
            'phone' => 'required|unique:epin_requests',
        ]);
        if ($request->referred_payed_by) { //by reward
            $this->validate(
                $request,
                [
                    'amount' => ['required', 'numeric', new UserOrderGift($request->amount)],
                ]
            );
            $referredPayedbywallet = ['gift' => DB::raw('gift - ' . $request->amount)];
            $referredPayedbyPost = 1;
            $is_gift = 1;
        } else { //by wallet
            $this->validate(
                $request,
                [
                    'amount' => ['required', 'numeric', new UserOrderWallet($request->amount)],
                ]
            );
            $referredPayedbyPost = 0;
            $referredPayedbywallet = ['amount' => DB::raw('amount - ' . $request->amount)];
            $is_gift = 0;
        }

        DB::beginTransaction();
        $uuid = CustomHelper::createNewEpin();
        $epinrequest = new EpinRequest();
        $epinrequest->email = $request->email;
        $epinrequest->phone = $request->phone;
        $epinrequest->amount = $request->amount;
        $epinrequest->approved_at = date("Y-m-d H:i:s");
        $epinrequest->referred_by = Auth::guard('web')->user()->id;
        $epinrequest->referred_payed_by = $referredPayedbyPost;
        $epinrequest->epin = $uuid;
        $epinrequest->status = 1;
        $response = $epinrequest->save();

        $wallet = Wallet::updateOrCreate(
            ['user_id' => Auth::guard('web')->user()->id],
            $referredPayedbywallet
        );
        $wallettransaction = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'is_gift' => $is_gift,
            'status' => 0,
            'detail' => 'buyyed RG-code for ' .  $request->email,
        ]);

        if ($response && $wallet && $wallettransaction) {
            DB::commit();
            $msg = "New RG-Code has been added";
            $type = 1;
            $link = "admin/request/epin/detail/" . $epinrequest->id;
            $detail = "New RG-Code added has been user ABF-" .  Auth::guard('web')->user()->id;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail);
            Notification::send($admin, $adminnotification);

            $epinrequest = EpinRequest::find($epinrequest->id);
            $epinrequest->notify(new EpinRequestApprovedNotification($uuid, $epinrequest));
            return redirect()->route('buy.rgcode.add')->with('success', "Epin request is made please wait for admin approval");
        } else {
            DB::rollback();
            return redirect()->route('buy.rgcode.add')->with('error', "Something went wrong please try again");
        }
    }
}
