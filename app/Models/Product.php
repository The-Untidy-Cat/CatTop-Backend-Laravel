<?php

namespace App\Models;

use App\Enums\ProductState;
use App\Enums\ProductVariantState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'brand_id',
        "state"
    ];
    protected $hidden = ["brand_id"];
    // protected $with = [
    //     'brand:id,name,image',
    //     // 'variants:name,id,image,sku,standard_price,sale_price,product_id,specifications'
    // ];
    protected $appends = ['variant_count', 'discount', 'sale_price', 'standard_price'];
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    public function getVariantCountAttribute()
    {
        return $this->variants()->count();
    }

    public function getMinSalePriceOfVariants()
    {
        $variants = $this->variants()->get();
        $minVariant = $variants[0];
        foreach ($variants as $variant) {
            if ($variant->sale_price < $minVariant->sale_price && $variant->state == ProductVariantState::PUBLISHED) {
                $minVariant = $variant;
            }
        }
        return $minVariant;
    }

    public function getStandardPriceAttribute()
    {
        return $this->variants()->min('standard_price');
    }

    public function getSalePriceAttribute()
    {
        return $this->getMinSalePriceOfVariants()->sale_price;
    }

    public function getDiscountAttribute()
    {
        return $this->getMinSalePriceOfVariants()->discount;
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
    public function validate($data)
    {
        $rules = [
            "name" => "required|unique:products,name",
            "slug" => "required|unique:products,slug",
            "brand_id" => "required",
        ];
        return Validator::make($data, $rules);
    }
    protected $casts = [
        'state' => ProductState::class,
    ];
}
