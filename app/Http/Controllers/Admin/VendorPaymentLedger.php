<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccountDetail;
use App\Models\Vendor;
use App\Models\VendorPaymentLedger as ModelsVendorPaymentLedger;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorPaymentLedger extends Controller
{
    public function index()
    {
        $paymentledger = ModelsVendorPaymentLedger::all();
        return view('admin.vendor.payment_ledger.index', [
            'paymentledger' => $paymentledger
        ]);
    }

    public function add()
    {
        $user = User::where('is_vendor', '1')->get();
        return view('admin.vendor.payment_ledger.add', [
            'user' => $user
        ]);
    }

    public function getVendorPaymentInformationWithoutstandingAmount($userid)
    {
        $user = UserAccountDetail::select('*', 'banks.name AS bankName', 'user_account_details.account_holder_name AS userAccountHolderName', 'user_account_details.account_number AS userAccountNumber', 'user_account_details.account_iban AS useraccountIBAN')
            ->join('banks', 'banks.id', '=', 'user_account_details.bank_id')->where('user_id', $userid)->first();
        $vendor =  Vendor::where('user_id', $userid)->first();

        if ($user) {
            $json = [
                'type' => 1,
                'object' => [
                    "user" => $user,
                    "vendor" => ($vendor) ? $vendor->outstanding_amount : 0,
                ],
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Not payment information found',
            ];
        }
        return response()->json($json);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'amount' => 'required|min:1',
            'image' => 'mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $vendor = Vendor::where('user_id', $request->input('user'))->first();
        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/payment_ledger_proof'), $image);
        }
        DB::beginTransaction();
        $paymentledger = new ModelsVendorPaymentLedger();
        $paymentledger->vendor_id = $vendor->id;
        $paymentledger->user_id = $request->input('user');
        $paymentledger->amount = $request->input('amount');
        $paymentledger->proof = $image;
        $responsepay = $paymentledger->save();

        $vendor->outstanding_amount = $vendor->outstanding_amount - $paymentledger->amount;
        $responsevend = $vendor->save();
        if ($responsepay && $responsevend) {
            DB::commit();
            return redirect()
                ->route('admin.vendor.paymentledger.add')
                ->with('success', 'Data is saved successfully');
        } else {
            DB::rollback();
            return redirect()
                ->route('admin.vendor.paymentledger.add')
                ->with('error', 'Something went wrong');
        }
    }

    public function detail($id)
    {
        $paymentledger = ModelsVendorPaymentLedger::findOrFail($id);
        return view('admin.vendor.payment_ledger.detail', [
            'paymentledger' => $paymentledger
        ]);
    }
}
