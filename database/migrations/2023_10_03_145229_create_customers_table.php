<?php

use App\Enums\CustomerState;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable(false);
            $table->string('last_name', 100)->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('phone_number', 10)->unique()->nullable(false);
            $table->dateTime('date_of_birth')->nullable();
            $table->boolean('gender')->nullable();
            $table->enum('state', CustomerState::toArray())->nullable(false)->default(CustomerState::ACTIVE);
            $table->foreignIdFor(User::class, 'user_id')->references('id')->on('users');
            $table->dateTime('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
