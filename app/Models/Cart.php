<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = "carts";
    protected $fillable = [
        "customer_id",
        "variant_id",
        "amount"
    ];

    protected $hidden = [
        "customer_id",
        "variant_id",
    ];

    protected $casts = [];

    protected $appends = [
        'total'
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function getTotalAttribute()
    {
        return $this->amount * $this->variant()->sale_price();
    }
}
