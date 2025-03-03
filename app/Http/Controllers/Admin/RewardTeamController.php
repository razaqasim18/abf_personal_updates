<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamReward;
use Illuminate\Http\Request;

class RewardTeamController extends Controller
{
    public function index()
    {
        $team = TeamReward::all();
        return view('admin.reward.team_list', compact('team'));
    }

    public function add()
    {
        return view('admin.reward.team_add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:team_rewards',
            'members' => 'required',
            'reward' => 'required',
        ]);

        $team = new TeamReward();
        $team->title = $request->title;
        $team->members = $request->members;
        $team->reward = $request->reward;

        if ($team->save()) {
            return redirect()
                ->route('admin.reward.team.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.reward.team.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $team = TeamReward::findorFail($id);
        return view('admin.reward.team_edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'members' => 'required',
            'reward' => 'required',
        ]);

        $team = TeamReward::findorFail($id);
        $team->title = $request->title;
        $team->members = $request->members;
        $team->reward = $request->reward;


        if ($team->save()) {
            return redirect()
                ->route('admin.reward.team.edit', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.reward.team.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = TeamReward::destroy($id);
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
