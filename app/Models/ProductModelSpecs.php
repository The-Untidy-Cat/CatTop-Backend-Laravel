<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModelSpecs extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_model_id',
        'specs_type',
        'value'
    ];

    public function model(){
        return $this->belongsTo(ProductModel::class, 'product_model_id', 'id');
    }

    public function specsType(){
        return $this->belongsTo(SpecsType::class,'specs_type','id');
    }
}
