<?php

namespace App\Models;

use App\Enums\BrandState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Validator;

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

    public function validate($data)
    {
        $rules = [
            "name" => "required",
            "slug" => "required",
            "description" => "required",
            "image" => "required|url",
        ];
        return Validator::make($data, $rules);
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, "brand", "id");
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
        return $this->belongsTo(Brand::class,'parent_id');
    }
    protected $attributes = [
        'state' => BrandState::ACTIVE,
        'parent_id' => NULL,
    ];
    protected $casts = [
        'state' => BrandState::class,
    ];
}
