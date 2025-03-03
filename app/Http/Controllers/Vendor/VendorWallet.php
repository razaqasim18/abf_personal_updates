<?php

namespace App\Http\Controllers\Vendor;


use App\Http\Controllers\Controller;
use App\Models\VendorWallet as ModelsVendorWallet;
use App\Models\VendorWalletTransaction;
use Illuminate\Support\Facades\Auth;

class VendorWallet extends Controller
{
    public function  index()
    {
        $wallet =  ModelsVendorWallet::where('user_id', Auth::user()->id)
            ->where('vendor_id', Auth::user()->vendor->id)
            ->first();
        $wallettransection =  VendorWalletTransaction::where('user_id', Auth::user()->id)
            ->where('vendor_id', Auth::user()->vendor->id)
            ->get();

        return view('user.vendor.wallet.index', [
            'wallet' => $wallet,
            'wallettransection' => $wallettransection
        ]);
    }
}
