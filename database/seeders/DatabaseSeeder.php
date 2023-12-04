<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = new User();
        $user->username = 'theuntidycat';
        $user->password = bcrypt('theuntidycat');
        $user->save();
        $user->userRole()->create([
            'role_id' => UserRole::ADMIN
        ]);
        $user->employee()->create([
            'first_name' => 'Admin',
            'last_name' => '',
            'display_name' => 'admin',
            'email' => 'admin@theuntidycat.tech',
            'phone_number' => '0999999999'
        ]);
    }
}
