<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\VendorHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\VendorCategory;
use App\Models\VendorProduct as ModelVendorProduct;
use App\Models\VendorSubCategory;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class VendorProduct extends Controller
{
    public function index()
    {
        $product = ModelVendorProduct::where("user_id", Auth::guard('web')->user()->id)->where("vendor_id", VendorHelper::getVendorID())->orderBy('id','desc')->get();
        return view('user.vendor.product.list', [
            'product' => $product,
        ]);
    }

    public function add()
    {
        $category = VendorCategory::all();
        $subcategory = VendorSubCategory::all();
        return view('user.vendor.product.add', [
            'category' => $category,
            'subcategory' => $subcategory
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'product' => 'required|unique:products',
            'vendor_category_id' => 'required',
            'vendor_sub_category_id' => 'required',
            'product' => 'required',
            'price' => 'required',
            'purchase_price' => 'required',
            'stock' => 'required',
            'weight' => 'required',
            // 'points' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif|max:10240',
        ]);
        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/product'), $image);
        }
        $product = new ModelVendorProduct;
        $product->user_id = Auth::guard('web')->user()->id;
        $product->vendor_id = VendorHelper::getVendorID();
        $product->vendor_category_id = $request->vendor_category_id;
        $product->vendor_sub_category_id = $request->vendor_sub_category_id;
        $product->product = $request->product;
        $product->price = $request->price;
        $product->purchase_price = $request->purchase_price;
        $product->description = $request->description;
        $product->points = ceil(($request->price + 1) / 750);
        $product->stock = $request->stock;
        $product->weight = $request->weight;
        $product->image = $image;
        $product->discount = $request->discount;
        $product->is_discount = ($request->is_discount == '1') ? 1 : 0;
        $product->in_stock = ($request->is_stock == '1') ? 1 : 0;
        if ($product->save()) {
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $media = $product->addMedia($file)->toMediaCollection('images');
                }
            }
            $msg = 'Vendor add new product';
            $detail = 'Product ' . $request->product . ' is submitted for approval';
            $admin = Admin::find(1);
            $type = 4;
            $link = 'admin/vendor/product/detail/' . $product->id;
            $adminnotification = new AdminNotification($msg, $type, $link, $detail,1);
            Notification::send($admin, $adminnotification);
            return redirect()
                ->route('vendor.product.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('vendor.product.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $product = ModelVendorProduct::findorFail($id);
        $category = VendorCategory::all();
        $subcategory = VendorSubCategory::all();
        return view('user.vendor.product.edit', [
            'product' => $product,
            'category' => $category,
            'subcategory' => $subcategory
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'product' => 'required',
            'vendor_category_id' => 'required',
            'vendor_sub_category_id' => 'required',
            'product' => 'required',
            'price' => 'required',
            'purchase_price' => 'required',
            // 'points' => 'required',
            'stock' => 'required',
            'weight' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/product'), $image);
        } else {
            $image = $request->oldimage;
        }
        $product = ModelVendorProduct::findOrFail($id);
        $product->product = $request->product;
        $product->vendor_category_id = $request->vendor_category_id;
        $product->vendor_sub_category_id = $request->vendor_sub_category_id;
        $product->price = $request->price;
        $product->purchase_price = $request->purchase_price;
        $product->description = $request->description;
        $product->points = ceil(($request->price + 1) / 750);
        $product->stock = $request->stock;
        $product->weight = $request->weight;
        $product->image = $image;
        $product->discount = $request->discount;
        $product->is_discount = ($request->is_discount == '1') ? 1 : 0;
        $product->in_stock = ($request->is_stock == '1') ? 1 : 0;
        $oldapproval =  $product->getOriginal('is_approved');
        $notification = 0;
        if ($product->isDirty('vendor_category_id','vendor_sub_category_id','product','price','purchase_price','description','points','weight')) {
            $notification = 1;
            $product->is_active =  "0";
            $product->is_approved =  "0";
        }
    
        if ($product->update()) {
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $media = $product->addMedia($file)->toMediaCollection('images');
                }
            }
            if($notification){
                $msg = 'Vendor updated its product details';
                $detail = ($oldapproval == "1") ? 'Product ' . $request->product . ' has new updated  values' : 'Product ' . $request->product . ' is resubmitted for approval';
                $admin = Admin::find(1);
                $type = 4;
                $link = 'admin/vendor/product/detail/' . $product->id;
                $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
                Notification::send($admin, $adminnotification);
            }
            return redirect()
                ->route('vendor.product.edit', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('vendor.product.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = ModelVendorProduct::destroy($id);
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

    public function deleteMedia($postid, $mediaId)
    {
        // Retrieve the model instance associated with the media file
        $product = ModelVendorProduct::find($postid); // Replace `Post` with your actual model class and `1` with the ID of the post
        // Retrieve the media instance to be deleted by its ID
        // $mediaId = 1; // Replace `1` with the ID of the media
        $media = $product->getMedia('images')->find($mediaId); // Replace `media_collection` with your media collection name
        if ($media) {
            // Delete the media file, including its storage file
            $media->delete();
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
