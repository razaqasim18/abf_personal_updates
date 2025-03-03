<?php

namespace App\Rules;

use App\Models\Withdraw;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class CheckWithDrawRequest implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Withdraw::where("user_id", Auth::guard('web')->user()->id)->where("status", "0")->first();
        if ($response) {
            $fail("You Already made a widthdraw request.");
        }
    }
}
