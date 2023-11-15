<?php

use App\Enums\OrderState;
use App\Enums\PaymentMethod;
use App\Enums\PaymentState;
use App\Enums\ShoppingMethod;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class, 'customer_id')->nullable(false)->references('id')->on('customers');
            $table->foreignIdFor(Employee::class, 'employee_id')->nullable(true)->references('id')->on('employees');
            $table->enum('shopping_method', ShoppingMethod::toArray())->nullable(false)->default(ShoppingMethod::OFFLINE);
            $table->enum('payment_method', PaymentMethod::toArray())->nullable(false)->default(PaymentMethod::CASH);
            $table->enum('payment_state', PaymentState::toArray())->nullable(false)->default(PaymentState::UNPAID);
            $table->enum('state', OrderState::toArray())->nullable(false)->default(OrderState::DRAFT);
            $table->string('bill_of_landing_no')->nullable(true);
            $table->longText('note')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
