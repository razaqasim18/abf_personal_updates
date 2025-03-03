<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuccesStory;
use App\Models\User;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{
    public function index()
    {
        $success = SuccesStory::all();
        return view('admin.success_story.list', compact('success'));
    }

    public function add()
    {
        $user = User::all();
        return view('admin.success_story.add', compact('user'));
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'user' => ['required'],
            'description' => ['required'],
        ]);

        $success = new SuccesStory();
        $success->user_id = $request->user;
        $success->description = $request->description;
        $resposesuccess = $success->save();

        if ($resposesuccess) {
            return redirect()->route('admin.success.story.add')->with('success', "Ticket is created successfully");
        } else {
            return redirect()->route('admin.success.story.add')->with('error', "Something went wrong please try again");
        }

    }

    public function edit($id)
    {
        $user = User::all();
        $story = SuccesStory::findOrFail($id);
        return view('admin.success_story.add', compact('user', 'story'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'user' => ['required'],
            'description' => ['required'],
        ]);

        $success = SuccesStory::findOrFail($id);
        $success->user_id = $request->user;
        $success->description = $request->description;
        $resposesuccess = $success->save();

        if ($resposesuccess) {
            return redirect()->route('admin.success.story.edit', $id)->with('success', "Ticket is created successfully");
        } else {
            return redirect()->route('admin.success.story.add', $id)->with('error', "Something went wrong please try again");
        }

    }

    public function delete($id)
    {
        $response = SuccesStory::destroy($id);
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
