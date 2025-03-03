<?php

namespace App\Rules;

use App\Models\EpinRequest;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class verifyEPINByPin implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = EpinRequest::where('epin', $value)
        ->where('status', 1)
        ->whereNull('allotted_to_user_id')->first();
        if (!$response) {
            $fail("The {$attribute} is invalid.");
        }

    }
}
