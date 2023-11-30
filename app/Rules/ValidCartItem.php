<?php

namespace App\Rules;

use App\Enums\ProductState;
use App\Enums\ProductVariantState;
use App\Models\ProductVariant;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCartItem implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $variant = ProductVariant::find($value);
        if (!$variant) {
            $fail(__('messages.not_found', ['name' => __('messages.product_variant')]));
            return;
        }
        if ($variant->state !== ProductVariantState::PUBLISHED || $variant->product()->first()->state !== ProductState::PUBLISHED) {
            $fail(__('messages.unavailable'));
            return;
        }
    }
}
