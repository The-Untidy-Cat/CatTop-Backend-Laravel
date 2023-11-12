<?php

namespace App\Models;

use App\Enums\ProductVariantState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ProductVariant extends Model
{
    use HasFactory;
    protected $table = "product_variants";
    protected $fillable = [
        'SKU',
        'name',
        'slug',
        'image',
        'description',
        'product_id',
        'standard_price',
        'tax_rate',
        'discount',
        'extra_fee',
        'cost_price',
        'specifications',
    ];

    // protected $casts = [
    //     'specifications' => 'array'
    // ];

    protected $casts = [
        'specifications' => 'array',
        'state'=> ProductVariantState::class,
    ];

    // public function setDataAttribute($value)
    // {
    //     $value = json_decode($value, true);

    //     $value['key1'] = 'value1';
    //     $value['key2'] = 'value2';

    //     $this->attributes['data'] = json_encode($value);
    // }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function validator($data)
    {
        $rules = [
            "name" => "required",
            "slug" => "required",
            "description" => "required",
            "product_id" => "required",
            "standard_price" => "required",
            "tax_rate" => "required",
            "discount" => "required",
            "extra_fee" => "required",
            "cost_price" => "required",
            "specifications" => "json",
            "SKU" => "required|unique:product_variants,SKU",
            "image" => "required|url",
            "sepecifications.processors" => ["filled", "json"],
            "sepecifications.processors.name" => ["required_with:sepecifications.processors"],
            "sepecifications.processors.cores" => ["required_with:sepecifications.processors"],
            "sepecifications.processors.threads" => ["required_with:sepecifications.processors"],
            "sepecifications.processors.base_clock" => ["required_with:sepecifications.processors"],
            "sepecifications.processors.turbo_clock" => ["required_with:sepecifications.processors"],
            "sepecifications.processors.cache" => ["required_with:sepecifications.processors"],

        ];
        return Validator::make($data, $rules);
    }

    // public function specs(){
    //     return $this->hasMany(ProductVariantSpecs::class,'product_variant_id','id');
    // }
}
