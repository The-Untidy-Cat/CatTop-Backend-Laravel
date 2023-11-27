<?php

namespace App\Observers;

use App\Models\ProductVariant;

class ProductVariantObserve
{
    public function created(ProductVariant $variant): void
    {
        $variant->sale_price = $variant->calculateSalePrice();
        $variant->save();
    }

    /**
     * Handle the ProductVariant "updated" event.
     */

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
