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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('description')->nullable(true);
            $table->timestamps();
        });
        DB::table('roles')->insert([
            ['name' => 'Admin', 'description' => 'Quản lý toàn bộ hệ thống'],
            ['name' => 'Seller', 'description' => 'Người đăng sản phẩm và xử lý đơn hàng'],
            ['name' => 'Customer', 'description' => 'Khách hàng mua sắm trên web'],
            ['name' => 'Sales', 'description' => 'Tư vấn sản phẩm cho khách'],
            ['name' => 'Accountant', 'description' => 'Theo dõi giao dịch tài chính'],
            ['name' => 'Delivery', 'description' => 'Giao hàng cho khách'],
            ['name' => 'Warehouse', 'description' => 'Quản lý kho và đóng gói hàng'],
            ['name' => 'Marketing', 'description' => 'Xây dựng các chiến dịch quảng cáo'],
            ['name' => 'IT support', 'description' => 'Hỗ trợ kỹ thuật về CNTT']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
