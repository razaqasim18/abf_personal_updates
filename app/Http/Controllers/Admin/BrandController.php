<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::orderBy('id', 'DESC')->get();
        return view('admin.brand.list', ['brand' => $brand]);
    }

    public function add()
    {
        return view('admin.brand.add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'brand' => 'required|unique:brands',
        ]);

        $brand = new Brand;
        $brand->brand = $request->brand;
        $brand->is_active = ($request->is_active == '1') ? 1 : 0;
        if ($brand->save()) {
            return redirect()
                ->route('admin.brand.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.brand.add')
                ->with('error', 'Something went wrong');
        }

    }

    public function edit($id)
    {
        $brand = Brand::findorFail($id);
        return view('admin.brand.edit', ['brand' => $brand]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'brand' => 'required',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->brand = $request->brand;
        $brand->is_active = ($request->is_active == '1') ? 1 : 0;
        if ($brand->update()) {
            return redirect()
                ->route('admin.brand.edit', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.brand.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = Brand::destroy($id);
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
