<?php

namespace App\Rules;

use App\Models\VendorWallet;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class VendorAmount implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = VendorWallet::where('user_id', Auth::user()->id)->first();
        if ($response == null || $response->amount < $value) {
            $fail("You don't have sufficent amount in vendor wallet.");
        }
    }
}
