<?php

namespace App\Models;

use App\Enums\BrandState;
use App\Enums\ProductState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Brand extends Model
{
    use HasFactory;

    protected $table = "brands";
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'parent_id'
    ];
    protected $attributes = [
        'state' => BrandState::ACTIVE,
        'parent_id' => NULL,
    ];
    protected $casts = [
        'state' => BrandState::class,
    ];
    protected $appends = ['product_count'];

    public function getProductCountAttribute()
    {
        return $this->products()->where(["products.state", "=", ProductState::PUBLISHED])->count(["products.id"]);
    }

    // public function getStateAttribute()
    // {
    //     return $this->state;
    // }

    public function validate($data)
    {
        $rules = [
            "name" => "required|string",
            "description" => "string",
            "image" => "required|url",
            "state" => [Rule::enum(BrandState::class)]
        ];
        return Validator::make($data, $rules);
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, "brand_id", "id");
    }
    // public function brandable(){
    //     return $this->morphTo();
    // }
    public function children()
    {
        return $this->hasMany(Brand::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(Brand::class, 'parent_id');
    }
}

