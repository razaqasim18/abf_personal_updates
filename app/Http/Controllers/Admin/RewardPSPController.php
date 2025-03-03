<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PSPReward;
use Illuminate\Http\Request;

class RewardPSPController extends Controller
{
    public function index()
    {
        $psp = PSPReward::all();
        return view('admin.reward.psp_list', compact('psp'));
    }

    public function add()
    {
        return view('admin.reward.psp_add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:team_rewards',
            'points' => 'required',
            'reward' => 'required',
        ]);

        $psp = new PSPReward();
        $psp->title = $request->title;
        $psp->points = $request->points;
        $psp->reward = $request->reward;

        if ($psp->save()) {
            return redirect()
                ->route('admin.reward.psp.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.reward.psp.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $psp = PSPReward::findorFail($id);
        return view('admin.reward.psp_edit', compact('psp'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'points' => 'required',
            'reward' => 'required',
        ]);

        $psp = PSPReward::findorFail($id);
        $psp->title = $request->title;
        $psp->points = $request->points;
        $psp->reward = $request->reward;
        if ($psp->save()) {
            return redirect()
                ->route('admin.reward.psp.edit', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.reward.psp.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = PSPReward::destroy($id);
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
}
