<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\OrderState;
use App\Enums\PaymentState;
use App\Enums\ShoppingMethod;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = DatabaseController::searchRead(
            "Order",
            [],
            [
                "id",
                "customer_id",
                "employee_id",
                "shopping_method",
                "created_at",
                "state"
            ],
            [
                "customer:id,first_name,last_name",
                "employee:id,first_name,last_name",
                "items:id,variant_id,amount,sale_price,standard_price,order_id",
                "items.variant:id,product_id,sku",
                "items.variant.product:id,name",
            ],
            [],
            ['*'],
            $request->offset ? $request->offset : 0,
            $request->limit ? $request->limit : 10
        );
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'orders']),
            'data' => $orders
        ], 200);
    }
    public function create(Request $request)
    {
        $order = new Order();
        $request->merge(['employee_id' => $request->user()->employee()->first()->id]);
        $request->merge(['state' => OrderState::DRAFT->value]);
        $request->merge(['payment_state' => PaymentState::UNPAID->value]);
        $request->merge(['shopping_method' => ShoppingMethod::OFFLINE->value]);
        $validate = $order->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $order->fill($request->all());
        $order->save();
        foreach ($request->items as $item) {
            if ($order->items()->where('variant_id', $item['variant_id'])->first()) {
                continue;
            }
            $order->items()->create([
                'variant_id' => $item['variant_id'],
                'amount' => $item['amount'],
                'standard_price' => ProductVariant::find($item['variant_id'])->standard_price,
                'sale_price' => ProductVariant::find($item['variant_id'])->sale_price,
                'total' => ProductVariant::find($item['variant_id'])->sale_price * $item['amount'],
            ]);
        }
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => 'order']),
            'data' => $order
        ], 200);
    }
}
