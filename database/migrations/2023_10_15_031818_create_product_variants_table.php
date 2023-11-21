<?php

use App\Enums\ProductVariantState;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->string('SKU')->nullable(false)->unique();
            $table->string('name')->nullable(false);
            $table->text('image')->nullable(true);
            $table->text('description')->nullable(true);
            $table->foreignIdFor(Product::class, 'product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->unsignedBigInteger('standard_price')->nullable(true)->default(0);
            $table->unsignedDouble('tax_rate')->nullable(true)->default(0.1);
            $table->double('discount')->nullable(true)->default(0);
            $table->double('extra_fee')->nullable(true)->default(0);
            $table->unsignedBigInteger('cost_price')->nullable(true)->default(0);
            $table->unsignedBigInteger('sale_price')->nullable(true)->default(0);
            $table->json('specifications')->nullable(true);
            $table->enum('state', ProductVariantState::toArray())->nullable(false)->default(ProductVariantState::DRAFT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
