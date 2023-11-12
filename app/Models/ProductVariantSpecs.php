<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantSpecs extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_variant_id',
        'specs_type',
        'value'
    ];

    public function model(){
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function specsType(){
        return $this->belongsTo(SpecsType::class,'specs_type','id');
    }
}
