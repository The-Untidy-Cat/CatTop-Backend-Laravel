<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\OrderState;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Validator;

class OrderItemController extends Controller
{
    public function index(Request $request, $order_id)
    {
        $orderItems = DatabaseController::searchRead(
            "OrderItem",
            [
                ["order_id", "=", $order_id]
            ],
            [
                "id",
                "variant_id",
                "amount",
                "sale_price",
                "standard_price",
                "order_id"
            ],
            [
                "variant:id,product_id,sku",
                "variant.product:id,name",
            ],
            [],
            ['*'],
            $request->offset ? $request->offset : 0,
            $request->limit ? $request->limit : 0
        );
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'order items']),
            'data' => $orderItems
        ], 200);
    }
    public function create(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        if (
            in_array($request->state, [OrderState::CONFIRMED->value,
                OrderState::DELIVERING->value,
                OrderState::DELIVERED->value,
                OrderState::CANCELLED->value,
                OrderState::REFUNDED->value,
                OrderState::FAILED->value])
        ) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.order.state_not_allowed'),
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|exists:product_variants,id',
            'amount' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validator->errors()
            ], 400);
        }
        $variant = $order->items()->where('variant_id', $request->variant_id)->first();
        if ($variant) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => [
                    'variant_id' => [
                        __('messages.validation.unique', ['name' => 'variant'])
                    ]
                ]
            ], 400);
        }
        $order->items()->create([
            'variant_id' => $request->variant_id,
            'amount' => $request->amount,
            'standard_price' => ProductVariant::find($request->variant_id)->standard_price,
            'sale_price' => ProductVariant::find($request->variant_id)->sale_price,
            'total' => ProductVariant::find($request->variant_id)->sale_price * $request->amount,
        ]);
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => 'order item']),
            'data' => $order->items()->where('variant_id', $request->variant_id)->first()
        ], 200);
    }
    public function update(Request $request, $order_id, $item_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        if (
            in_array($request->state, [OrderState::CONFIRMED->value,
                OrderState::DELIVERING->value,
                OrderState::DELIVERED->value,
                OrderState::CANCELLED->value,
                OrderState::REFUNDED->value,
                OrderState::FAILED->value])
        ) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.order.state_not_allowed'),
            ], 400);
        }
        $orderItem = OrderItem::find($item_id);
        if (!$orderItem) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validator->errors()
            ], 400);
        }
        if ($request->amount) {
            $orderItem->amount = $request->amount;
        }
        $orderItem->total = $orderItem->sale_price * $orderItem->amount;
        $orderItem->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.update.success', ['name' => 'order item']),
            'data' => $orderItem
        ], 200);
    }
    public function delete(Request $request, $order_id, $item_id)
    {
        $order = Order::find($order_id);
        if (!$order) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        if (
            in_array($request->state, [OrderState::CONFIRMED->value,
                OrderState::DELIVERING->value,
                OrderState::DELIVERED->value,
                OrderState::CANCELLED->value,
                OrderState::REFUNDED->value,
                OrderState::FAILED->value])
        ) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.order.state_not_allowed'),
            ], 400);
        }
        $orderItem = OrderItem::find($item_id);
        if (!$orderItem) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        $orderItem->delete();
        return response()->json([
            'code' => 200,
            'message' => __('messages.delete.success', ['name' => 'order item']),
        ], 200);
    }
}
