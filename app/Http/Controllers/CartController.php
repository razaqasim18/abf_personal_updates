<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\SettingHelper;
use App\Models\PointTransaction;
use App\Models\Product;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class CartController extends Controller
{
    public function index()
    {
        return view('cart');
    }

    // old not in use
    public function oldinsert(Request $request)
    {
        if ($request->isvendor) {
            $product = VendorProduct::findorFail($request->productid);
            $carttype = 'vendor';
        } else {
            $product = Product::findorFail($request->productid);
            $carttype = 'normal';
        }

        $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ?
            ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;

        $discount = 0;
        if ($request->discount_coupon) {
            $discount = $price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100);
            $price = $price - ($price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100));
        }
        //
        $item = \Cart::session($carttype)->get($request->productid);

        if ($item) {
            $stock = $request->quantity + $item->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }

            $item = \Cart::session($carttype)->get($request->productid);

            $item->attributes->put('product_points', $product->points * $stock);
            $item->attributes->put('product_weight', $product->weight * $stock);
            $item->attributes->put('product_discount', $discount * $stock);

            $item->attributes->put('product_is_coupon', ($product->is_other == 0) ? 1 : 0);
            $item->attributes->put('product_is_coupon_used', ($request->discount_coupon == 1) ? 1 : 0);
            \Cart::session($carttype)->update($request->productid, $item);

            $response = \Cart::session($carttype)->update($request->productid, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $stock,
                ),
            ));
        } else {
            $stock = $request->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }

            $newProduct = [
                'id' => $product->id,
                'name' => $product->product,
                'quantity' => $request->quantity,
                'price' => $price,
                'attributes' => array(
                    // 'product_discount' => 0,
                    // 'product_price' => $request->quantity * $product->price,
                    'product_from_table' => $carttype,
                    'product_seller_id' => $product->user_id,
                    'product_price' => $request->quantity * $price,
                    'product_points' => $request->quantity * $product->points,
                    'product_weight' => $request->quantity * $product->weight,
                    'product_discount' => $request->quantity * $discount,
                    'product_type' => ($product->is_other == 0 || $product->is_other == 1) ? 0 : 1,
                    'product_image' => ($product->image) ? $product->image : null,
                    'product_is_coupon' => ($product->is_other == 0) ? 1 : 0,
                    'product_is_coupon_used' => ($request->discount_coupon == 1) ? 1 : 0,
                ),
            ];

            $normalCart = \Cart::session('normal')->getContent();
            $vendorCart = \Cart::session('vendor')->getContent();

            // to check if current cart is normal and if vendor cart have products or not
            if ($carttype == 'normal' && count($vendorCart)) {
                $json = ['type' => 0, 'msg' => 'You cannot add products to the normal cart while the vendor cart has products and vice versa'];
                return response()->json($json);
            }

            // to check if current cart is vendor and if normal cart have products or not
            if ($carttype == 'vendor' && count($normalCart)) {
                $json = ['type' => 0, 'msg' => 'You cannot add products to the vendor cart while the normal cart has products and vice versa'];
                return response()->json($json);
            }


            if (count(\Cart::session('discount')->getContent())) {
                $discountdiffProduct = \Cart::session('discount')->getContent()->first(function ($item, $key) use ($newProduct) {
                    return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
                });
                if ($discountdiffProduct) {
                    $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                    return response()->json($json);
                }
            }

            // new code to check same product is added
            // $diffProduct = CartHelper::checkCartType($newProduct);
            $diffProduct = \Cart::session($carttype)->getContent()->first(function ($item, $key) use ($newProduct) {
                return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
            });

            if ($diffProduct) {
                $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                return response()->json($json);
            }

            $response = \Cart::session($carttype)->add($newProduct);
        }

        if ($response) {
            $json = ['type' => 1, 'msg' => 'Product is added into cart'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    // old not in use
    public function oldinsertDiscount(Request $request)
    {
        if (Auth::guard('web')->check()) {
            if (CartHelper::cartDiscountCount(Auth::guard('web')->user()->id)) {
                $json = ['type' => 0, 'msg' => 'Reached Monthly Discount Product Limit'];
                return response()->json($json);
            }

            $point = PointTransaction::select(DB::raw("SUM(point) as count"))
                ->where('user_id', Auth::guard('web')->user()->id)
                ->where('status', 1)
                ->where('is_child', 0)
                ->first();
            $resultpoint = ($point->count) ? $point->count : 0;
            if ($resultpoint < 100) {
                $json = ['type' => 0, 'msg' => 'Total Point should be greater than 100'];
                return response()->json($json);
            }
        } else {
            $json = ['type' => 0, 'msg' => 'Please Login To Add Discount Product'];
            return response()->json($json);
        }

        if ($request->isvendor) {
            $product = VendorProduct::findorFail($request->productid);
            $carttype = 'vendor';
        } else {
            $product = Product::findorFail($request->productid);
            $carttype = 'normal';
        }

        $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ?
            ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;
        $price = $price - ($product->price * ($product->discount / 100));
        //
        $countDiscountCart = \Cart::session('discount')->getContent()->count();
        if ($countDiscountCart >= 3) {
            $json = ['type' => 0, 'msg' => 'Discount Product is out of limit'];
            return response()->json($json);
        }

        $item = \Cart::session('discount')->get($request->productid);
        if ($item) {
            $stock = $request->quantity + $item->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }

            $item = \Cart::session('discount')->get($request->productid);
            $item->attributes->put('product_points', $item->attributes->product_points * $request->quantity);
            $item->attributes->put('product_weight', $item->attributes->product_weight * $request->quantity);
            \Cart::update($request->productid, $item);

            $response = \Cart::update($request->productid, array(
                'quantity' => array(
                    // 'relative' => false,
                    'value' => $request->quantity,
                ),
            ));
        } else {
            $stock = $request->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }
            $newProduct = [
                'id' => $product->id,
                'name' => $product->product,
                'quantity' => $request->quantity,
                'price' => $price,
                'attributes' => array(
                    'product_from_table' =>  $carttype, //for product is from abf or vendor
                    'product_discount' => 1,
                    // 'product_price' => $request->quantity * $product->price,
                    'product_price' => $request->quantity * $price,
                    'product_points' => 0,
                    'product_weight' => $request->quantity * $product->weight,
                    'product_type' => ($product->is_other == 0 || $product->is_other == 1) ? 0 : 1,
                    'product_image' => ($product->image) ? $product->image : null,
                    'product_is_coupon' => 0,
                    'product_is_coupon_used' => 0,
                ),
                // 'conditions' => $productCondition,
            ];


            $normalCart = \Cart::session('normal')->getContent();
            $vendorCart = \Cart::session('vendor')->getContent();
            // checking if products is customized or not in normal cart
            // to check if current cart is normal and if vendor cart have products or not
            if ($carttype == 'normal' && count($vendorCart)) {
                $json = ['type' => 0, 'msg' => 'You cannot add products to the normal cart while the vendor cart has products and vice versa'];
                return response()->json($json);
            }

            if (count(\Cart::session('normal')->getContent())) {
                $normaldiffProduct = \Cart::session('normal')->getContent()->first(function ($item, $key) use ($newProduct) {
                    return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
                });
                if ($normaldiffProduct) {
                    $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                    return response()->json($json);
                }
            }

            $diffProduct = \Cart::session('discount')->getContent()->first(function ($item, $key) use ($newProduct) {
                return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
            });

            if ($diffProduct) {
                $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                return response()->json($json);
            }

            // checking if products is customized or not in vendor cart
            // to check if current cart is vendor and if normal cart have products or not
            if ($carttype == 'vendor' && count($normalCart)) {
                $json = ['type' => 0, 'msg' => 'You cannot add products to the vendor cart while the normal cart has products and vice versa'];
                return response()->json($json);
            }

            if (count(\Cart::session('vendor')->getContent())) {
                $vendordiffProduct = \Cart::session('vendor')->getContent()->first(function ($item, $key) use ($newProduct) {
                    return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
                });
                if ($vendordiffProduct) {
                    $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                    return response()->json($json);
                }
            }

            $diffProductvendor = \Cart::session('discount')->getContent()->first(function ($item, $key) use ($newProduct) {
                return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
            });

            if ($diffProductvendor) {
                $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                return response()->json($json);
            }

            $response = \Cart::session('discount')->add($newProduct);
        }

        if ($response) {
            $json = ['type' => 1, 'msg' => 'Discount Product is added into cart'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    // old not in use
    public function midnewinsert(Request $request)
    {
        $type = $request->discounted ?  'discount' : 'normal';

        if ($request->isvendor) {
            $product = VendorProduct::findorFail($request->productid);
            $carttype = 'vendor';
        } else {
            $product = Product::findorFail($request->productid);
            $carttype = 'abf';
        }

        $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ?
            ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;

        $discount = 0;
        if ($request->discounted) {
            if (!Auth::guard('web')->check()) {
                $json = ['type' => 0, 'msg' => 'Please Login To Add Discount Product'];
                return response()->json($json);
            }

            if (CartHelper::cartDiscountCount(Auth::guard('web')->user()->id)) {
                $json = ['type' => 0, 'msg' => 'Reached Monthly Discount Product Limit'];
                return response()->json($json);
            }

            $point = PointTransaction::select(DB::raw("SUM(point) as count"))
                ->where('user_id', Auth::guard('web')->user()->id)
                ->where('status', 1)
                ->where('is_child', 0)
                ->first();

            $resultpoint = ($point->count) ? $point->count : 0;
            if ($resultpoint < 100) {
                $json = ['type' => 0, 'msg' => 'Total Point should be greater than 100'];
                return response()->json($json);
            }
            $price = $price - ($product->price * ($product->discount / 100));
        } else {
            if ($request->discount_coupon) {
                $discount = $price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100);
                $price = $price - ($price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100));
            }
        }

        $item = \Cart::session($type)->get($request->productid);

        if (CartHelper::isProductofChecktable($type, $carttype)) {
            $json = ['type' => 0, 'msg' => 'You can not add vendor products with abf product or vice virsa'];
            return response()->json($json);
        }

        if ($item) {

            // checking the stocks
            $stock = $request->quantity + $item->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }

            // updating the cart
            $item = \Cart::session($type)->get($request->productid);
            $item->attributes->put('product_points', $product->points * ($request->discounted) ? $request->quantity : $stock);
            $item->attributes->put('product_weight', $product->product_weight * ($request->discounted) ? $request->quantity : $stock);
            $relative = true;
            $value = $request->quantity;
            if (!$request->discounted) {
                $item->attributes->put('product_discount', $product->product_discount * $stock);
                $item->attributes->put('product_is_coupon', ($product->is_other == 0) ? 1 : 0);
                $item->attributes->put('product_is_coupon_used', ($request->discount_coupon == 1) ? 1 : 0);
            }
            \Cart::session($type)->update($request->productid, $item);
            if (!$request->discounted) {
                $response = \Cart::session('normal')->update($request->productid, array(
                    'quantity' => array(
                        'relative' => false,
                        'value' => $stock,
                    ),
                ));
            } else {
                $response = \Cart::session('discount')->update($request->productid, array(
                    'quantity' => array(
                        'value' => $request->quantity,
                    ),
                ));
            }
        } else {

            $stock = $request->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }


            $product_seller_id = NULL;
            $product_points = $request->quantity * $product->points;
            $product_discount = $request->quantity * $discount;
            $product_type = ($product->is_other == 0 || $product->is_other == 1) ? 0 : 1;
            $product_is_coupon = ($product->is_other == 0) ? 1 : 0;
            $product_is_coupon_used = ($request->discount_coupon == 1) ? 1 : 0;

            if ($request->discounted) {
                $product_discount = $product_is_coupon =  $product_is_coupon_used =  $product_points = 0;
            }

            if ($request->isvendor) {
                $product_seller_id = $product->user_id;
                $product_type =   $product_is_coupon = $product_is_coupon_used = 0;
            }

            $newProduct = [
                'id' => $product->id,
                'name' => $product->product,
                'quantity' => $request->quantity,
                'price' => $price,
                'attributes' => array(
                    'product_isdiscounted' => ($request->discounted) ? 1 : 0,
                    'product_from_table' =>  $carttype, //for product is from abf or vendor
                    'product_seller_id' => $product_seller_id,
                    'product_price' => $request->quantity * $price,
                    'product_points' => $product_points,
                    'product_weight' => $request->quantity * $product->weight,
                    'product_discount' => $product_discount,
                    'product_type' => $product_type,
                    'product_image' => ($product->image) ? $product->image : null,
                    'product_is_coupon' =>  $product_is_coupon,
                    'product_is_coupon_used' => $product_is_coupon_used,
                ),
                'associatedModel' => ($request->isvendor) ? 'VendorProduct' : 'Product',
            ];

            if (CartHelper::isProductofdifftable($type, $newProduct)) {
                $json = ['type' => 0, 'msg' => 'You can not add vendor products with abf product or vice virsa'];
                return response()->json($json);
            }

            if (count(\Cart::session($type)->getContent())) {
                $normaldiffProduct = \Cart::session($type)->getContent()->first(function ($item, $key) use ($newProduct) {
                    return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
                });
                if ($normaldiffProduct) {
                    $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                    return response()->json($json);
                }
            }

            $diffProduct = \Cart::session($type)->getContent()->first(function ($item, $key) use ($newProduct) {
                return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
            });

            if ($diffProduct) {
                $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                return response()->json($json);
            }

            $response = \Cart::session($type)->add($newProduct);
        }

        if ($response) {
            $json = ['type' => 1, 'msg' => 'Product is added into cart'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    // new one function both for normal abd vendor both have discount in its cart
    public function insert(Request $request)
    {
        // check if the product is from abf or vendor
        if ($request->isvendor) {
            $product = VendorProduct::findorFail($request->productid);
            $cartype = "vendor";
        } else {
            $product = Product::findorFail($request->productid);
            $cartype = "normal";
        }

        $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ?
            ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;

        $discount = 0;
        if ($request->discount_coupon) {
            $discount = $price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100);
            $price = $price - $discount;
        }

        // vendor product price if discount active
        if ($cartype == "vendor") {
            $discount = $price * ($product->discount  / 100);
            $price = $price - $discount;
        }

        // check if product is from discout or not
        $item = \Cart::session($cartype)->get($request->productid);
        $productid = $product->id;
        $product_seller_discounted = 0;
        $product_points = $request->quantity * $product->points;
        $product_is_coupon = ($product->is_other == 0) ? 1 : 0;
        $product_is_coupon_used = ($request->discount_coupon == 1) ? 1 : 0;

        // if product is discounted product or not
        if ($request->discounted) {

            // check if user is login or not
            if (!Auth::guard('web')->check()) {
                $json = ['type' => 0, 'msg' => 'Please Login To Add Discount Product'];
                return response()->json($json);
            }

            // check if current cart have more than three discounted products
            if (CartHelper::countDiscountProductsinCart('normal') >= 3) {
                $json = ['type' => 0, 'msg' => 'Discount Product is out of limit'];
                return response()->json($json);
            }

            // check if monthly limit is reached
            if (CartHelper::cartDiscountCount(Auth::guard('web')->user()->id)) {
                $json = ['type' => 0, 'msg' => 'Reached Monthly Discount Product Limit'];
                return response()->json($json);
            }

            $point = PointTransaction::select(DB::raw("SUM(point) as count"))
                ->where('user_id', Auth::guard('web')->user()->id)
                ->where('status', 1)
                ->where('is_child', 0)
                ->first();
            $resultpoint = ($point->count) ? $point->count : 0;
            if ($resultpoint < 100) {
                $json = ['type' => 0, 'msg' => 'Total Point should be greater than 100'];
                return response()->json($json);
            }

            $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ?
                ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;
            $price = $price - ($product->price * ($product->discount / 100));
            $product_seller_discounted = 1;
            $productid = $product->id . "-discount";
            $product_points = 0;
            $product_is_coupon =  0;
            $product_is_coupon_used =  0;
            $item = \Cart::session($cartype)->get($productid);
        }

        if ($item) {
            $stock = $request->quantity + $item->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }

            $item = \Cart::session($cartype)->get($request->productid);

            $item->attributes->put('product_weight', $product->weight * $stock);
            if (!$request->discounted) {
                $item->attributes->put('product_points', $product->points * $stock);
                $item->attributes->put('product_discount', $discount * $stock);
                $item->attributes->put('product_is_coupon', ($product->is_other == 0) ? 1 : 0);
                $item->attributes->put('product_is_coupon_used', ($request->discount_coupon == 1) ? 1 : 0);
            }

            \Cart::session($cartype)->update($request->productid, $item);

            $response = \Cart::session($cartype)->update($request->productid, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $stock,
                ),
            ));
        } else {
            $stock = $request->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }


            $newProduct = [
                'id' => $productid,
                'name' => $product->product,
                'quantity' => $request->quantity,
                'price' => $price,
                'attributes' => array(
                    'product_seller_discounted' =>  $product_seller_discounted,
                    'product_from_table' => $cartype,
                    'product_seller_id' => $product->user_id,
                    'product_price' => $request->quantity * $price,
                    'product_points' => $product_points,
                    'product_weight' => $request->quantity * $product->weight,
                    'product_discount' => $request->quantity * $discount,
                    'product_type' => ($product->is_other == 0 || $product->is_other == 1) ? 0 : 1,
                    'product_image' => ($product->image) ? $product->image : null,
                    'product_is_coupon' => $product_is_coupon,
                    'product_is_coupon_used' => $product_is_coupon_used,
                ),
            ];

            $reversecarttype = ($cartype == 'normal') ? 'vendor' : 'normal';
            $vendorCart = \Cart::session($reversecarttype)->getContent();

            // to check if current cart is normal and if vendor cart have products or not
            if (count($vendorCart)) {
                $json = ['type' => 0, 'msg' => 'You cannot add products to the normal cart while the vendor cart has products and vice versa'];
                return response()->json($json);
            }


            // new code to check same product is added
            $diffProduct = \Cart::session($cartype)->getContent()->first(function ($item, $key) use ($newProduct) {
                return ($item->attributes->get('product_type') != $newProduct['attributes']['product_type']) ? true : false;
            });

            if ($diffProduct) {
                $json = ['type' => 0, 'msg' => 'You can not add customized products with other brand or vice virsa'];
                return response()->json($json);
            }

            $response = \Cart::session($cartype)->add($newProduct);
        }

        if ($response) {
            $json = ['type' => 1, 'msg' => 'Product is added into cart'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }


    public function update(Request $request)
    {
        if (CartHelper::checkVendorCart()) {
            // Get the cart item
            $product = VendorProduct::find($request->productid);
            $item = \Cart::session('vendor')->get($request->productid);
            $stock = $request->quantity + $item->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }

            $item->attributes->put('product_points', $product->points * $request->quantity);
            $item->attributes->put('product_weight', $product->weight * $request->quantity);
            if ($item->attributes->product_discount) {
                $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ? ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;
                $discount = $price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100);
                $item->attributes->put('product_discount', $discount * $request->quantity);
            }
            \Cart::session('vendor')->update($request->productid, $item);

            $response = \Cart::session('vendor')->update($request->productid, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $request->quantity,
                ),
            ));
            $cart = [
                "point" => \Cart::session('vendor')->get($request->productid)->attributes->product_points,
                "totalweight" => \Cart::session('vendor')->get($request->productid)->attributes->product_weight,
                "sumprice" => \Cart::session('vendor')->get($request->productid)->getPriceSum(),
            ];
        } else {
            // Get the cart item
            $product = Product::find($request->productid);
            $item = \Cart::session('normal')->get($request->productid);
            $stock = $request->quantity + $item->quantity;
            if ($product->stock < $stock) {
                $json = ['type' => 0, 'msg' => 'Cart is out of stock'];
                return response()->json($json);
            }

            $item->attributes->put('product_points', $product->points * $request->quantity);
            $item->attributes->put('product_weight', $product->weight * $request->quantity);
            if ($item->attributes->product_discount) {
                $price = (SettingHelper::getSettingValueBySLug('gst_charges')) ? ceil($product->price + $product->price / SettingHelper::getSettingValueBySLug('gst_charges')) : $product->price;
                $discount = $price * (SettingHelper::getSettingValueBySLug('coupon_discount') / 100);
                $item->attributes->put('product_discount', $discount * $request->quantity);
            }
            \Cart::session('normal')->update($request->productid, $item);

            $response = \Cart::session('normal')->update($request->productid, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $request->quantity,
                ),
            ));
            $cart = [
                "point" => \Cart::session('normal')->get($request->productid)->attributes->product_points,
                "totalweight" => \Cart::session('normal')->get($request->productid)->attributes->product_weight,
                "sumprice" => \Cart::session('normal')->get($request->productid)->getPriceSum(),
            ];
        }
        if ($response) {

            $json = ['type' => 1, 'cart' => $cart];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $id = $request->productid;
        $isdiscount = $request->isdiscount;
        $isvendor = $request->isvendor;
        if ($isvendor) {
            $carttype = "vendor";
        } else {
            $carttype = "normal";
        }
        $response =  \Cart::session($carttype)->remove($id);

        if ($response) {
            $json = ['type' => 1, 'msg' => 'Product is removed from cart'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function ajaxList()
    {

        $object['list_normal'] = \Cart::session('normal')->getContent();
        $object['list_discount'] = \Cart::session('discount')->getContent();
        $object['list_vendor'] = \Cart::session('vendor')->getContent();
        $object['count'] = \Cart::session('normal')->getContent()->count() + \Cart::session('discount')->getContent()->count() + \Cart::session('vendor')->getContent()->count();
        $object['subtotal'] = \Cart::session('normal')->getSubTotal() + \Cart::session('discount')->getSubTotal() + \Cart::session('vendor')->getSubTotal();
        return response()->json($object);
    }

    public function discount($val)
    {
        if ($val && SettingHelper::getSettingValueBySLug('coupon_discount') > 0) {
            Session::put('coupon_discount', SettingHelper::getSettingValueBySLug('coupon_discount'));
            $response = ['type' => 0, 'msg' => "Coupon discount is applied"];
        } else {
            Session::forget('coupon_discount');
            $response = ['type' => 0, 'msg' => "Coupon discount is removed"];
        }
        return response()->json($response);
    }
}
