<?php

use App\Models\Customer;
use App\Models\ProductVariant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->foreignIdFor(Customer::class, 'customer_id')->references('id')->on('customers');
            $table->foreignIdFor(ProductVariant::class, 'variant_id')->references('id')->on('product_variants');
            $table->unsignedInteger('amount')->nullable(false)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
