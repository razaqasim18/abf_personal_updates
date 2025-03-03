<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
            [
                "setting_slug" => "register_reward",
                "setting_value" => "100",
            ],
            [
                "setting_slug" => "epin_charges",
                "setting_value" => "250",
            ],
            [
                "setting_slug" => "transection_charges",
                "setting_value" => "10",
            ],
            [
                "setting_slug" => "gst_charges",
                "setting_value" => "10",
            ],
            [
                "setting_slug" => "shipping_charges",
                "setting_value" => "100",
            ],
            [
                "setting_slug" => "customized_shipping_charges",
                "setting_value" => "100",
            ],
            [
                "setting_slug" => "admin_charges",
                "setting_value" => "10",
            ],
            [
                "setting_slug" => "money_rate",
                "setting_value" => "60",
            ],
            [
                "setting_slug" => "coupon_discount",
                "setting_value" => "20",
            ],
            [
                "setting_slug" => "site_name",
                "setting_value" => "ABF",
            ],
            [
                "setting_slug" => "site_primary_color",
                "setting_value" => "#6778f0",
            ],
        ]);
    }
}
