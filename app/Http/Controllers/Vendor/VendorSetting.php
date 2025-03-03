<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorSetting extends Controller
{
    public function businessLoad()
    {
        $vendor = Vendor::where('user_id', Auth::guard('web')->user()->id)->first();
        return view('user.vendor.setting.business', [
            'vendor' => $vendor
        ]);
    }

    public function businessUpate(Request $request)
    {
        $this->validate($request, [
            'business_name' => 'required',
            'business_mail' => 'required',
            'shop_phone' => 'required',
            'mobile_phone' => 'required',
            'delivery_charges' => 'required',
        ]);
        $userid = Auth::guard('web')->user()->id;

        $business_logo = null;
        if (!empty($request->file('business_logo'))) {
            $business_logo = "business_logo_" . $userid . '.' . $request->file('business_logo')->extension();
            $request
                ->file('business_logo')
                ->move(base_path('uploads/vendor/business_logo'), $business_logo);
            $business_logo = config('app.url') . "/uploads/vendor/business_logo/" . $business_logo;
        } else {
            $business_logo = $request->oldbusiness_logo;
        }

        $shop_card = null;
        if (!empty($request->file('shop_card'))) {
            $shop_card = "shop_card_" . $userid . '.' . $request->file('shop_card')->extension();
            $request
                ->file('shop_card')
                ->move(base_path('uploads/vendor/shop_card'), $shop_card);
            $shop_card = config('app.url') . "/uploads/vendor/shop_card/" . $shop_card;
        } else {
            $shop_card = $request->oldshop_card;
        }

        $vendor = Vendor::findOrFail($request->id);
        $vendor->business_address = $request->business_name;
        $vendor->business_mail = $request->business_mail;
        $vendor->shop_phone = $request->shop_phone;
        $vendor->mobile_phone = $request->mobile_phone;
        $vendor->website_link = $request->website_link;
        $vendor->social_media_link = $request->social_media_link;
        $vendor->business_logo = $business_logo;
        $vendor->shop_card =  $shop_card;
        $vendor->business_address = $request->business_address;
        $vendor->delivery_charges = $request->delivery_charges;
        $vendor->is_order_handle_by_admin = $request->is_order_handle_by_admin;
        if ($vendor->save()) {
            return redirect()
                ->route('vendor.setting.business.load')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('vendor.setting.business.load')
                ->with('error', 'Something went wrong');
        }
    }
}
