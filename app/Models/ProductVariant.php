<?php

namespace App\Models;

use App\Enums\CPUProperties;
use App\Enums\GPUProperties;
use App\Enums\ProductVariantState;
use App\Enums\RAMProperties;
use App\Enums\ScreenProperties;
use App\Enums\StorageProperties;
use App\Enums\VariantType;
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

    protected $appends = ['sale_price', 'sold'];
    protected $hidden = ['product_id'];

    protected $casts = [
        'specifications' => 'array',
        'state' => ProductVariantState::class,
    ];

    // public function updateSalePrice()
    // {
    //     $this->sale_price = ($this->standard_price * (1 - $this->sale_price) + $this->extra_fee)*(1 + $this->tax_rate);
    // }

    public function calculateSalePrice()
    {
        return ($this->standard_price * (1 - $this->discount) + $this->extra_fee) * (1 + $this->tax_rate);
    }

    public function getSalePriceAttribute()
    {
        return $this->calculateSalePrice();
    }

    public function getSoldAttribute(){
        return $this->hasMany(OrderItem::class, 'variant_id', 'id')->sum('amount');
    }
    public function setSalePriceAttribute($value)
    {
        return $this->calculateSalePrice();
    }
    // public function setDataAttribute($value)
    // {
    //     $value = json_decode($value, true);

    //     $value['key1'] = 'value1';
    //     $value['key2'] = 'value2';

    //     $this->attributes['data'] = json_encode($value);
    // }

    public function variantable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function specificatonsTemplate(): array
    {
        return array_merge(
            CPUProperties::cases(),
            RAMProperties::cases(),
            StorageProperties::cases(),
            ScreenProperties::cases(),
            GPUProperties::cases(),
            [
                VariantType::PORTS,
                VariantType::KEYBOARD,
                VariantType::TOUCHPAD,
                VariantType::WEBCAM,
                VariantType::BATTERY,
                VariantType::WEIGHT,
                VariantType::OS,
                VariantType::WARRANTY
            ],
        );
    }

    public function validate($data)
    {
        $rules = [
            "name" => "required",
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
            // "sepecifications.processors" => ["filled", "json"],
            // "sepecifications.processors.name" => ["required_with:sepecifications.processors"],
            // "sepecifications.processors.cores" => ["required_with:sepecifications.processors"],
            // "sepecifications.processors.threads" => ["required_with:sepecifications.processors"],
            // "sepecifications.processors.base_clock" => ["required_with:sepecifications.processors"],
            // "sepecifications.processors.turbo_clock" => ["required_with:sepecifications.processors"],
            // "sepecifications.processors.cache" => ["required_with:sepecifications.processors"],

        ];
        return Validator::make($data, $rules);
    }

    // public function specs(){
    //     return $this->hasMany(ProductVariantSpecs::class,'product_variant_id','id');
    // }
}
