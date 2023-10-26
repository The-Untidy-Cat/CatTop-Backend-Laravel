<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = "product_models";
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'product_id',
        'price_before_discount',
        'discount_percent',
        'price',
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function specs(){
        return $this->hasMany(ProductModelSpecs::class,'product_model_id','id');
    }
}
