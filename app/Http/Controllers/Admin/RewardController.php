<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function add()
    {
        return view('admin.reward.referred');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'referred_points' => 'required|integer|min:0',
        ]);

        $setting = Setting::updateOrCreate(
            ['setting_slug' => 'referred_points'],
            ['setting_value' => $request->referred_points]
        );

        if ($setting->save()) {
            return redirect()
                ->route('admin.reward.referred.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.reward.referred.add')
                ->with('error', 'Something went wrong');
        }
    }
}
