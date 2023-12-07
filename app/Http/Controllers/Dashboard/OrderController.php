<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\OrderState;
use App\Enums\PaymentMethod;
use App\Enums\PaymentState;
use App\Enums\ShoppingMethod;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
    public function update(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        $validate = Validator::make($request->all(), [
            'address_id' => ['exists:address_books,id'],
            'customer_id' => ['exists:customers,id'],
            'shopping_method' => [Rule::enum(ShoppingMethod::class)],
            'payment_method' => [Rule::enum(PaymentMethod::class)],
            'payment_state' => [Rule::enum(PaymentState::class)],
            'state' => [Rule::enum(OrderState::class)],
            'note' => ['string'],
            'items' => ['array'],
            'items.*.variant_id' => ['exists:product_variants,id'],
            'items.*.amount' => ['integer', 'min:1'],
            'tracking_no' => ['string'],
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        if (
            $request->state == OrderState::DELIVERED->value &&
            ($request->payment_state != PaymentState::PAID->value && $order->payment_state != PaymentState::PAID->value)
        ) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => [
                    'payment_state' => [__('messages.order.payment_state.error')]
                ]
            ], 400);
        }
        $order->fill($request->all());
        $order->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.update.success', ['name' => 'order']),
            'data' => $order
        ], 200);
    }
    public function show(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        return response()->json([
            'code' => 200,
            'message' => __('messages.get.success'),
            'data' => $order->load(
                'customer:id,first_name,last_name',
                'employee:id,first_name,last_name',
                'items:id,variant_id,amount,sale_price,standard_price,order_id,rating,review',
                'items.variant:id,product_id,sku',
                'items.variant.product:id,name',
            )
        ], 200);
    }
}
