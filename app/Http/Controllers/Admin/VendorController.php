<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\VendorRequest;
use App\Notifications\VendorRequestApprovedNotification;
use App\Notifications\VendorRequestFailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{

    public function list()
    {
        $vendor = Vendor::all();
        return view('admin.vendor.list', [
            'vendor' => $vendor
        ]);
    }

    public function delete($id)
    {
        $user = Vendor::findOrFail($id);
        // $user->is_deleted = 1;
        // $response = $user->save();
        $response = $user->delete();
        if ($response) {
            $type = 1;
            $msg = 'User is deleted successfully';
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        return response()->json($result);
    }

    public function status(Request $request)
    {
        $id = $request->id;
        $status  = $request->status;
        $txt = ($status) ? "Vendor is blocked successfully" : "Vendor is unblocked successfully";
        $user = Vendor::findOrFail($id);
        $user->is_blocked = $status;
        $response = $user->save();
        if ($response) {
            $type = 1;
            $msg = $txt;
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        return response()->json($result);
    }

    public function detail($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('admin.vendor.detail', [
            'vendor' => $vendor
        ]);
    }
}
