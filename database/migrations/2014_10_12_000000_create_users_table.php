<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->index()->unique()->nullable()->default(null);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['username' => 'admin', 'password' => bcrypt('admin')],
            ['username' => 'seller', 'password' => bcrypt('seller')],
            ['username' => 'customer', 'password' => bcrypt('customer')],
            ['username' => 'sales', 'password' => bcrypt('sales')],
            ['username' => 'accountant', 'password' => bcrypt('accountant')],
            ['username' => 'delivery', 'password' => bcrypt('delivery')],
            ['username' => 'warehouse', 'password' => bcrypt('warehouse')],
            ['username' => 'marketing', 'password' => bcrypt('marketing')],
            ['username' => 'it_support', 'password' => bcrypt('it_support')]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
