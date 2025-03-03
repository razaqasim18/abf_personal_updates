<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\VendorRequest;
use App\Models\VendorSubCategory;
use App\Notifications\VendorRequestApprovedNotification;
use App\Notifications\VendorRequestFailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Validated;

use Illuminate\Support\Facades\DB;

class VendorOther extends Controller
{
    public function termsCondtion()
    {
        $response = Setting::where('setting_slug', "vendor_terms_condition")->first();
        return view("admin.vendor.other.terms-condition", compact('response'));
    }

    public function saveTermscondtion(Request $request)
    {
        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'vendor_terms_condition'],
            ['setting_value' => $request->description]
        );
        if ($setting) {
            $action = "success";
            $message = "Data is saved successfully";
        } else {
            $action = "error";
            $message = "Something went wrong";
        }
        return redirect()->route('admin.vendor.other.terms-condition.load')->with($action, $message);
    }

    public function privacyPolicy()
    {
        $response = Setting::where('setting_slug', "vendor_privacy_policy")->first();
        return view("admin.vendor.other.privacy-policy", compact('response'));
    }

    public function savePrivacypolicy(Request $request)
    {
        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'vendor_privacy_policy'],
            ['setting_value' => $request->description]
        );
        if ($setting) {
            $action = "success";
            $message = "Data is saved successfully";
        } else {
            $action = "error";
            $message = "Something went wrong";
        }
        return redirect()->route('admin.vendor.other.privacy-policy.load')->with($action, $message);
    }

    public function notes()
    {
        $response = Setting::where('setting_slug', "vendor_notes")->first();
        return view("admin.vendor.other.notes", compact('response'));
    }

    public function saveNotes(Request $request)
    {
        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'vendor_notes'],
            ['setting_value' => $request->description]
        );
        if ($setting) {
            $action = "success";
            $message = "Data is saved successfully";
        } else {
            $action = "error";
            $message = "Something went wrong";
        }
        return redirect()->route('admin.vendor.other.notes.load')->with($action, $message);
    }

    public function category()
    {
        $category = VendorCategory::all();
        return view("admin.vendor.other.category", compact('category'));
    }

    public function saveCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category' => 'required|unique:vendor_categories',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'type' => 0,
                'validator_error' => 1,
                'errors' => $validator->errors(),
            ]);
        }

        if ($request->categoryid != "") {
            $category = VendorCategory::findorFail($request->categoryid);
            $category->category = $request->category;
        } else {
            $category = new VendorCategory;
            $category->category = $request->category;
        }
        $response = $category->save();

        if ($response) {
            $json = ['type' => 1, 'msg' => 'Data is saved successfully'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function deleteCategory($id)
    {
        $category = VendorCategory::findorFail($id);
        $response = $category->delete();
        if ($response) {
            $json = ['type' => 1, 'msg' => 'Data is saved successfully'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }


    public function subCategory()
    {
        $category = VendorCategory::all();
        $subcategory = VendorSubCategory::all();
        return view("admin.vendor.other.sub-category", compact('category', 'subcategory'));
    }

    public function saveSubcategory(Request $request)
    {

        if ($request->categoryid != "") {
            $validation = 'required|unique:vendor_sub_categories,sub_category,' . $request->categoryid;
        } else {
            $validation = 'required|unique:vendor_sub_categories,sub_category';
        }
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category' => $validation,
        ]);
        if ($validator->fails()) {
            return response()->json([
                'type' => 0,
                'validator_error' => 1,
                'errors' => $validator->errors(),
            ]);
        }

        if ($request->categoryid != "") {
            $category = VendorSubCategory::findorFail($request->categoryid);
            $category->vendor_category_id = $request->category_id;
            $category->sub_category = $request->sub_category;
        } else {
            $category = new VendorSubCategory;
            $category->vendor_category_id = $request->category_id;
            $category->sub_category = $request->sub_category;
        }
        $response = $category->save();

        if ($response) {
            $json = ['type' => 1, 'msg' => 'Data is saved successfully'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function deletesubCategory($id)
    {
        $category = VendorSubCategory::findorFail($id);
        $response = $category->delete();
        if ($response) {
            $json = ['type' => 1, 'msg' => 'Data is saved successfully'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }
}
