<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Username implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = "/^\S[a-zA-Z0-9.]*$/u";
        if(preg_match($pattern, $value ) == false || strlen($value) < 6) {
            $fail("validation.username")->translate();
        }
    }
}
