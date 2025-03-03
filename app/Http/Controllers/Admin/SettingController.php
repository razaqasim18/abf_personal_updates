<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
        $banner = Banner::where('type', 1)->get();
        $dashboardbanner = Banner::where('type', 0)->get();
        $vendorbanner = Banner::where('type', 2)->get();
        return view('admin.setting.banner', ["banner" => $banner, 'dashboardbanner' => $dashboardbanner, 'vendorbanner' => $vendorbanner]);
    }

    public function saveOtherBannerSetting(Request $request)
    {
        $shop_banner = null;
        if (!empty($request->file('shop_banner'))) {
            $uploadedFile = $request->file('shop_banner');
            $shop_banner = "shop_banner" . time() . '.' . $uploadedFile->extension();

            // Move the uploaded file to the specified directory
            $uploadedFile->move(base_path('uploads/setting'), $shop_banner);
        } else {
            $shop_banner = $request->shopbannerimage;
        }

        if ($shop_banner) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'shop_banner'],
                ['setting_value' => $shop_banner]
            );
        }

        $other_brand_banner = null;
        if (!empty($request->file('other_brand_banner'))) {
            $other_brand_banner = "other_brand_banner" . time() . "." . $request->file('other_brand_banner')->extension();
            $request
                ->file('other_brand_banner')
                ->move(base_path('uploads/setting'), $other_brand_banner);
        } else {
            $other_brand_banner = $request->otherbrandbannerimage;
        }

        if ($other_brand_banner) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'other_brand_banner'],
                ['setting_value' => $other_brand_banner]
            );
        }

        $customize_banner = null;
        if (!empty($request->file('customize_banner'))) {
            $customize_banner = "cutomize" . time() . "." . $request->file('customize_banner')->extension();
            $request
                ->file('customize_banner')
                ->move(base_path('uploads/setting'), $customize_banner);
        } else {
            $customize_banner = $request->customizebannerimage;
        }

        if ($customize_banner) {
            $setting = Setting::updateOrCreate(
                ['setting_slug' => 'customize_banner'],
                ['setting_value' => $customize_banner]
            );
        }
        if ($setting) {
            return redirect()
                ->route('admin.setting.banner')
                ->with('othersuccess', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.setting.banner')
                ->with('othererror', 'Something went wrong');
        }
    }

    public function saveBannerSetting(Request $request)
    {

        if ($request->hasFile('file')) {
            $i = 1;
            foreach ($request->file('file') as $file) {
                $banner = new Banner();
                $image = time() . $i++ . '.' . $file->extension();
                $banner->banner = $image;
                $banner->type = 1;
                $banner->save();
                $media = $banner->addMedia($file)->toMediaCollection('images');
            }
        }
        return redirect()->route('admin.setting.banner')->with('successs', 'Data is saved successfully');
    }

    public function saveVendorBannerSetting(Request $request)
    {

        if ($request->hasFile('file')) {
            $i = 1;
            foreach ($request->file('file') as $file) {
                $banner = new Banner();
                $image = time() . $i++ . '.' . $file->extension();
                $banner->banner = $image;
                $banner->type = 2;
                $banner->save();
                $media = $banner->addMedia($file)->toMediaCollection('images');
            }
        }
        return redirect()->route('admin.setting.banner')->with('successs', 'Data is saved successfully');
    }

    public function saveDashboardBannerSetting(Request $request)
    {

        if ($request->hasFile('files')) {
            $i = 1;
            foreach ($request->file('files') as $file) {
                $banner = new Banner();
                $image = time() . $i++ . '.' . $file->extension();
                $banner->banner = $image;
                $banner->type = 0;
                $banner->save();
                $media = $banner->addMedia($file)->toMediaCollection('images');
            }
        }
        return redirect()->route('admin.setting.banner')->with('dashboardsuccess', 'Data is saved successfully');
    }

    public function deleteMedia($postid)
    {
        // Retrieve the model instance associated with the media file
        $banner = Banner::destroy($postid); // Replace `Post` with your actual model class and `1` with the ID of the post
        // Retrieve the media instance to be deleted by its ID
        // $mediaId = 1; // Replace `1` with the ID of the media
        // $media = $product->getMedia('images')->find($mediaId); // Replace `media_collection` with your media collection name
        if ($banner) {
            // Delete the media file, including its storage file
            // $media->delete();
            // $product->destroy();
            $json = [
                'type' => 1,
                'msg' => 'Data is deleted successfully',
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Something went wrong',
            ];
        }
        return response()->json($json);
    }
}
