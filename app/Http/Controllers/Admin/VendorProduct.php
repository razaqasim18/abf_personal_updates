<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorProduct as ModelsVendorProduct;
use App\Models\VendorCategory;
use App\Models\VendorSubCategory;
use Illuminate\Http\Request;

use App\Notifications\VendorRequestApprovedNotification;
use App\Notifications\VendorRequestFailNotification;

class VendorProduct extends Controller
{
    public function index()
    {
        $product = ModelsVendorProduct::orderBy('id', 'desc')->get();
        return view('admin.vendor.product.list', compact('product'));
    }

    public function detail($id)
    {
        $category = VendorCategory::all();
        $subcategory = VendorSubCategory::all();
        $product = ModelsVendorProduct::findOrFail($id);
        return view('admin.vendor.product.detail', compact('product','category','subcategory'));
    }

    public function delete($id)
    {
        $vendor = ModelsVendorProduct::findOrFail($id);
        $response = $vendor->delete();
        if ($response) {
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

    public function status(Request $request)
    {
        $product = ModelsVendorProduct::findOrFail($request->id);
        $user = User::findOrFail($product->user_id);

        $status = $request->status;
        if ($status == "1") {
            $product->is_approved = 1;
            $product->is_active = 1;
            $product->remarks = "";
            $msgshow = "Product is approved successfully";
            $subject = "Vendor product is approved";
            $mailmsg =  "Your product " . $product->product . " is approved.<br />Congratulations.<br />";
            $link = "";
            $linktext = "";

            $msgshow = "Product is approved successfully";
            $user->notify(new VendorRequestApprovedNotification($subject, $mailmsg, $link, $linktext));
        } else {
            $product->is_approved = "-1";
            $product->remarks = $request->remarks;

            $subject = "Vendor product is disapproved";
            $mailmsg =  "Your product " . $product->product . "is denied.<br /> <b>Admin remarks: <br/>" . $request->remarks . "</b><br/>";
            $msgshow = "Product is rejected";
            $link = "/vendor/product/edit/" . $product->id;
            $linktext = "Go to Product";

            $user->notify(new VendorRequestFailNotification($subject, $mailmsg, $link, $linktext));
        }
        if ($product->save()) {
            $json = [
                'type' => 1,
                'msg' => $msgshow,
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
