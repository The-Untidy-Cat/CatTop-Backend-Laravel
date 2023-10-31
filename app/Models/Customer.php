<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Customer extends Model
{
    use HasFactory;
    protected $table = "customers";

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'date_of_birth',
        'gender',
        'status',
        'user_id',
        'email_verified_at',
        'email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function validator($data)
    {
        $phone_number_regex = "/^(0|84)(2(0[3-9]|1[0-6|8|9]|2[0-2|5-9]|3[2-9]|4[0-9]|5[1|2|4-9]|6[0-3|9]|7[0-7]|8[0-9]|9[0-4|6|7|9])|3[2-9]|5[5|6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])([0-9]{7})$/mg";
        $rules = [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "phone_number" => "required|regex:" . $phone_number_regex,
            "date_of_birth" => "date",
            "gender" => "required|in:true,false",
            "email" => "required|email|unique:customers,email",
        ];
        return Validator::make($data, $rules);
    }
}
