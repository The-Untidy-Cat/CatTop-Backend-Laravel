<?php

namespace App\Observers;
use App\Models\ProductVariant;

class ProductVariantObserve
{
    public function created(ProductVariant $variant): void
    {
        // $variant->sell_price = ($variant->standard_price * (1 - $variant->sale_price) + $variant->extra_fee)*(1 + $variant->tax_rate);
        // $variant->updateSalePrice();
        $variant->specifications = [];
        foreach ($variant->specificatonsTemplate() as $specification) {
            $variant->specifications += [$specification->value => ""];
        }
        $variant->save();
        // foreach ($variant->specificationsTemplate as $specification) {
        //     $variant_variant_specs = new ProductVariantVariantSpecs();
        //     $variant_variant_specs->product_variant_id = $variant->id;
        //     $variant_variant_specs->specs_type = $specification->value;
        //     $variant_variant_specs->value = "";
        //     $variant_variant_specs->save();
        // }
    }

    /**
     * Handle the ProductVariant "updated" event.
     */
    public function updated(ProductVariant $variant): void
    {
        // $variant->updateSalePrice();
    }

    /**
     * Handle the ProductVariant "deleted" event.
     */
    public function deleted(ProductVariant $variant): void
    {
        //
    }

    /**
     * Handle the ProductVariant "restored" event.
     */
    public function restored(ProductVariant $variant): void
    {
        //
    }

    /**
     * Handle the ProductVariant "force deleted" event.
     */
    public function forceDeleted(ProductVariant $variant): void
    {
        //
    }
}
