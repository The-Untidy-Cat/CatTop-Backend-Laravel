<?php

namespace App\Models;

use App\Enums\OrderState;
use App\Enums\PaymentMethod;
use App\Enums\PaymentState;
use App\Enums\ShoppingMethod;
use App\Models\OrderItem;
use App\Rules\ValidCartItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";
    protected $fillable = [
        'address_id',
        'customer_id',
        'employee_id',
        'shopping_method',
        'payment_method',
        'payment_state',
        'state',
        'note',
        'tracking_no'
    ];

    protected $casts = [
        'state' => OrderState::class,
        'shopping_method' => ShoppingMethod::class,
        'payment_method' => PaymentMethod::class,
        'payment_state' => PaymentState::class
    ];

    protected $hidden = ['id', 'customer_id', 'employee_id'];

    protected $appends = ['order_id', 'total'];

    // protected $with = ['customer:id,first_name,last_name', 'employee:id,first_name,last_name'];

    public function validate($data)
    {
        $rules = [
            'customer_id' => ['required', 'exists:customers,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'shopping_method' => ['required', Rule::enum(ShoppingMethod::class)],
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'payment_state' => ['required', Rule::enum(PaymentState::class)],
            'state' => ['required', Rule::enum(OrderState::class)],
            'note' => ['string'],
            'items' => ['required', 'array'],
            'items.*.variant_id' => ['required', new ValidCartItem],
            'items.*.amount' => ['required', 'integer', 'min:1'],
        ];
        return Validator::make($data, $rules);
    }
    public function getOrderIdAttribute()
    {
        return "SO" . (intval($this->id) < 10000 ? str_pad(strval($this->id), 4, "0", STR_PAD_LEFT) : strval($this->id));
    }

    public function getTotalAttribute()
    {
        return $this->items()->sum('total');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, "order_id", "id");
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id", "id");
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }
    public function histories()
    {
        return $this->hasMany(OrderHistory::class, "order_id", "id");
    }
}
