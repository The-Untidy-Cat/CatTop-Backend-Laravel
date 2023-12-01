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
        'image',
        'description',
        'product_id',
        'standard_price',
        'tax_rate',
        'discount',
        'extra_fee',
        'cost_price',
        'specifications',
        'state',
        'sale_price',
    ];

    protected $appends = ['sold'];
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
        return round(($this->standard_price * (1 - $this->discount) + $this->extra_fee) * (1 + $this->tax_rate));
    }

    public function getSoldAttribute()
    {
        return $this->hasMany(OrderItem::class, 'variant_id', 'id')->sum('amount');
    }

    // public function variantable()
    // {
    //     return $this->morphTo();
    // }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(OrderItem::class, 'variant_id', 'id');
    }

    public function specificatonsTemplate(): array
    {
        $cpu = [];
        foreach (CPUProperties::cases() as $item) {
            $cpu[] = [$item->value => ""];
        }
        $ram = [];
        foreach (RAMProperties::cases() as $item) {
            $ram[] = [$item->value => ""];
        }
        $storage = [];
        foreach (StorageProperties::cases() as $item) {
            $storage[] = [$item->value => ""];
        }
        $screen = [];
        foreach (ScreenProperties::cases() as $item) {
            $screen[] = [$item->value => ""];
        }
        $gpu = [];
        foreach (GPUProperties::cases() as $item) {
            $gpu[] = [$item->value => ""];
        }
        return array_merge(
            [VariantType::PROCESSOR->value => $cpu],
            [VariantType::RAM->value => $ram],
            [VariantType::STORAGE->value => $storage],
            [VariantType::SCREEN->value => $screen],
            [VariantType::GPU->value => $gpu],
            [
                VariantType::PORTS->value => "",
                VariantType::KEYBOARD->value => "",
                VariantType::TOUCHPAD->value => "",
                VariantType::WEBCAM->value => "",
                VariantType::BATTERY->value => "",
                VariantType::WEIGHT->value => "",
                VariantType::OS->value => "",
                VariantType::WARRANTY->value => "",
                VariantType::COLOR->value => ""
            ],
        );
        // // return array_merge(
        // //     [VariantType::PROCESSOR->value => CPUProperties::toArray()],
        // //     [VariantType::RAM => RAMProperties::cases()],
        // //     [VariantType::STORAGE => StorageProperties::cases()],
        // //     [VariantType::SCREEN => ScreenProperties::cases()],
        // //     [VariantType::GPU => GPUProperties::cases()],
        // //     [
        // //         VariantType::PORTS,
        // //         VariantType::KEYBOARD,
        // //         VariantType::TOUCHPAD,
        // //         VariantType::WEBCAM,
        // //         VariantType::BATTERY,
        // //         VariantType::WEIGHT,
        // //         VariantType::OS,
        // //         VariantType::WARRANTY
        // //     ],
        // // );
    }

    public function validate($data)
    {
        $rules = [
            "name" => "required",
            "description" => "required",
            "product_id" => "required|exists:products,id",
            "standard_price" => "required",
            "tax_rate" => "required",
            "discount" => "required",
            "extra_fee" => "required",
            "cost_price" => "required",
            "specifications" => "required",
            "SKU" => "required|unique:product_variants,SKU",
            "image" => "required|url",
            "specifications.cpu.name" => ["required", "string"],
            "specifications.cpu.cores" => ["required", "numeric"],
            "specifications.cpu.threads" => ["required", "numeric"],
            "specifications.cpu.base_clock" => ["required", "numeric"],
            "specifications.cpu.turbo_clock" => ["required", "numeric"],
            "specifications.cpu.cache" => ["required", "numeric"],
            "specifications.ram.capacity" => ["required", "numeric"],
            "specifications.ram.type" => ["required", "string"],
            "specifications.ram.frequency" => ["required", "numeric"],
            "specifications.storage.drive" => ["required", "string"],
            "specifications.storage.capacity" => ["required", "numeric"],
            "specifications.storage.type" => ["required", "string"],
            "specifications.display.size" => ["required", "string"],
            "specifications.display.resolution" => ["required", "string"],
            "specifications.display.technology" => ["required", "string"],
            "specifications.display.refresh_rate" => ["required", "numeric"],
            "specifications.display.touch" => ["required", "boolean"],
            "specifications.gpu.name" => ["required", "string"],
            "specifications.gpu.memory" => ["required", "numeric"],
            "specifications.gpu.type" => ["required", "string"],
            "specifications.gpu.frequency" => ["required", "numeric"],
            "specifications.ports" => ["required", "string"],
            "specifications.keyboard" => ["required", "string"],
            "specifications.touchpad" => ["required", "string"],
            "specifications.webcam" => ["required", "string"],
            "specifications.battery" => ["required", "numeric"],
            "specifications.weight" => ["required", "numeric"],
            "specifications.os" => ["required", "string"],
            "specifications.warranty" => ["required", "numeric"],
            "specifications.color" => ["required", "string"],
        ];
        return Validator::make($data, $rules);
    }

    // public function specs(){
    //     return $this->hasMany(ProductVariantSpecs::class,'product_variant_id','id');
    // }
}
