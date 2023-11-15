<?php

namespace App\Models;

use App\Enums\ProductState;
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
        'brand_id',
    ];
    protected $hidden = ["brand_id"];
    protected $with = [
        'brand',
        // // 'variants:name,id,image,sku,standard_price,sale_price,product_id,specifications'
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id')->select(['id', 'name', 'image']);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
        // ->select(['id', 'name', 'image', 'SKU', 'standard_price', 'sale_price', 'specifications', 'product_id']);
    }
    public function validate($data)
    {
        $rules = [
            "name" => "required",
            "slug" => "required",
            "description" => "required",
            "brand_id" => "required",
        ];
        return Validator::make($data, $rules);
    }
    protected $casts = [
        'state' => ProductState::class,
    ];
}
