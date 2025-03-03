<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $response = Blog::orderBy('id', 'DESC')->get();
        return view('admin.blog.list', ['blogs' => $response]);
    }

    public function add()
    {
        return view('admin.blog.add');
    }

    public function insert(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'image' => ['mimes:jpg,jpeg,png'],
        ]);

        // $name = $request->file('image')->getClientOriginalName();

        // $path = $request->file('image')->store('public/uploads');

        $fileName = time() . '.' . $request->file('image')->extension();
        if ($request->file('image')->move(base_path('uploads/blog'), $fileName)) {

            $response = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $fileName,
            ]);
            if ($response) {
                return redirect()->route('admin.blog.add')->with('success', 'Data is saved succesfully');
            } else {
                return back()->withInput()->with('error', 'Something went wrong');
            }
        } else {
            return back()->withInput()->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        return view('admin.blog.edit', ['blog' => Blog::findorfail($id)]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'image' => ['mimes:jpg,jpeg,png'],
        ]);
        $fileName = '';

        if (!empty($request->file('image'))) {
            $fileName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(base_path('uploads/blog'), $fileName);
        } else {
            // $name = $request->file('image')->getClientOriginalName();
            // $path = $request->file('image')->store('public/uploads');
            $fileName = $request->showimage;
        }

        if (!empty($fileName)) {
            $response = Blog::findOrFail($id)->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $fileName,
            ]);
            if ($response) {
                return redirect()->route('admin.blog.edit', $id)->with('success', 'Data is updated succesfully');
            } else {
                return back()->withInput()->with('error', 'Something went wrong');
            }
        } else {
            return back()->withInput()->with('error', 'Something went wrong');
        }

    }

    public function delete($id)
    {
        $response = Blog::findOrFail($id)->delete();
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
