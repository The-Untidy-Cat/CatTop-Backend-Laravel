<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('address_books', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class, 'customer_id')->nullable(false)->references('id')->on('customers');
            $table->string('name');
            $table->string('phone');
            $table->string('address_line');
            $table->unsignedInteger('ward');
            $table->unsignedInteger('district');
            $table->unsignedInteger('province');
            $table->unique(['customer_id', 'name', 'phone', 'address_line', 'ward', 'district', 'province']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_books');
    }
};
