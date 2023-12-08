<?php

namespace App\Models;

use App\Enums\CustomerState;
use App\Rules\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Customer extends Model {
    use HasFactory;
    protected $table = "customers";

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'date_of_birth',
        'gender',
        'state',
        'user_id',
        'email_verified_at',
        'email',
    ];

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
    public function addressBooks() {
        return $this->hasMany(AddressBook::class, "customer_id", "id");
    }
    public function cart() {
        return $this->hasMany(Cart::class, "customer_id", "id");
    }
    public function orders() {
        return $this->hasMany(Order::class, "customer_id", "id");
    }
    public function getOrderCountAttribute() {
        return $this->orders()->count();
    }
    protected $casts = [
        'state' => CustomerState::class,
    ];

    protected $appends = [
        'order_count'
    ];
    public function validate($data) {
        $rules = [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "phone_number" => ["required", new PhoneNumber, "unique:users,username", "unique:customers,phone_number"],
            "date_of_birth" => "date",
            "gender" => "required|in:0,1",
            "email" => "required|email|unique:customers,email",
        ];
        return Validator::make($data, $rules);
    }
}
