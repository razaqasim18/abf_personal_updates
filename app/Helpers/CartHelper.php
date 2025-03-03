<?php

namespace App\Helpers;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDiscount;
use App\Models\OrderShippingDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorOrder;
use App\Models\VendorOrderDetail;
use App\Models\VendorOrderShippingDetail;
use App\Models\VendorProduct;
use App\Notifications\AdminNotification;
use App\Notifications\VendorOrderNotification;
use Carbon\Carbon;
use Darryldecode\Cart\Cart;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CartHelper
{
    public static function cartDiscountCount($userid)
    {
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $result = OrderDiscount::select(DB::raw("Count(id) as countitem"))->where('user_id', $userid)->whereMonth('created_at', $currentMonth)->first();
        if ($result->countitem >= 3) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkCartType($newProduct)
    {
        $response = false;

        $response;
    }

    public static function checkVendorCart()
    {
        if (count(\Cart::session('vendor')->getContent())) {
            return true;
        } else {
            return false;
        }
    }

    public static function cartFromTable($type, $table)
    {
        $response  = false;
        foreach (\Cart::session($type)->getContent() as $item) {
            if ($item->attributes->product_from_table == $table) {
                $response = true;
                break;
            }
        }
        return $response;
    }

    public static function countDiscountProductsinCart($type)
    {
        $count = 0;
        foreach (\Cart::session($type)->getContent() as $item) {
            if ($item->attributes->get('product_seller_discounted')) {
                $count++;
            }
        }
        return $count;
    }

    public static function isProductofdifftable($type, $newProduct)
    {
        $response  = false;
        $normaldiffProduct = \Cart::session($type)->getContent()->first(function ($item, $key) use ($newProduct) {
            return ($item->attributes->get('product_from_table') != $newProduct['attributes']['product_from_table']) ? true : false;
        });
        if ($normaldiffProduct) {
            $response = true;
        }
        return $response;
    }

    public static function isProductofChecktable($type, $carttype)
    {
        $response  = false;
        $normaldiffProduct = \Cart::getContent()->first(function ($item, $key) use ($carttype) {
            return ($item->attributes->get('product_from_table') != $carttype) ? true : false;
        });
        if ($normaldiffProduct) {
            $response = true;
        }
        return $response;
    }


    public static function createNewOrderno()
    {
        $count = 1;
        do {
            $newepin = Str::substr(Str::replace("-", "", Str::uuid()), 0, 12);
            $response = Order::where('order_no', $newepin)->first();
            $count = (!$response) ? 0 : 1;
        } while ($count);
        return $newepin;
    }

    // old function
    public static function normalOrder($request)
    {
        $order_no = self::createNewOrderno();
        DB::beginTransaction();
        $order = new Order();
        $order->order_no = $order_no;
        $order->user_id = Auth::guard('web')->user()->id;
        $order->points = $request->subpoint;
        $order->weight = $request->totalweight;
        $order->subtotal = $request->subtotal;
        $order->shippingcharges = $request->shippingcharges;
        $order->total_bill = $request->totalpay;
        $order->discount = $request->discount;
        $order->payment_by = $request->payment_by;
        $orderresponse = $order->save();

        // normal
        if (
            \Cart::session('normal')
            ->getContent()
            ->count()
        ) {
            foreach (\Cart::session('normal')->getContent() as $item) {
                $orderdetail[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product' => $item->name,
                    'weight' => $item->attributes->product_weight,
                    'quantity' => $item->quantity,
                    'points' => $item->attributes->product_points,
                    'price' => $item->price,
                    'product_type' =>  $item->attributes->product_type,
                    'product_is_coupon' =>  $item->attributes->product_is_coupon,
                    'product_is_coupon_used' =>  $item->attributes->product_is_coupon_used,
                ];
                $product = Product::find($item->id);
                $product->stock = $product->stock - $item->quantity;
                if ($product->stock <= 0) {
                    $product->in_stock = 0;
                }
                $product->save();
            }
        }

        // discount
        if (
            \Cart::session('discount')
            ->getContent()
            ->count()
        ) {
            foreach (\Cart::session('discount')->getContent() as $item) {
                $orderdetail[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product' => $item->name,
                    'weight' => $item->attributes->product_weight,
                    'quantity' => $item->quantity,
                    'points' => $item->attributes->product_points,
                    'price' => $item->price,
                    'product_type' =>  $item->attributes->product_type,
                    'product_is_coupon' =>  $item->attributes->product_is_coupon,
                    'product_is_coupon_used' =>  $item->attributes->product_is_coupon_used,
                ];
                $product = Product::find($item->id);
                $product->stock = $product->stock - $item->quantity;
                if ($product->stock <= 0) {
                    $product->in_stock = 0;
                }
                $product->save();

                $orderdiscount = new OrderDiscount();
                $orderdiscount->user_id = Auth::guard('web')->user()->id;
                $orderdiscount->order_id = $order->id;
                $orderdiscount->product_id = $item->id;
                $orderdiscount->product_from_table = $item->attributes->product_type;
                $orderdiscount->save();
            }
        }

        $orderdetailresponse = OrderDetail::insert($orderdetail);

        $ordershippindetail = new  OrderShippingDetail();
        $ordershippindetail->order_id = $order->id;
        $ordershippindetail->name = $request->name;
        $ordershippindetail->email = $request->email;
        $ordershippindetail->phone = $request->phone;
        $ordershippindetail->address = $request->address;
        $ordershippindetail->shipping_address = $request->shipping_address;
        $ordershippindetail->other_information = $request->other;
        $ordershippindetail->city_id = $request->city;
        $ordershippindetail->street = $request->street;
        $ordershippindetailresponse = $ordershippindetail->save();

        $walletresponse = true;
        if ($request->payment_by == '1') {
            //if user selected payment by wallet
            $walletresponse = CustomHelper::orderWalletTrasection(Auth::guard('web')->user()->id, $request->totalpay);
        }
        if ($request->payment_by == '2') {
            //if user selected payment by gift
            $walletresponse = CustomHelper::orderWalletGiftTrasection(Auth::guard('web')->user()->id, $request->totalpay);
        }
        if ($orderresponse && $orderdetailresponse && $ordershippindetailresponse && $walletresponse) {

            DB::commit();
            \Cart::session('normal')->clear();
            \Cart::session('discount')->clear();
            // Cart::
            $msg = 'New order has been placed';
            $type = 4;
            $link = 'admin/order/detail/' . $order->id;
            $detail = 'New order is placed with order no#' . $order_no;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail);
            Notification::send($admin, $adminnotification);
            Session::forget('coupon_discount');

            return true;
        } else {
            return false;
        }
    }


    // old function
    public static function vendorOrder($request)
    {

        // Get the content of the cart for the 'vendor' session
        $cartContent = \Cart::session('vendor')->getContent();

        // Group the items by the product_seller_id attribute
        $groupedBySeller = $cartContent->groupBy(function ($item) {
            return $item->attributes->product_seller_id;
        });
        //
        DB::beginTransaction();
        try {
            foreach ($groupedBySeller as $sellerId => $items) {
                $price = $weight  = $points = $shipcharges = $discount = 0;
                foreach ($items as $item) {
                    $price = $price + $item->attributes->product_price;
                    $weight = $weight + $item->attributes->product_weight;
                    $points = $points + $item->attributes->product_points;
                    $discount = $discount + $item->attributes->product_discount;

                    if ($item->attributes->product_type) {
                        $charges =  SettingHelper::getSettingValueBySLug('customized_shipping_charges');
                        $shipcharges = ceil($weight) * $charges;
                    } else {
                        $charges = SettingHelper::getSettingValueBySLug('shipping_charges');
                        $shipcharges = ceil($weight) * $charges;
                    }
                }
                $total_bill = ($price + $shipcharges) - $discount;
                $vendor = VendorHelper::getVendorByid($sellerId);
                $comission = ($vendor->is_order_handle_by_admin) ? SettingHelper::getSettingValueBySLug('vendor_order_handle_by_admin_comission')  : SettingHelper::getSettingValueBySLug('vendor_order_commission');
                $commission_amount = 0;
                $vendor_amount = $total_bill;
                if ($comission) {
                    $commission_amount = $total_bill * ($comission / 100);
                    $vendor_amount = ($total_bill - ($total_bill * ($comission / 100)));
                }
                $order_no = self::createNewOrderno();
                $order = new VendorOrder();
                $order->order_no = $order_no;
                $order->user_id = Auth::guard('web')->user()->id;
                $order->seller_id =  $sellerId;
                $order->vendor_id = $vendor->id;
                $order->points =  $points;
                $order->weight = $weight;
                $order->subtotal = $price;
                $order->shippingcharges = $shipcharges;
                $order->total_bill = $total_bill;
                $order->vendor_amount = $vendor_amount;
                $order->commission_amount = $commission_amount;
                $order->commission = $comission;
                $order->is_order_handle_by_admin = $vendor->is_order_handle_by_admin;
                $order->discount = $discount;
                $order->payment_by = $request->payment_by;
                $order->save();

                foreach ($items as $item) {
                    $orderdetail = [
                        'vendor_order_id' => $order->id,
                        'vendor_product_id' => $item->id,
                        'product' => $item->name,
                        'weight' => $item->attributes->product_weight,
                        'quantity' => $item->quantity,
                        'points' => $item->attributes->product_points,
                        'price' => $item->price,
                        // 'product_type' =>  $item->attributes->product_type,
                        // 'product_is_coupon' =>  $item->attributes->product_is_coupon,
                        // 'product_is_coupon_used' =>  $item->attributes->product_is_coupon_used,
                    ];
                    VendorOrderDetail::insert($orderdetail);

                    $product = VendorProduct::find($item->id);
                    $product->stock = $product->stock - $item->quantity;
                    if ($product->stock <= 0) {
                        $product->in_stock = 0;
                    }
                    $product->save();
                }



                $ordershippindetail = new  VendorOrderShippingDetail();
                $ordershippindetail->vendor_order_id = $order->id;
                $ordershippindetail->name = $request->name;
                $ordershippindetail->email = $request->email;
                $ordershippindetail->phone = $request->phone;
                $ordershippindetail->address = $request->address;
                $ordershippindetail->shipping_address = $request->shipping_address;
                $ordershippindetail->other_information = $request->other;
                $ordershippindetail->city_id = $request->city;
                $ordershippindetail->street = $request->street;
                $ordershippindetail->save();

                $msg = 'New order has been placed';
                $type = 4;
                $link = 'admin/vendor/order/detail/' . $order->id;
                $detail = 'New order is placed with order no#' . $order_no;
                $admin = Admin::find(1);
                $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
                Notification::send($admin, $adminnotification);

                $link = 'vendor/order/detail/' . $order->id;
                $user = User::find($sellerId);
                $vendornotification = new VendorOrderNotification($msg, $type, $link, $detail);
                Notification::send($user, $vendornotification);
            }

            if ($request->payment_by == '1') {
                //if user selected payment by wallet
                CustomHelper::orderWalletTrasection(Auth::guard('web')->user()->id, $request->totalpay);
            }
            if ($request->payment_by == '2') {
                //if user selected payment by gift
                CustomHelper::orderWalletGiftTrasection(Auth::guard('web')->user()->id, $request->totalpay);
            }

            DB::commit();
            \Cart::session('vendor')->clear();

            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return false;
        }
    }

    public static function checkOutForCart($type, $request)
    {
        $order_no = self::createNewOrderno();
        DB::beginTransaction();

        if ($type == 'vendor') {
            // Get the content of the cart for the 'vendor' session
            $cartContent = \Cart::session($type)->getContent();

            // Group the items by the product_seller_id attribute
            $groupedBySeller = $cartContent->groupBy(function ($item) {
                return $item->attributes->product_seller_id;
            });


            try {
                foreach ($groupedBySeller as $sellerId => $items) {
                    $price = $weight  = $points = $shipcharges = $discount = $subtotal = 0;
                    $charges = SettingHelper::getVendordeliveryCharges($sellerId);

                    foreach ($items as $item) {
                        $subtotal = $subtotal + $item->getPriceSum();
                        $weight = $weight + $item->attributes->product_weight;
                        $points = $points + $item->attributes->product_points;
                        $discount = $discount + $item->attributes->product_discount;
                        $shipcharges = ceil($weight) * $charges;
                    }
                    $total_bill = ($subtotal + $shipcharges) - $discount;
                    $vendor = VendorHelper::getVendorByid($sellerId);
                    $comission = ($vendor->is_order_handle_by_admin) ? SettingHelper::getSettingValueBySLug('vendor_order_handle_by_admin_comission') + SettingHelper::getSettingValueBySLug('vendor_order_commission') : SettingHelper::getSettingValueBySLug('vendor_order_commission');
                    $commission_amount = 0;
                    $vendor_amount = $total_bill;
                    if ($comission) {
                        $commission_amount = $total_bill * ($comission / 100);
                        $vendor_amount = ($total_bill - ($total_bill * ($comission / 100)));
                    }
                    $order_no = self::createNewOrderno();
                    $order = new VendorOrder();
                    $order->order_no = $order_no;
                    $order->user_id = Auth::guard('web')->user()->id;
                    $order->seller_id =  $sellerId;
                    $order->vendor_id = $vendor->id;
                    $order->points =  $points;
                    $order->weight = $weight;
                    $order->subtotal = $subtotal;
                    $order->shippingcharges = $shipcharges;
                    $order->total_bill = $total_bill;
                    $order->vendor_amount = $vendor_amount;
                    $order->commission_amount = $commission_amount;
                    $order->commission = $comission;
                    $order->is_order_handle_by_admin = $vendor->is_order_handle_by_admin;
                    $order->discount = $discount;
                    $order->payment_by = $request->payment_by;
                    $order->save();

                    foreach ($items as $item) {
                        $itemresult = explode("-discount", $item->id);
                        if (is_array($itemresult)) {
                            $product_id =  $itemresult[0];
                        } else {
                            $product_id = $item->id;
                        }
                        $orderdetail = [
                            'vendor_order_id' => $order->id,
                            'vendor_product_id' =>  $product_id,
                            'product' => $item->name,
                            'weight' => $item->attributes->product_weight,
                            'quantity' => $item->quantity,
                            'points' => $item->attributes->product_points,
                            'price' => $item->price,
                        ];
                        VendorOrderDetail::insert($orderdetail);

                        $product = VendorProduct::find($product_id);
                        $product->stock = $product->stock - $item->quantity;
                        if ($product->stock <= 0) {
                            $product->in_stock = 0;
                        }
                        $product->save();

                        if ($item->attributes->product_seller_discounted) {
                            $orderdiscount = new OrderDiscount();
                            $orderdiscount->user_id = Auth::guard('web')->user()->id;
                            // $orderdiscount->order_id = $order->id;
                            // $orderdiscount->product_id =  $product_id;

                            $orderdiscount->orderable_id = $order->id;
                            $orderdiscount->orderable_type = get_class($order);  // 'App\Models\Order'

                            // Set polymorphic relationship for the product
                            $orderdiscount->productable_id = $product->id;
                            $orderdiscount->productable_type = get_class($product);  // 'App\Models\Product'

                            $orderdiscount->from_table = $item->attributes->product_from_table;
                            $orderdiscount->save();
                        }
                    }

                    $ordershippindetail = new  VendorOrderShippingDetail();
                    $ordershippindetail->vendor_order_id = $order->id;
                    $ordershippindetail->name = $request->name;
                    $ordershippindetail->email = $request->email;
                    $ordershippindetail->phone = $request->phone;
                    $ordershippindetail->address = $request->address;
                    $ordershippindetail->shipping_address = $request->shipping_address;
                    $ordershippindetail->other_information = $request->other;
                    $ordershippindetail->city_id = $request->city;
                    $ordershippindetail->street = $request->street;
                    $ordershippindetail->save();

                    $msg = 'New order has been placed';
                    $type = 4;
                    $link = 'admin/vendor/order/detail/' . $order->id;
                    $detail = 'New order is placed with order no#' . $order_no;
                    $admin = Admin::find(1);
                    $adminnotification = new AdminNotification($msg, $type, $link, $detail, 1);
                    Notification::send($admin, $adminnotification);

                    $link = 'vendor/order/detail/' . $order->id;
                    $user = User::find($sellerId);
                    $vendornotification = new VendorOrderNotification($msg, $type, $link, $detail);
                    Notification::send($user, $vendornotification);
                }

                if ($request->payment_by == '1') {
                    //if user selected payment by wallet
                    CustomHelper::orderWalletTrasection(Auth::guard('web')->user()->id, $request->totalpay);
                }
                if ($request->payment_by == '2') {
                    //if user selected payment by gift
                    CustomHelper::orderWalletGiftTrasection(Auth::guard('web')->user()->id, $request->totalpay);
                }

                DB::commit();
                \Cart::session($type)->clear();

                return true;
            } catch (\Exception $e) {
                dd($e->getMessage());
                return false;
            }
        } else {
            
            $attribute = $weight = $subtotal = $discount = 0;
            foreach (\Cart::session('normal')->getContent() as $item) {
                $attribute = $attribute + $item->attributes->product_points;
                $weight = $weight + $item->attributes->product_weight;
                $discount = $discount + $item->attributes->product_discount;
                $subtotal = $subtotal + $item->getPriceSum();
            }
            
            $normalcustmoizedProduct = $discountcustmoizedProduct = false;
            \Cart::session('normal')
                ->getContent()
                ->each(function ($item, $key) use (&$normalcustmoizedProduct) {
                    if ($item->attributes->get('product_type') == 1) {
                        $normalcustmoizedProduct = true;
                    }
                });

            $charges = $normalcustmoizedProduct
                ? SettingHelper::getSettingValueBySLug('customized_shipping_charges')
                : SettingHelper::getSettingValueBySLug('shipping_charges');
            $shippingcharges = ceil($weight) * $charges;
           
            $totalpay = $subtotal + $shippingcharges;
            
            $order = new Order();
            $order->order_no = $order_no;
            $order->user_id = Auth::guard('web')->user()->id;
            $order->points = $attribute;
            $order->weight = $weight;
            $order->subtotal = $subtotal;
            $order->shippingcharges = $shippingcharges;
            $order->total_bill = $totalpay;
            $order->discount = $discount;
            $order->payment_by = $request->payment_by;
            $orderresponse = $order->save();

            // normal
            if (
                \Cart::session($type)
                ->getContent()
                ->count()
            ) {
                foreach (\Cart::session($type)->getContent() as $item) {
                    $itemresult = explode("-discount", $item->id);
                    if (is_array($itemresult)) {
                        $product_id =  $itemresult[0];
                    } else {
                        $product_id = $item->id;
                    }
                    $orderdetail[] = [
                        'order_id' => $order->id,
                        'product_id' => $product_id,
                        'product' => $item->name,
                        'weight' => $item->attributes->product_weight,
                        'quantity' => $item->quantity,
                        'points' => $item->attributes->product_points,
                        'price' => $item->price,
                        'product_type' =>  $item->attributes->product_type,
                        'product_is_coupon' =>  $item->attributes->product_is_coupon,
                        'product_is_coupon_used' =>  $item->attributes->product_is_coupon_used,
                    ];
                    $product = Product::find($product_id);
                    $product->stock = $product->stock - $item->quantity;
                    if ($product->stock <= 0) {
                        $product->in_stock = 0;
                    }
                    $product->save();

                    if ($item->attributes->product_seller_discounted) {
                        $orderdiscount = new OrderDiscount();
                        $orderdiscount->user_id = Auth::guard('web')->user()->id;
                        // $orderdiscount->order_id = $order->id;
                        // $orderdiscount->product_id =  $product_id;

                        // $orderdiscount->order_id = $order->id;
                        // $orderdiscount->product_id =  $product_id;

                        $orderdiscount->orderable_id = $order->id;
                        $orderdiscount->orderable_type = get_class($order);  // 'App\Models\Order'

                        // Set polymorphic relationship for the product
                        $orderdiscount->productable_id = $product->id;
                        $orderdiscount->productable_type = get_class($product);  // 'App\Models\Product'

                        // $orderdiscount->from_table = $item->attributes->product_from_table;
                        $orderdiscount->save();
                    }
                }
            }

            $orderdetailresponse = OrderDetail::insert($orderdetail);

            $ordershippindetail = new  OrderShippingDetail();
            $ordershippindetail->order_id = $order->id;
            $ordershippindetail->name = $request->name;
            $ordershippindetail->email = $request->email;
            $ordershippindetail->phone = $request->phone;
            $ordershippindetail->address = $request->address;
            $ordershippindetail->shipping_address = $request->shipping_address;
            $ordershippindetail->other_information = $request->other;
            $ordershippindetail->city_id = $request->city;
            $ordershippindetail->street = $request->street;
            $ordershippindetailresponse = $ordershippindetail->save();

            $walletresponse = true;
            if ($request->payment_by == '1') {
                //if user selected payment by wallet
                $walletresponse = CustomHelper::orderWalletTrasection(Auth::guard('web')->user()->id, $request->totalpay);
            }
            if ($request->payment_by == '2') {
                //if user selected payment by gift
                $walletresponse = CustomHelper::orderWalletGiftTrasection(Auth::guard('web')->user()->id, $request->totalpay);
            }
            if ($orderresponse && $orderdetailresponse && $ordershippindetailresponse && $walletresponse) {

                DB::commit();
                \Cart::session($type)->clear();
                // Cart::
                $msg = 'New order has been placed';
                $type = 4;
                $link = 'admin/order/detail/' . $order->id;
                $detail = 'New order is placed with order no#' . $order_no;
                $admin = Admin::find(1);
                $adminnotification = new AdminNotification($msg, $type, $link, $detail);
                Notification::send($admin, $adminnotification);
                Session::forget('coupon_discount');
                return true;
            } else {
                return false;
            }
        }
    }
}
