<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class SpecsType extends Model
{
    use HasFactory;
    protected $table = "specs_types";
    protected $fillable = [
        'id', // 'id' is the primary key of the table 'specs_types
        'name',
        'slug',
        'description',
    ];

    public $incrementing = false;

    public function validate($data)
    {
        $rules = [
            "name" => "required",
            "slug" => "required",
            "description" => "required",
        ];
        return Validator::make($data, $rules);
    }

    public function specs(){
        return $this->hasMany(ProductVariantSpecs::class,'specs_type','id');
    }
}
