<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $bank = Bank::orderBy('id', 'DESC')->get();
        return view('admin.bank.list', ['bank' => $bank]);
    }

    public function add()
    {
        return view('admin.bank.add');
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:banks',
        ]);
        $bank = new Bank;
        $bank->name = $request->name;
        $bank->slug = trim(str_replace(' ', '_', strtolower($request->name)));
        if ($bank->save()) {
            return redirect()
                ->route('admin.bank.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.bank.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $bank = Bank::findorFail($id);
        return view('admin.bank.edit', ['bank' => $bank]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $bank = Bank::findOrFail($id);
        $bank->name = $request->name;
        $bank->slug = trim(str_replace(' ', '_', strtolower($request->name)));
        if ($bank->save()) {
            return redirect()
                ->route('admin.bank.edit', $id)
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.bank.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = Bank::destroy($id);
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
