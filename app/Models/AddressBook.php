<?php

namespace App\Models;

use App\Rules\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class AddressBook extends Model
{
    use HasFactory;
    protected $table = "address_books";
    protected $fillable = [
        'customer_id',
        'name',
        'phone',
        'address_line',
        'ward',
        'district',
        'province'
    ];

    protected $hidden = ['id', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function validate($data)
    {
        $rules = [
            'customer_id' => ['required', 'exists:customers,id'],
            'name' => ['required', 'string'],
            'phone' => ['required', new PhoneNumber],
            'address_line' => ['required', 'string'],
            'ward' => ['required', 'integer'],
            'district' => ['required', 'integer'],
            'province' => ['required', 'integer']
        ];
        return Validator::make($data, $rules);
    }
}
