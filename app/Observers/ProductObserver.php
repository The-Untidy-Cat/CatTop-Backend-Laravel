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
        $variant->SKU = Str::slug($product->name).'-'.Str::random(4);
        $variant->name = $product->name;
        $variant->description = $product->description;
        $variant->image = $product->image;
        $variant->state = ProductVariantState::DRAFT->value;
        $variant->specifications = [];
        foreach ($variant->specificatonsTemplate() as $specification => $value) {
            if (gettype($value) == "array") {
                $data = [];
                foreach ($value as $item) {
                    $data += $item;
                }
                $variant->specifications += [$specification => $data];
            } else {
                $variant->specifications += [$specification => ""];
            }
        }
        $variant->save();
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
