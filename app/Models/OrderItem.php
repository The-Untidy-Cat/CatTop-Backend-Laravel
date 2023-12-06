<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'variant_id',
        'amount',
        'standard_price',
        'sale_price',
        'total',
        'is_refund',
    ];

    protected $attributes = [
        // 'unit_price' => $this->variant->sale_price,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function customer()
    {
        return $this->order()->select('customer_id')->first()->customer_id;
    }
}
