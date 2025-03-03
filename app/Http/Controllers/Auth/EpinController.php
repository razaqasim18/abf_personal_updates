<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\EpinRequest;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EpinController extends Controller
{
    public function loadEpinRequest()
    {
        $bank = Bank::select("*", 'banks.id AS id')->join("business_accounts", "business_accounts.bank_id", "=", "banks.id")->where('business_accounts.is_active', '1')->get();
        $paymentmethod = array();
        return view('auth.epin', [
            // 'paymentmethod' => $paymentmethod,
            "bank" => $bank,
        ]);
    }

    public function saveEpinRequest(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required|unique:epin_requests',
            'phone' => 'required|unique:epin_requests',
            'bank_id' => 'required',
            'transectionid' => 'required|unique:epin_requests',
            'date' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/epin_proof'), $image);
        }

        $amount = SettingHelper::getSettingValueBySLug('epin_charges');
        if (empty($amount)) {
            return redirect()->route('request.epin.load')->with('error', "Epin Charges is not defined by the admin yet");
        }

        $epinrequest = new EpinRequest();
        $epinrequest->bank_id = $request->bank_id;
        $epinrequest->transectionid = $request->transectionid;
        $epinrequest->email = $request->email;
        $epinrequest->phone = $request->phone;
        $epinrequest->amount = $amount;
        $epinrequest->proof = $image;
        $epinrequest->transectiondate = $request->date;

        if ($epinrequest->save()) {
            $msg = "New RG-Code has been request";
            $type = 1;
            $link = "admin/request/epin/detail/" . $epinrequest->id;
            $detail = "New RG-Code request has been placed by " . $request->email;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail);
            Notification::send($admin, $adminnotification);

            return redirect()->route('request.epin.load')->with('success', "Epin request is made please wait for admin approval");
        } else {
            return redirect()->route('request.epin.load')->with('error', "Something went wrong please try again");
        }
    }
}
