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
        Schema::create('product_model_specs', function (Blueprint $table) {
            $table->unsignedBigInteger('product_model_id')->nullable(false);
            $table->string('specs_type')->nullable(false);
            $table->string('value');
            $table->primary(['product_model_id', 'specs_type']);
            $table->foreign('product_model_id')->references('id')->on('product_models')->onDelete('cascade');
            $table->foreign('specs_type')->references('id')->on('specs_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_model_specs');
    }
};