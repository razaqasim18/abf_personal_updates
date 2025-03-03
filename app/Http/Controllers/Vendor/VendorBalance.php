<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BalanceRequest;
use App\Models\Bank;
use App\Models\BusinessAccount;
use App\Models\User;
use App\Models\VendorBalanceRequest;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class VendorBalance extends Controller
{
    public function add()
    {
        $businessaccount = BusinessAccount::select("*", 'banks.name AS bankname')->join('banks', 'banks.id', '=', 'business_accounts.bank_id')->where('business_accounts.is_active', '1')->get();
        $bank = Bank::all();

        return view('user.vendor.balance.add', [
            'businessaccount' => $businessaccount,
            'bank' => $bank,
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'bank_id' => 'required',
            'transectionid' => 'required|unique:vendor_balance_requests',
            'date' => 'required',
            'amount' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif',
        ]);

        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/balance_proof'), $image);
        }

        $balance = new VendorBalanceRequest();
        $balance->user_id = Auth::guard('web')->user()->id;
        $balance->vendor_id = Auth::guard('web')->user()->vendor->id;
        $balance->bank_id = $request->bank_id;
        $balance->transectionid = $request->transectionid;
        $balance->amount = $request->amount;
        $balance->proof = $image;
        $balance->transectiondate = $request->date;

        if ($balance->save()) {

            $msg = "New balance request has been placed";
            $type = 1;
            $link = "admin/vendor/request/balance/detail/" . $balance->id;
            $detail = "Balance request of amount " . $request->amount . " by user Vendor ABF-" . Auth::guard('web')->user()->id;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
            Notification::send($admin, $adminnotification);

            return redirect()->route('vendor.balance.add')->with('success', "Balance request is made please wait for admin approval");
        } else {
            return redirect()->route('vendor.balance.add')->with('error', "Something went wrong please try again");
        }
    }

    public function history()
    {
        $balance = VendorBalanceRequest::select('*', 'vendor_balance_requests.id AS id')
            ->join('banks', 'banks.id', '=', 'vendor_balance_requests.bank_id')
            ->where('vendor_balance_requests.user_id', Auth::user()->id)
            ->orderBy("vendor_balance_requests.id", 'DESC')->get();
        return view('user.vendor.balance.history', ['balance' => $balance]);
    }

    public function detail($id)
    {
        $balance = VendorBalanceRequest::find($id);
        $user = User::where('id', $balance->user_id)->first();
        return view('user.balance.detail', [
            'balance' => $balance,
            'user' => $user,
        ]);
    }
}
