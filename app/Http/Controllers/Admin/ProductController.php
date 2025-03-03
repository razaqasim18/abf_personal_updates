<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::select('*', 'products.id AS pid','products.is_active AS is_active')->join("categories", "categories.id", "=", "products.category_id")->orderBy('products.id', 'DESC')->get();
        return view('admin.product.list', [
            'product' => $product,
        ]);
    }

    public function add()
    {
        $brand = Brand::where('is_active', '1')->get();
        $category = Category::where('is_active', '1')->get();
        return view('admin.product.add', [
            'brand' => $brand,
            'category' => $category,
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'product' => 'required|unique:products',
            'category_id' => 'required',
            'brand_id' => 'required',
            'product' => 'required',
            'price' => 'required',
            'purchase_price' => 'required',
            'stock' => 'required',
            'weight' => 'required',
            'points' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif|max:10240',
        ]);
        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/product'), $image);
        }
        $product = new Product;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->product = $request->product;
        $product->price = $request->price;
        $product->purchase_price = $request->purchase_price;
        $product->description = $request->description;
        $product->points = $request->points;
        $product->stock = $request->stock;
        $product->weight = $request->weight;
        $product->image = $image;
        $product->discount = $request->discount;
        $product->is_discount = ($request->is_discount == '1') ? 1 : 0;
        $product->is_active = ($request->is_active == '1') ? 1 : 0;
        $product->in_stock = ($request->is_stock == '1') ? 1 : 0;
        $product->is_other = ($request->is_other == '1') ? 1 : 0;
        $product->is_feature = ($request->is_feature == '1') ? 1 : 0;
        if ($product->save()) {
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $media = $product->addMedia($file)->toMediaCollection('images');
                }
            }

            // $product->addMultipleMediaFromRequest(['file'])
            //     ->each(function ($fileAdder) {
            //         $fileAdder->toMediaCollection('images');
            //     });

            // $fileAdders = $product->addMultipleMediaFromRequest(['file']);
            // foreach ($fileAdders as $fileAdder) {
            //     $fileAdder->toMediaCollection('images');
            // }

            return redirect()
                ->route('admin.product.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.product.add')
                ->with('error', 'Something went wrong');
        }

    }

    public function edit($id)
    {
        $brand = Brand::where('is_active', '1')->get();
        $category = Category::where('is_active', '1')->get();
        $product = Product::findorFail($id);

        return view('admin.product.edit', [
            'product' => $product,
            'category' => $category,
            'brand' => $brand,
        ]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'product' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'product' => 'required',
            'price' => 'required',
            'purchase_price' => 'required',
            'points' => 'required',
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

        $product = Product::findOrFail($id);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->product = $request->product;
        $product->price = $request->price;
        $product->purchase_price = $request->purchase_price;
        $product->description = $request->description;
        $product->points = $request->points;
        $product->stock = $request->stock;
        $product->weight = $request->weight;
        $product->image = $image;
        $product->discount = $request->discount;
        $product->is_discount = ($request->is_discount == '1') ? 1 : 0;
        $product->is_active = ($request->is_active == '1') ? 1 : 0;
        $product->in_stock = ($request->is_stock == '1') ? 1 : 0;
        $product->is_other = ($request->is_other) ? $request->is_other : 0;
        $product->is_feature = ($request->is_feature == '1') ? 1 : 0;
        if ($product->update()) {
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    $media = $product->addMedia($file)->toMediaCollection('images');
                }
            }

            return redirect()
                ->route('admin.product.edit', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.product.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = Product::destroy($id);
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
        $product = Product::find($postid); // Replace `Post` with your actual model class and `1` with the ID of the post
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
