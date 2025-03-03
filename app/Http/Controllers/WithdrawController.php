<?php

namespace App\Http\Controllers;

use App\Helpers\SettingHelper;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserAccountDetail;
use App\Models\Withdraw;
use App\Notifications\AdminNotification;
use App\Rules\UserAmount;
use App\Rules\CheckWithDrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class WithdrawController extends Controller
{
    public function add()
    {
        $useraccount = UserAccountDetail::join('banks', 'banks.id', '=', 'user_account_details.bank_id')->where('user_id', Auth::user()->id)->get();
        return view('user.withdraw.add', [
            'useraccount' => ($useraccount) ? $useraccount : [],
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required', 'numeric', 'min:1000', new UserAmount, new CheckWithDrawRequest],
        ]);

        $withdraw = new Withdraw();
        $withdraw->user_id = Auth::guard('web')->user()->id;
        $withdraw->requested_amount = $request->amount;
        $withdraw->transectioncharges = SettingHelper::getSettingValueBySLug('transection_charges');
        if ($withdraw->save()) {

            $msg = "New withdraw request has been placed";
            $type = 1;
            $link = "admin/request/withdraw/detail/" . $withdraw->id;
            $detail = "Withdraw request of amount " . $request->amount . " by user ABF-" . Auth::guard('web')->user()->id;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail);
            Notification::send($admin, $adminnotification);

            return redirect()->route('withdraw.add')->with('success', "Balance request is made please wait for admin approval");
        } else {
            return redirect()->route('withdraw.add')->with('error', "Something went wrong please try again");
        }
    }

    public function history()
    {
        $withdraw = Withdraw::where('user_id', Auth::user()->id)->get();
        return view('user.withdraw.history', ['withdraw' => $withdraw]);
    }

    public function detail($id)
    {
        $withdraw = Withdraw::find($id);
        $user = User::where('id', $withdraw->user_id)->first();
        return view('user.withdraw.detail', [
            'withdraw' => $withdraw,
            'user' => $user,
        ]);
    }
}
