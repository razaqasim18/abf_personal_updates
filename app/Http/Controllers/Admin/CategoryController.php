<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('id', 'DESC')->get();
        return view('admin.category.list', ['category' => $category]);
    }

    public function add()
    {
        return view('admin.category.add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'category' => 'required|unique:categories',
        ]);

        $category = new Category;
        $category->category = $request->category;
        $category->page_type = $request->page_type;
        $category->is_active = ($request->is_active == '1') ? 1 : 0;
        if ($category->save()) {
            return redirect()
                ->route('admin.category.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.category.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $category = Category::findorFail($id);
        return view('admin.category.edit', ['category' => $category]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
        ]);

        $category = Category::findOrFail($id);
        $category->category = $request->category;
        $category->page_type = $request->page_type;
        $category->is_active = ($request->is_active == '1') ? 1 : 0;
        if ($category->update()) {
            return redirect()
                ->route('admin.category.edit', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.category.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = Category::destroy($id);
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
