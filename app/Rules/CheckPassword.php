<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = (isset(Auth::guard('web')->user()->id)) ? User::find(Auth::guard('web')->user()->id) : Admin::find(Auth::guard('admin')->user()->id);
        if (!Hash::check($value, $user->password)) {
            $fail("Old password doesn't match.");
        }
    }
}
