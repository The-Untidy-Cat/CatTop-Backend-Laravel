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
        Schema::create('product_variant_specs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id')->nullable(false);
            $table->string('specs_type')->nullable(false);
            $table->string('value');
            $table->primary(['product_variant_id', 'specs_type']);
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_specs');
    }
};
