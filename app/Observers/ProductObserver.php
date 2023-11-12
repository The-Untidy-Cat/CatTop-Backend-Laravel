<?php

namespace App\Observers;

use App\Enums\ProductVariantState;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // $variant = new ProductVariant();
        // $variant->product_id = $product->id;
        // $variant->name = $product->name;
        // $variant->price = $product->price;
        // $variant->description = $product->description;
        // $variant->state = ProductVariantState::DRAFT;
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
