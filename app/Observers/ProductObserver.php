<?php

namespace App\Observers;

use App\Enums\ProductVariantState;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantSpecs;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $variant = new ProductVariant();
        $variant->product_id = $product->id;
        // $variant->slug = Str::slug($product->name) . "-" . Str::random(6);
        $variant->SKU = Str::slug($product->name);
        $variant->name = $product->name;
        // $variant->price = $product->price;
        $variant->description = $product->description;
        $variant->state = ProductVariantState::DRAFT->value;
        // $variant->specifications = [];
        // foreach ($variant->specificatonsTemplate() as $specification) {
        //     $variant->specifications += [$specification->value => ""];
        // }
        $variant->save();
        // foreach ($variant->specificationsTemplate as $specification) {
        //     $product_variant_specs = new ProductVariantSpecs();
        //     $product_variant_specs->product_variant_id = $product->id;
        //     $product_variant_specs->specs_type = $specification->value;
        //     $product_variant_specs->value = "";
        //     $product_variant_specs->save();
        // }
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
