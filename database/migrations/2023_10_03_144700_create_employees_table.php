<?php

use App\Enums\EmployeeState;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable(false);
            $table->string('last_name', 100)->nullable(true);
            $table->string('display_name');
            $table->string('email')->unique()->nullable(false);
            $table->string('phone_number', 10)->nullable(false);
            $table->dateTime('date_of_birth')->nullable();
            $table->boolean('gender')->nullable();
            $table->string('department', 100)->nullable();
            $table->string('job_title', 100)->nullable();
            $table->enum('state', EmployeeState::toArray())->nullable(false)->default(EmployeeState::ACTIVE);
            // $table->foreignIdFor(Employee::class, 'manager_id')->references('id')->on('employees')->nullable(true)->default(null);
            $table->foreignIdFor(User::class, 'user_id')->references('id')->on('users')->nullable(true)->default(null);
            $table->timestamps();
        });
        DB::table('employees')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => '',
                'display_name' => 'admin',
                'email' => 'admin@cattop.theuntidycat.tech',
                'phone_number' => '0999999999',
                'user_id' => 1
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
