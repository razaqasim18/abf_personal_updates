<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    public function showAdminLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('admin.auth.login');
        }
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        $email = $request->email;
        $password = $request->password;
        $rememberToken = $request->remember;

        // if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password], $rememberToken)) {
            $user = \Auth::guard('admin')->user();
            return redirect()->intended('/admin');
        } else {
            return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'Credentials doesn\'t match.');
        }

    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout(); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $request->wantsJson()
        ? new JsonResponse([], 204)
        : redirect()->route('admin.login.view');
    }

}
