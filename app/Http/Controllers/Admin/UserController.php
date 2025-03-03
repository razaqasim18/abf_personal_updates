<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\User;
use App\Models\UserLog;
use App\Notifications\VendorNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('is_deleted', 0)->orderBy('id', 'DESC')->get();
        return view('admin.user.list', compact('user'));
    }

    public function log()
    {
        $user = UserLog::select('*', 'user_logs.created_at AS logcreated')->with('user')->orderBy('id', 'DESC')->get();
        return view('admin.user.list_log', compact('user'));
    }

    public function add()
    {
        $user = User::where('is_blocked', 0)->orderBy('id', 'DESC')->get();
        return view('admin.user.add', compact('user'));
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            // 'epin' => ['required'],
            'sponsor' => ['required'],
            'phone' => ['required'],
            'cnic' => ['required'],
            'dob' => ['required'],
            'cnic_image_front' => ['required', 'mimes:jpeg,png,jpg,gif'],
            'cnic_image_back' => ['required', 'mimes:jpeg,png,jpg,gif'],
        ]);
        if (!empty($request->hasFile('cnic_image_front'))) {
            $cnic_image_front = time() . '_front.' . $request->file('cnic_image_front')->extension();
            $request->file('cnic_image_front')->move(base_path('uploads/cnic'), $cnic_image_front);
        }

        if (!empty($request->hasFile('cnic_image_back'))) {
            $cnic_image_back = time() . '_back.' . $request->file('cnic_image_back')->extension();
            $request->file('cnic_image_back')->move(base_path('uploads/cnic'), $cnic_image_back);
        }

        // DB::beginTransaction();

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->cnic = $request->cnic;
        $user->sponserid = $request->sponsor;
        $user->phone = $request->phone;
        $user->dob = $request->dob;

        $user->cnic_image_front = $cnic_image_front;
        $user->cnic_image_back = $cnic_image_back;
        $responseuser = $user->save();
        // $EpinRequest = EpinRequest::where('epin', $request->epin)
        //     ->where('email', $data['email'])
        //     ->where('status', 1)
        //     ->whereNull('allotted_to_user_id')
        //     ->first();
        // $EpinRequest->allotted_to_user_id = $user->id;
        // $responseepin = $EpinRequest->save();
        // if ($responseuser && $responseepin) {
        if ($responseuser) {
            event(new Registered($user));
            // DB::commit();
            return redirect()
                ->route('admin.client.add')
                ->with('success', 'Client added is successfully');
        } else {
            // DB::rollback();
            return redirect()
                ->route('admin.client.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $user = User::where('is_blocked', 0)->where('id', '!=', $id)->orderBy('id', 'DESC')->get();
        $client = User::findorFail($id);
        return view('admin.user.edit', compact('user', 'client'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            // 'password' => ['required', 'string', 'min:8'],
            // 'epin' => ['required'],
            'sponsor' => ['required'],
            'phone' => ['required'],
            'cnic' => ['required'],
            'dob' => ['required'],
            'cnic_image_front' => ['mimes:jpeg,png,jpg,gif'],
            'cnic_image_back' => ['mimes:jpeg,png,jpg,gif'],
        ]);
        if (!empty($request->hasFile('cnic_image_front'))) {
            $cnic_image_front = time() . '_front.' . $request->file('cnic_image_front')->extension();
            $request->file('cnic_image_front')->move(base_path('uploads/cnic'), $cnic_image_front);
        } else {
            $cnic_image_front = $request->cnic_image_front_showimage;
        }

        if (!empty($request->hasFile('cnic_image_back'))) {
            $cnic_image_back = time() . '_back.' . $request->file('cnic_image_back')->extension();
            $request->file('cnic_image_back')->move(base_path('uploads/cnic'), $cnic_image_back);
        } else {
            $cnic_image_back = $request->cnic_image_back_showimage;
        }

        // DB::beginTransaction();

        $user = User::findorFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->cnic = $request->cnic;
        $user->sponserid = $request->sponsor;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->cnic_image_front = $cnic_image_front;
        $user->cnic_image_back = $cnic_image_back;
        $responseuser = $user->save();
        // $EpinRequest = EpinRequest::where('epin', $request->epin)
        //     ->where('email', $data['email'])
        //     ->where('status', 1)
        //     ->whereNull('allotted_to_user_id')
        //     ->first();
        // $EpinRequest->allotted_to_user_id = $user->id;
        // $responseepin = $EpinRequest->save();
        // if ($responseuser && $responseepin) {
        // if ($responseuser) {
        //     event(new Registered($user));
        // DB::commit();
        if ($responseuser) {
            return redirect()
                ->route('admin.client.edit', $id)
                ->with('success', 'Client added is successfully');
        } else {
            // DB::rollback();
            return redirect()
                ->route('admin.client.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
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

    public function block($id, $status)
    {
        $txt = ($status) ? "User is blocked successfully" : "User is unblocked successfully";
        $user = User::findOrFail($id);
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
        $client = User::findorFail($id);
        return view('admin.user.detail', compact('client'));
    }

    public function insertPoint(Request $request)
    {

        $point = Point::updateOrCreate(
            ['user_id' => $request->clientid],
            ['point' => DB::raw('point + ' . $request->points)],
        );
        if ($point) {
            CustomHelper::calculateUserRank($request->clientid);
            $type = 1;
            $msg = "Points is added successfully";
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        return response()->json($result);
    }

    public function allowVendor($id)
    {
        $user = User::findorFail($id);
        $user->is_vendor_allowed = '1';
        if ($user->save()) {
            $msg =  "You are ready for the next step.<br />
            Now you can become a vendor.<br />
            Please click the link below to proceed.";
            $user->notify(new VendorNotification($msg));
            $json = ['type' => 1, 'msg' => 'User is allowed to become for vendor'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }
}
