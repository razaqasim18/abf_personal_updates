<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SettingHelper;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\VendorRequest as ModelVendorRequest;
use App\Notifications\AdminNotification;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class VendorRequest extends Controller
{
    public  function  vendorRequestload()
    {
        $vendor = ModelVendorRequest::where('user_id', Auth::guard('web')->user()->id)->first();
        if ($vendor) {
            return view('user.vendor.request.form_resubmit', compact('vendor'));
        } else {
            return view('user.vendor.request.form');
        }
    }

    public function vendorRequestsave(Request $request)
    {
        if ($request->id) {
            $this->validate($request, [
                'business_name' => ['required'],
                'category' => ['required'],
                'mobile_phone' => ['required'],
                'website_link' => ['required'],
                'social_media_link' => ['required'],
            ]);
        } else {
            $this->validate($request, [
                'business_name' => ['required'],
                'category' => ['required'],
                'mobile_phone' => ['required'],
                'website_link' => ['required'],
                'social_media_link' => ['required'],
                'business_logo' => 'required|mimes:jpeg,png,jpg,gif',
            ]);
        }

        $userid = Auth::guard('web')->user()->id;
        $business_logo = null;
        if (!empty($request->file('business_logo'))) {
            $business_logo = "business_logo_" . $userid . '.' . $request->file('business_logo')->extension();
            $request
                ->file('business_logo')
                ->move(base_path('uploads/vendor/business_logo'), $business_logo);
            $business_logo = config('app.url') . "/uploads/vendor/business_logo/" . $business_logo;
        } else {
            if ($request->business_logo_show) {
                $business_logo = $request->business_logo_show;
            }
        }

        $shop_card = null;
        if (!empty($request->file('shop_card'))) {
            $shop_card = "shop_card_" . $userid . '.' . $request->file('shop_card')->extension();
            $request
                ->file('shop_card')
                ->move(base_path('uploads/vendor/shop_card'), $shop_card);
            $shop_card = config('app.url') . "/uploads/vendor/shop_card/" . $shop_card;
        } else {
            if ($request->shop_card_show) {
                $shop_card = $request->shop_card_show;
            }
        }

        $owner_image = null;
        if (!empty($request->file('owner_image'))) {
            $owner_image = "owner_image_" . $userid . '.' . $request->file('owner_image')->extension();
            $request
                ->file('owner_image')
                ->move(base_path('uploads/vendor/owner_image'), $owner_image);
            $owner_image = config('app.url') . "/uploads/vendor/owner_image/" . $owner_image;
        } else {
            if ($request->owner_image_show) {
                $owner_image = $request->owner_image_show;
            }
        }

        $data = [
            'business_name' => $request->business_name,
            'category' => $request->category,
            'shop_phone' => $request->shop_phone,
            'mobile_phone' => $request->mobile_phone,
            'business_logo' => $business_logo,
            'shop_card' => $shop_card,
            'business_mail' => $request->business_mail,
            'owner_image' => $owner_image,
            'website_link' => $request->website_link,
            'social_media_link' => $request->social_media_link,
            'business_address' => $request->business_address,
            'previous_work' => $request->previous_work,
            'business_withabf' => $request->business_withabf,
            'describe_product' => $request->describe_product,
            'career_goal' => $request->career_goal,
            'experience' => $request->experience,
            'delivery' => $request->delivery,
            'market_business' => $request->market_business,
            'herbel_product' => $request->herbel_product,
        ];

        if ($request->id) {
            $vendor =  ModelVendorRequest::findOrFail($request->id);
            $detail = "Resubmit vendor request by " . Auth::guard('web')->user()->name . " with " . " by user ABF-" . Auth::guard('web')->user()->id;
            $msg = "Vendor request is resubmitted";
        } else {
            $vendor = new  ModelVendorRequest();
            $vendor->user_id = Auth::guard('web')->user()->id;
            $detail = "Vendor request by " . Auth::guard('web')->user()->name . " with " . " by user ABF-" . Auth::guard('web')->user()->id;
            $msg = "Vendor request has been placed";
        }
        $vendor->status = 1;
        $vendor->vendor_data = json_encode($data);
        $response = $vendor->save();
        if ($response) {
            $msg = "Vendor request has been placed";
            $type = 1;
            $link = "admin/vendor/request/detail/" . $vendor->id;
            $detail = "Vendor request by " . Auth::guard('web')->user()->name . " with " . " by user ABF-" . Auth::guard('web')->user()->id;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
            Notification::send($admin, $adminnotification);
            $type = "success";
            $msg =  "Your application is submitted successfully";
        } else {
            $type = "error";
            $msg =  "Something went wrong please try again";
        }
        return redirect()->route('vendor.request.load')->with($type, $msg);
    }

    public function paymentLoad()
    {
        $bank = Bank::select("*", 'banks.id AS id')->join("business_accounts", "business_accounts.bank_id", "=", "banks.id")->where('business_accounts.is_active', '1')->get();
        $vendor = ModelVendorRequest::where("user_id", Auth::guard('web')->user()->id)->first();
        return view('user.vendor.request.payment', compact('bank', 'vendor'));
    }

    public function paymentSave(Request $request)
    {
        $this->validate($request, [
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
                ->move(base_path('uploads/vendor/payment_proof'), $image);
        } else {
            if ($request->payment_proof_show) {
                $image = $request->payment_proof_show;
            }
        }

        $amount = SettingHelper::getSettingValueBySLug('vendor_registration_charges');
        if (empty($amount)) {
            return redirect()->back()->with('error', "Vendor Charges is not defined by the admin yet");
        }

        $epinrequest = ModelVendorRequest::findOrFail($request->id);
        $epinrequest->bank_id = $request->bank_id;
        $epinrequest->transectionid = $request->transectionid;
        $epinrequest->amount = $amount;
        $epinrequest->proof = $image;
        $epinrequest->status = "3";
        $epinrequest->transectiondate = $request->date;

        if ($epinrequest->save()) {
            $msg = "Vendor payment has been made";
            $type = 1;
            $link = "admin/vendor/request/detail/" . $epinrequest->id;
            $detail = "New Vendor request payment has been placed by " . $request->email;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
            Notification::send($admin, $adminnotification);

            return redirect()->back()->with('success', "Vendor payment request is made please wait for admin approval");
        } else {
            return redirect()->back()->with('error', "Something went wrong please try again");
        }
    }
}
