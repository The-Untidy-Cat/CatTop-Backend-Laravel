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
        'image',
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function model()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
    public function validator($data)
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
