<?php // Code within app\Helpers\SettingHelper.php
namespace App\Helpers;

use App\Models\Setting;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorHelper
{

    public static function getVendorID()
    {
        $response = Vendor::where('user_id', Auth::guard('web')->user()->id)->first();
        return ($response) ? $response->id : "";
    }

    public static function getVendorLogo()
    {
        $response = Vendor::where('user_id', Auth::guard('web')->user()->id)->first();
        return ($response) ? $response->business_logo : "";
    }

    public static function getVendorBusinessname()
    {
        $response = Vendor::where('user_id', Auth::guard('web')->user()->id)->first();
        return ($response) ? $response->business_name : "";
    }

    public static function getVendorExists()
    {
        $response = Vendor::where('user_id', Auth::guard('web')->user()->id)->first();
        return ($response) ? true : false;
    }


    public static function getVendorByid($sellerId)
    {
        $response = Vendor::where('user_id', $sellerId)->first();
        return ($response) ? $response : false;
    }
}
