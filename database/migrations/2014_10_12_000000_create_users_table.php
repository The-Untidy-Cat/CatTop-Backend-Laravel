<?php

use App\Enums\UserState;
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
            $table->enum('state', UserState::toArray())->nullable(false)->default(UserState::ACTIVE);
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['username' => 'admin', 'password' => bcrypt('admin')],
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
