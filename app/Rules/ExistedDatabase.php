<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistedDatabase implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $model = app("App\\Models\\$value");
            $model = $model->firstOrFail();
        } catch (\Exception $e) {
            $fail($e->getMessage());
        }

    }
}
