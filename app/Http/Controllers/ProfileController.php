<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\City;
use App\Models\User;
use App\Models\UserAccountDetail;
use App\Models\UserDetail;
use App\Rules\CheckPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function paymentInformationLoad()
    {
        $data = [
            'bank' => Bank::all(),
            'userpaymentinformation' => UserAccountDetail::where('user_id', Auth::guard('web')->user()->id)->first(),
        ];
        return view('user.profile.payment_information', $data);
    }

    public function paymentInformationUpdate(Request $request)
    {
        $this->validate($request, [
            'bank_id' => 'required',
            'account_holder_name' => 'required',
            'account_number' => 'required',
            'account_iban' => 'required',
        ]);

        $useraccount = UserAccountDetail::updateOrCreate(
            [
                'user_id' => Auth::guard('web')->user()->id,
            ],
            [
                'bank_id' => $request->bank_id,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'account_iban' => $request->account_iban,
            ]
        );
        if ($useraccount) {
            return redirect()
                ->route('payment.information.load')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('payment.information.load')
                ->with('error', 'Something went wrong');
        }
    }

    public function profileLoad()
    {
        $user = User::find(Auth::guard('web')->user()->id);
        $data = [
            'city' => City::all(),
            'profile' => $user,
            'profiledetail' => $user->userdetail,
        ];
        return view('user.profile.detail', $data);
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'cnic' => 'required',
            'dob' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif',
        ]);

        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/user_profile'), $image);
        } else {
            $image = $request->oldimage;
        }
        $userresponse = User::where("id", Auth::guard('web')->user()->id)->update([
            'dob' => $request->dob,
            'cnic' => $request->cnic,
            'image' => $image,
        ]);
        $userdetailresponse = UserDetail::updateOrCreate(
            ['user_id' => Auth::guard('web')->user()->id],
            [
                'city_id' => $request->city_id,
                'address' => $request->address,
                'shipping_address' => $request->shipping_address,
                'street' => $request->street,
            ]
        );
        if ($userresponse && $userdetailresponse) {
            return redirect()
                ->route('profile.load')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('profile.load')
                ->with('error', 'Something went wrong');
        }
    }

    public function passwordLoad()
    {
        return view('user.profile.password');
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => ['required', new CheckPassword],
            'password' => ['required'],
        ]);

        $userresponse = User::where("id", Auth::guard('web')->user()->id)->update(
            [
                "password" => Hash::make($request->password),
            ]
        );

        if ($userresponse) {
            return redirect()
                ->route('password.load')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('password.load')
                ->with('error', 'Something went wrong');
        }
    }
}
