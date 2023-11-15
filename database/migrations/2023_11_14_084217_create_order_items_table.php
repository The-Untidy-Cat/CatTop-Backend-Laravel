<?php

use App\Models\Order;
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
        Schema::create('order_items', function (Blueprint $table) {
            $table->foreignIdFor(Order::class, 'order_id')->references('id')->on('orders')->nullable(false);
            $table->foreignIdFor(ProductVariant::class, 'variant_id')->references('id')->on('product_variants')->nullable(false);
            $table->primary(['order_id', 'variant_id']);
            $table->unsignedInteger('amount')->nullable(false)->default(1);
            $table->unsignedBigInteger('unit_price')->nullable(false)->default(0);
            $table->boolean('is_refund')->default(false);
            $table->text('serial_number')->nullable(true)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
