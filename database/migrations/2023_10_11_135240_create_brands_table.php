<?php

use App\Enums\BrandState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('slug')->nullable(true);
            $table->text('description')->nullable(true);
            $table->string('image')->nullable(true);
            $table->enum('state', BrandState::toArray())->nullable(false)->default(BrandState::ACTIVE);
            $table->unsignedBigInteger('parent_id')->nullable(true);
            $table->foreign('parent_id')->references('id')->on('brands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
