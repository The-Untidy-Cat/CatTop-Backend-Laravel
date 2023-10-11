<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = "employees";

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'display_name',
        'date_of_birth',
        'gender',
        'status',
        'user_id',
        'email_verified_at',
        'email',
        'user_id',
        'department',
        'job_title',
        'manager_id'
    ];
}