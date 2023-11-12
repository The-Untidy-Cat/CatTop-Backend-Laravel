<?php

use App\Enums\ProductVariantState;
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
            $table->string('slug')->nullable(false);
            $table->text('image')->nullable(true);
            $table->text('description')->nullable(true);
            $table->unsignedBigInteger('product_id')->nullable(false);
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('standard_price')->nullable(false)->default(0);
            $table->unsignedDouble('tax_rate')->nullable(false)->default(0);
            $table->double('discount')->nullable(false)->default(0);
            $table->double('extra_fee')->nullable(false)->default(0);
            $table->unsignedBigInteger('cost_price')->nullable(false)->default(0);
            $table->json('specifications')->nullable(false);
            $table->integer('state')->nullable(false)->default(ProductVariantState::DRAFT);
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
