<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SettingController extends Controller
{
    public function loadSiteSetting()
    {
        return view('admin.setting.site');
    }

    public function saveSiteSetting(Request $request)
    {
        $sitelogo = null;
        $data = [];
        if (!empty($request->file('site_logo'))) {
            $sitelogo = time() . 'logo.' . $request->file('site_logo')->extension();
            $request
                ->file('site_logo')
                ->move(base_path('uploads/setting'), $sitelogo);
        } else {
            $sitelogo = $request->sitelogoimage;
        }
        if ($sitelogo) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_logo'],
                ['setting_value' => $sitelogo]
            );
        }

        $sitefavicon = null;
        if (!empty($request->file('site_favicon'))) {
            $sitefavicon = time() . 'icon.' . $request->file('site_favicon')->extension();
            $request
                ->file('site_favicon')
                ->move(base_path('uploads/setting'), $sitefavicon);
        } else {
            $sitefavicon = $request->sitefaviconimage;
        }

        if ($sitefavicon) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_favicon'],
                ['setting_value' => $sitefavicon]
            );
        }

        if ($request->site_name) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_name'],
                ['setting_value' => $request->site_name]
            );
        }

        if ($request->site_primary_color) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_primary_color'],
                ['setting_value' => $request->site_primary_color]
            );
        }

        if ($request->site_secondary_color) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_secondary_color'],
                ['setting_value' => $request->site_secondary_color]
            );
        }

        $catalog = null;
        if (!empty($request->file('catalog'))) {
            $catalog = time() . "." . $request->file('catalog')->extension();
            $request
                ->file('catalog')
                ->move(base_path('uploads/catalog'), $catalog);
        } else {
            $catalog = $request->sitecatalog;
        }

        if ($catalog) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'catalog'],
                ['setting_value' => $catalog]
            );
        }

        // customize ticker
        if ($request->customized_ticker) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'customized_ticker'],
                ['setting_value' => ($request->customized_ticker) ? trim($request->customized_ticker) : "",]
            );
        }

        if ($request->site_email) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_email'],
                ['setting_value' => ($request->site_email) ? trim($request->site_email) : "",]
            );
        }

        if ($request->site_phone) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_phone'],
                ['setting_value' => ($request->site_phone) ? trim($request->site_phone) : "",]
            );
        }

        // login background ground
        $sitelogin = null;
        if (!empty($request->file('site_login'))) {
            $sitelogin = time() . 'login.' . $request->file('site_login')->extension();
            $request
                ->file('site_login')
                ->move(base_path('uploads/setting'), $sitelogin);
        } else {
            $sitelogin = $request->siteloginimage;
        }

        if ($sitelogin) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_login_backgroundimage'],
                ['setting_value' => $sitelogin]
            );
        }

        // register background ground
        $siteregister = null;
        if (!empty($request->file('site_register'))) {
            $siteregister = time() . 'register.' . $request->file('site_register')->extension();
            $request
                ->file('site_register')
                ->move(base_path('uploads/setting'), $siteregister);
        } else {
            $siteregister = $request->siteregisterimage;
        }

        if ($siteregister) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_register_backgroundimage'],
                ['setting_value' => $siteregister]
            );
        }

        // epin request background ground
        $siteepin = null;
        if (!empty($request->file('site_epin'))) {
            $siteepin = time() . 'epin.' . $request->file('site_epin')->extension();
            $request
                ->file('site_epin')
                ->move(base_path('uploads/setting'), $siteepin);
        } else {
            $siteepin = $request->siteepinimage;
        }

        if ($siteepin) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'site_epin_backgroundimage'],
                ['setting_value' => $siteepin]
            );
        }

        if ($setting) {
            return redirect()
                ->route('admin.setting.site')
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.setting.site')
                ->with('error', 'Something went wrong');
        }
    }

    public function loadChargesSetting()
    {
        return view('admin.setting.charges');
    }

    public function saveChargesSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'epin_charges' => 'required|integer|min:0',
            'epin_panel_charges' => 'required|integer|min:0',
            'transection_charges' => 'required|integer|min:0',
            'vendor_transection_charges' => 'required|integer|min:0',
            'gst_charges' => 'required',
            'shipping_charges' => 'required|integer|min:0',
            'customized_shipping_charges' => 'required|integer|min:0',
            'return_charges' => 'required|min:0',
            'admin_charges' => 'required|integer|min:0',
            'money_rate' => 'required|integer|min:0',
            'coupon_discount' => 'required|integer|min:0',
            'register_reward' => 'required|integer|min:0',
            'vendor_registration_charges' => 'required|integer|min:0',
            'vendor_order_commission' => 'required|integer|min:0',
            'vendor_order_handle_by_admin_comission' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            // If validation fails, you can redirect back with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'epin_charges'],
            ['setting_value' => $request->epin_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'epin_panel_charges'],
            ['setting_value' => $request->epin_panel_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'transection_charges'],
            ['setting_value' => $request->transection_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'vendor_transection_charges'],
            ['setting_value' => $request->vendor_transection_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'gst_charges'],
            ['setting_value' => $request->gst_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'shipping_charges'],
            ['setting_value' => $request->shipping_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'customized_shipping_charges'],
            ['setting_value' => $request->customized_shipping_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'return_charges'],
            ['setting_value' => $request->return_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'admin_charges'],
            ['setting_value' => $request->admin_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'money_rate'],
            ['setting_value' => $request->money_rate]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'coupon_discount'],
            ['setting_value' => $request->coupon_discount]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'register_reward'],
            ['setting_value' => $request->register_reward]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'vendor_registration_charges'],
            ['setting_value' => $request->vendor_registration_charges]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'vendor_order_commission'],
            ['setting_value' => $request->vendor_order_commission]
        );

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'vendor_order_handle_by_admin_comission'],
            ['setting_value' => $request->vendor_order_handle_by_admin_comission]
        );

        if ($setting) {
            return redirect()
                ->route('admin.setting.charges')
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.setting.charges')
                ->with('error', 'Something went wrong');
        }
    }

    public function bannerSetting()
    {
        $banner = Banner::where('type', "homepage_slider")->where('title', "homepage_slider")->first();
        $dashboardbanner = Banner::where('type', "dashboard_slider")->where('title', "dashboard_slider")->first();
        $vendorbanner = Banner::where('type', "vendor_dashboard_slider")->where('title', "vendor_dashboard_slider")->first();

        $shopbanner = Banner::where('type', "home_page")->where('title', "shop_banner")->first();
        $otherbanner = Banner::where('type', "home_page")->where('title', "other_brand_banner")->first();
        $customizebanner = Banner::where('type', "home_page")->where('title', "customize_banner")->first();

        return view('admin.setting.banner', [
            "banner" => $banner,
            'dashboardbanner' => $dashboardbanner,
            'vendorbanner' => $vendorbanner,
            'shopbanner' => $shopbanner,
            'otherbanner' => $otherbanner,
            'customizebanner' => $customizebanner,

        ]);
    }

    public function saveOtherBannerSetting(Request $request)
    {
        // ✅ Validate all banner images in one go
        $request->validate([
            'shop_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'other_brand_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'customize_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // ✅ Handle shop_banner
        if ($request->hasFile('shop_banner')) {
            $shopBanner = Banner::firstOrCreate(
                ['title' => 'shop_banner', 'type' => 'home_page'],
                ['title' => 'shop_banner', 'type' => 'home_page']
            );
            $shopBanner->addMediaFromRequest('shop_banner')->toMediaCollection('home_page_shop_banner');
        }

        // ✅ Handle other_brand_banner
        if ($request->hasFile('other_brand_banner')) {
            $otherBrandBanner = Banner::firstOrCreate(
                ['title' => 'other_brand_banner', 'type' => 'home_page'],
                ['title' => 'other_brand_banner', 'type' => 'home_page']
            );
            $otherBrandBanner->addMediaFromRequest('other_brand_banner')->toMediaCollection('home_page_other_brand_banner');
        }

        // ✅ Handle customize_banner
        if ($request->hasFile('customize_banner')) {
            $customizeBanner = Banner::firstOrCreate(
                ['title' => 'customize_banner', 'type' => 'home_page'],
                ['title' => 'customize_banner', 'type' => 'home_page']
            );
            $customizeBanner->addMediaFromRequest('customize_banner')->toMediaCollection('home_page_customize_banner');
        }

        return redirect()
            ->route('admin.setting.banner')
            ->with('othersuccess', 'Banners updated successfully');
    }

    public function saveBannerSetting(Request $request)
    {
        // Validate uploaded images
        $request->validate([
            'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if homepage_slider banner exists
        $banner = Banner::where('type', 'homepage_slider')->first();

        // If not, create it
        if (!$banner) {
            $banner = new Banner();
            $banner->title = 'homepage_slider';
            $banner->type = 'homepage_slider';
            $banner->save();
        }

        // Attach media files
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $banner->addMedia($file)->toMediaCollection('homepage_slider');
            }
        }

        return redirect()->route('admin.setting.banner')->with('success', 'Images added to Homepage Slider successfully');
    }

    public function saveVendorBannerSetting(Request $request)
    {
        // Validate uploaded images
        $request->validate([
            'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if dashboard_slider banner exists
        $banner = Banner::where('type', 'vendor_dashboard_slider')->first();

        // If not, create it
        if (!$banner) {
            $banner = new Banner();
            $banner->title = 'vendor_dashboard_slider';
            $banner->type = 'vendor_dashboard_slider';
            $banner->save();
        }

        // Attach media files
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $banner->addMedia($file)->toMediaCollection('vendor_dashboard_slider');
            }
        }

        return redirect()->route('admin.setting.banner')->with('vendorsuccess', 'Images added to Vendor Dashboard Slider successfully');
    }

    public function saveDashboardBannerSetting(Request $request)
    {
        // Validate uploaded images
        $request->validate([
            'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if dashboard_slider banner exists
        $banner = Banner::where('type', 'dashboard_slider')->first();

        // If not, create it
        if (!$banner) {
            $banner = new Banner();
            $banner->title = 'dashboard_slider';
            $banner->type = 'dashboard_slider';
            $banner->save();
        }

        // Attach media files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $banner->addMedia($file)->toMediaCollection('dashboard_slider');
            }
        }

        return redirect()->route('admin.setting.banner')->with('dashboardsuccess', 'Images added to User Dashboard Slider successfully');
    }

    public function deleteMedia($mediaId)
    {
        $media = Media::find($mediaId);

        if ($media) {
            // This deletes the file from storage and the database entry
            $media->delete();

            return response()->json([
                'type' => 1,
                'msg' => 'Media deleted successfully',
            ]);
        }

        return response()->json([
            'type' => 0,
            'msg' => 'Media not found or already deleted',
        ]);
    }
}
