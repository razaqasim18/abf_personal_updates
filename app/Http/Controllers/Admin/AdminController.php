<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Rules\CheckPassword;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }

    public function passwordLoad()
    {
        return view('admin.profile.password');
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => ['required', new CheckPassword],
            'password' => ['required'],
        ]);

        $userresponse = Admin::where("id", Auth::guard('admin')->user()->id)->update(
            [
                "password" => Hash::make($request->password),
            ]
        );

        if ($userresponse) {
            return redirect()
                ->route('admin.password')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.password')
                ->with('error', 'Something went wrong');
        }
    }

    public function profile()
    {
        $admin = Admin::find(Auth::guard('admin')->user()->id);
        $data = [
            'profile' => $admin,
        ];
        return view('admin.profile.detail', $data);
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'image' => 'mimes:jpeg,png,jpg,gif',
        ]);

        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/admin_profile'), $image);
        } else {
            $image = $request->oldimage;
        }
        $userresponse = Admin::where("id", Auth::guard('admin')->user()->id)->update([
            'image' => $image,
        ]);

        if ($userresponse) {
            return redirect()
                ->route('admin.profile')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.profile')
                ->with('error', 'Something went wrong');
        }
    }
}
