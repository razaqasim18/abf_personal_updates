<?php

namespace App\Rules;

use App\Models\Wallet;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class UserOrderGift implements ValidationRule
{
    public $totalpay;
    public function __construct($totalpay)
    {
        $this->totalpay = $totalpay;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Wallet::where('user_id', Auth::user()->id)->first();
        if ($response == null || $response->gift < $value || $this->totalpay > $value) {
            $fail("You don't have sufficent amount in cashback.");
        }
    }
}
