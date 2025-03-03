<?php // Code within app\Helpers\SettingHelper.php
namespace App\Helpers;

use App\Models\Setting;
use App\Models\Vendor;

class SettingHelper
{
    public static function shout(string $string)
    {
        return strtoupper($string);
    }

    public static function getSettingValueBySLug($slug)
    {
        $response = Setting::where('setting_slug', $slug)->first();
        return ($response) ? $response->setting_value : "0";
    }

    public static function getVendordeliveryCharges($id)
    {
        $response = Vendor::where("user_id", $id)->first();
        return ($response) ? $response->delivery_charges : "0";
    }
}
