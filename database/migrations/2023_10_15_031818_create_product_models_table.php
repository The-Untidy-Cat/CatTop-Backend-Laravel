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
        Schema::create('product_models', function (Blueprint $table) {
            $table->id();
            $table->string('SKU')->nullable(false)->unique();
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->text('image')->nullable(true);
            $table->text('description')->nullable(true);
            $table->unsignedBigInteger('product_id')->nullable(false);
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('price_before_discount')->nullable(false);
            $table->unsignedDouble('discount_percent')->nullable(false);
            $table->unsignedBigInteger('price')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_models');
    }
};