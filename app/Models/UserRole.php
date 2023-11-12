<?php

namespace App\Models;

use \App\Enums\UserRole as UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $table = "user_roles";

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    protected $cast = [
        'role_id' => UserRoleEnum::class,
    ];

    // public function user()
    // {
    //     return $this->hasMany(User::class, "id", "user_id");
    // }
}
