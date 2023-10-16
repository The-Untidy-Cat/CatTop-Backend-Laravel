<?php

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
        Schema::create('specs_types', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(false);
            $table->text('description')->nullable(true);
            $table->string('regex')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specs_types');
    }
};
