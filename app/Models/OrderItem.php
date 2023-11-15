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
        'is_refund'
    ];

    protected $attributes = [
        // 'unit_price' => $this->variant->sale_price,
    ];

    protected $appends = ['total'];

    public function getTotalAttribute(){
        return $this->amount*$this->unit_price;
    }

    public function setUnitPriceAttribute($value){
        $this->attributes['unit_price'] = $this->variant->sale_price;
    }
    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function variant(){
        return $this->belongsTo(ProductVariant::class,'variant_id', 'id');
    }
}
