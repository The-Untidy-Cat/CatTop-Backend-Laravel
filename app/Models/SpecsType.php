<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function specs(){
        return $this->hasMany(ProductModelSpecs::class,'specs_type','id');
    }
}
