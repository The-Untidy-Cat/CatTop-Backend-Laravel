<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function children()
    {
        return $this->hasMany(Brand::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(Brand::class,'parent_id');
    }
    protected $attributes = [
        'status' => 1,
        'parent_id' => NULL,
    ];
}
