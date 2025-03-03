<?php

namespace App\Rules;

use App\Models\VendorWithdraw;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class CheckVendorWithDrawRequest implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = VendorWithdraw::where("user_id", Auth::guard('web')->user()->id)
            ->where("vendor_id",    Auth::guard('web')->user()->vendor->id)
            ->where("status", "0")->first();
        if ($response) {
            $fail("You Already made a vendor widthdraw request.");
        }
    }
}
