<?php

use App\Enums\ProductState;
use App\Models\Brand;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->text('description')->nullable(true);
            $table->string('image')->nullable(true);
            $table->foreignIdFor(Brand::class, 'brand_id')->references('id')->on('brands')->cascadeOnDelete();
            $table->enum('state', ProductState::toArray())->nullable(false)->default(ProductState::DRAFT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
