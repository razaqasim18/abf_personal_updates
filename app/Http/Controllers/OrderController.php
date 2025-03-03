<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\VendorOrder;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\OrderStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $order = Order::where('user_id', Auth::guard('web')->user()->id)->orderBy('orders.id', 'DESC')->get();
        $vendororder = VendorOrder::where('user_id', Auth::guard('web')->user()->id)->orderBy('vendor_orders.id', 'DESC')->get();

        // Combine both collections into one array
        $combinedOrders = $order->merge($vendororder);

        // Optionally, sort the combined collection by a specific attribute (e.g., by created_at)
        $sortedCombinedOrders = $combinedOrders->sortByDesc('created_at')->values();
        return view('user.order.list', ['order' => $sortedCombinedOrders]);
    }

    public function detail($id, $isvendor)
    {
        if ($isvendor) {
            $order = VendorOrder::findOrFail($id);
        } else {
            $order = Order::findOrFail($id);
        }
        return view('user.order.detail', compact('order'));
    }

     public function printPDF($id, $isvendor)
    {
        if ($isvendor) {
            $order = VendorOrder::findOrFail($id);
            $orderDetail = VendorOrder::findOrFail($id)->orderDetail;
            $orderShippingDetail = VendorOrder::findOrFail($id)->orderShippingDetail;
            $orderVendorDetail = VendorOrder::with('vendorDetail.user')->findOrFail($id);
            $ordertype = 'vendor';
        } else {
            $order = Order::findOrFail($id);
            $orderDetail = Order::findOrFail($id)->orderDetail;
            $orderShippingDetail = Order::findOrFail($id)->orderShippingDetail;
            $orderVendorDetail = [];
            $ordertype = 'normal';
        }

        $data = [
            'order' => $order->toArray(),
            'orderDetail' => $orderDetail->toArray(),
            'orderShippingDetail' => $orderShippingDetail->toArray(),
            'orderVendorDetail' => ($orderVendorDetail) ? $orderVendorDetail->toArray() : [],
            'ordertype' =>  $ordertype
        ];
        // return view('vendor.print.pdf', compact('data'));
        $pdf = Pdf::loadView('vendor.print.pdf', $data);
        return $pdf->download($order->order_no . '_print.pdf');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if (\Order::remove($id)) {
            $json = ['type' => 1, 'msg' => 'Product is removed from cart'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function changeStatus($status, $id, $type)
    {
        if ($type) {
            $response = CustomHelper::vendorOrderCancelPolicy($status, $id, $type);
        } else {
            $response = CustomHelper::normalOrderCancelPolicy($status, $id, $type);
        }
        if ($response) {
            $json = ['type' => 1, 'msg' => $response];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }
}
