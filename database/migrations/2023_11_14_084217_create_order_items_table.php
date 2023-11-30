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
            $table->id();
            $table->foreignIdFor(Order::class, 'order_id')->references('id')->on('orders')->cascadeOnDelete()->nullable(false);
            $table->foreignIdFor(ProductVariant::class, 'variant_id')->references('id')->on('product_variants')->cascadeOnDelete()->nullable(false);
            $table->unique(['order_id', 'variant_id']);
            $table->unsignedInteger('amount')->nullable(false)->default(1);
            $table->unsignedBigInteger('standard_price')->nullable(false)->default(0);
            $table->unsignedBigInteger('sale_price')->nullable(false)->default(0);
            $table->unsignedBigInteger('total')->nullable(false)->default(0);
            $table->boolean('is_refunded')->default(false);
            $table->enum('rating', [1, 2, 3, 4, 5])->nullable(true);
            $table->text('review')->nullable(true)->default('');
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
