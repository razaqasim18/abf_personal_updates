<?php

namespace App\Rules;

use App\Models\EpinRequest;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class verifyEPINsame implements ValidationRule
{
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = EpinRequest::where('epin', $value)->where('email', $this->email)
            ->where('status', 1)
            ->whereNull('allotted_to_user_id')->first();
        if (!$response) {
            $fail("The {$attribute} is not registered with " . $this->email . "");
        }
    }
}
