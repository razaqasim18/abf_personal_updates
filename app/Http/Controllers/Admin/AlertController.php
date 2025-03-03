<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $response = Alert::orderBy('id', 'DESC')->get();
        return view('admin.alert.list', ['alerts' => $response]);
    }

    public function add()
    {
        return view('admin.alert.add');
    }

    public function insert(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);


        $response = Alert::create([
            'title' => str_replace(' ', '_', $request->title),
            'description' => $request->description,
            'is_active' => ($request->is_active) ? 1 : 0
        ]);
        if ($response) {
            return redirect()->route('admin.alert.add')->with('success', 'Data is saved succesfully');
        } else {
            return back()->withInput()->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        return view('admin.alert.edit', ['alert' => alert::findorfail($id)]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
        ]);
        $response = Alert::findOrFail($id)->update([
            'title' => str_replace(' ', '_', $request->title),
            'description' => $request->description,
            'is_active' => ($request->is_active) ? 1 : 0
        ]);
        if ($response) {
            return redirect()->route('admin.alert.edit', $id)->with('success', 'Data is updated succesfully');
        } else {
            return back()->withInput()->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = Alert::findOrFail($id)->delete();
        if ($response) {
            $type = 1;
            $msg = 'Data is deleted successfully';
        } else {
            $type = 0;
            $msg = 'Something went wrong';
        }
        $result = ['type' => $type, 'msg' => $msg];
        echo json_encode($result);
        exit;
    }
}
