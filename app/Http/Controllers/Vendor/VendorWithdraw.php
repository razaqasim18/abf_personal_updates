<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\SettingHelper;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserAccountDetail;
use App\Models\Vendor;
use App\Models\VendorWithdraw as ModelVendorWithdraw;
use App\Models\Withdraw;
use App\Notifications\AdminNotification;
use App\Rules\VendorAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Rules\CheckVendorWithDrawRequest;

class VendorWithdraw extends Controller
{

    public function add()
    {
        $useraccount = UserAccountDetail::join('banks', 'banks.id', '=', 'user_account_details.bank_id')
            ->where('user_id', Auth::user()->id)
            ->get();
        return view('user.vendor.withdraw.add', [
            'useraccount' => ($useraccount) ? $useraccount : [],
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required', 'numeric', 'min:1000', new VendorAmount, new CheckVendorWithDrawRequest],
        ]);

        $withdraw = new ModelVendorWithdraw();
        $withdraw->user_id = Auth::guard('web')->user()->id;
        $withdraw->vendor_id = Auth::guard('web')->user()->vendor->id;
        $withdraw->requested_amount = $request->amount;
        $withdraw->transectioncharges = SettingHelper::getSettingValueBySLug('vendor_transection_charges');
        if ($withdraw->save()) {

            $msg = "New withdraw request has been placed vendor";
            $type = 1;
            $link = "admin/vendor/request/withdraw/detail/" . $withdraw->id;
            $detail = "Withdraw request of amount " . $request->amount . " by user Vendor ABF-" . Auth::guard('web')->user()->id;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
            Notification::send($admin, $adminnotification);

            return redirect()->route('vendor.withdraw.add')->with('success', "Balance request is made please wait for admin approval");
        } else {
            return redirect()->route('vendor.withdraw.add')->with('error', "Something went wrong please try again");
        }
    }

    public function history()
    {
        $withdraw = ModelVendorWithdraw::where('user_id', Auth::user()->id)
            ->where('vendor_id', Auth::user()->vendor->id)
            ->get();
        return view('user.vendor.withdraw.history', ['withdraw' => $withdraw]);
    }

    public function detail($id)
    {
        $withdraw = ModelVendorWithdraw::find($id);
        $user = User::where('id', $withdraw->user_id)->first();
        return view('user.vendor.withdraw.detail', [
            'withdraw' => $withdraw,
            'user' => $user,
        ]);
    }
}
