<?php

namespace App\Rules;

use App\Enums\CustomerState;
use App\Enums\EmployeeState;
use App\Models\Customer;
use App\Models\Employee;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistedEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Customer::where([['email', '=', $value], ['state', '=', CustomerState::ACTIVE]])->exists()) {
            return;
        } else if (Employee::where([['email', '=', $value], ['state', '=', EmployeeState::ACTIVE]])->exists()) {
            return;
        } else {
            $fail('validation.existed_email')->translate();
        }
    }
}
