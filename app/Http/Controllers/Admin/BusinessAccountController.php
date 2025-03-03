<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BusinessAccount;
use Illuminate\Http\Request;

class BusinessAccountController extends Controller
{
    public function index()
    {
        $busienssaccount = BusinessAccount::select('*', 'business_accounts.id AS id')->join('banks', 'business_accounts.bank_id', '=', 'banks.id')->get();
        return view('admin.business_account.list', ['busienssaccount' => $busienssaccount]);
    }

    public function add()
    {
        $bank = Bank::all();
        return view('admin.business_account.add', ['bank' => $bank]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'bank' => 'required',
            'account_number' => 'required|unique:business_accounts',
            'account_iban' => 'required|unique:business_accounts',
        ]);
        $busienssaccount = new BusinessAccount;
        $busienssaccount->bank_id = $request->bank;
        $busienssaccount->account_holder_name = $request->account_holder_name;
        $busienssaccount->account_number = $request->account_number;
        $busienssaccount->account_iban = $request->account_iban;
        $busienssaccount->is_active = ($request->is_active == '1') ? 1 : 0;
        if ($busienssaccount->save()) {
            return redirect()
                ->route('admin.business.account.add')
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.business.account.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function edit($id)
    {
        $bank = Bank::all();
        $busienssaccount = BusinessAccount::findorFail($id);
        return view('admin.business_account.edit', ['bank' => $bank, 'busienssaccount' => $busienssaccount]);
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'bank' => 'required',
            'account_holder_name' => 'required',
            'account_number' => 'required',
            'account_iban' => 'required',
        ]);
        $busienssaccount = BusinessAccount::findOrFail($id);
        $busienssaccount->bank_id = $request->bank;
        $busienssaccount->account_holder_name = $request->account_holder_name;
        $busienssaccount->account_number = $request->account_number;
        $busienssaccount->account_iban = $request->account_iban;
        $busienssaccount->is_active = ($request->is_active == '1') ? 1 : 0;
        if ($busienssaccount->save()) {
            return redirect()
                ->route('admin.business.account.edit', $id)
                ->with('success', 'Data is saved successfully');
        } else {
            return redirect()
                ->route('admin.business.account.edit', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function delete($id)
    {
        $response = BusinessAccount::destroy($id);
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
