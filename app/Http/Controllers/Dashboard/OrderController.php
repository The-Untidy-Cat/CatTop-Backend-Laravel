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
            $request->limit ? $request->limit : 10,
            $request->order_by ?? "created_at",
            $request->order ?? "desc"
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
        if ($request->has('items')) {
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
            $order->state = OrderState::CONFIRMED->value;
        }

        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => 'order']),
            'data' => $order
        ], 200);
    }
    public function update(Request $request, $order_id)
    {
        try {
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
        if ($request->state == OrderState::FAILED->value) {
            $newOrder = new Order();
            $newOrder->fill($order->toArray());
            $newOrder->customer_id = $order->customer_id;
            $newOrder->state = OrderState::PENDING->value;
            $newOrder->payment_state = PaymentState::UNPAID->value;
            $newOrder->note = "Đổi trả đơn hàng #$order->id";
            $newOrder->save();
            $order->state = OrderState::FAILED->value;
            $order->note = "Đã đổi trả đơn hàng #$newOrder->id";
            $order->items()->get()->each(function ($item) use ($newOrder) {
                $newOrder->items()->create([
                    'variant_id' => $item->variant_id,
                    'amount' => $item->amount,
                    'standard_price' => $item->standard_price,
                    'sale_price' => $item->sale_price,
                    'total' => $item->total,
                ]);
            });
            $order->save();
            return response()->json([
                'code' => 200,
                'message' => __('messages.update.success', ['name' => 'order']),
                'data' => [
                    'old' => $order,
                    'new' => $newOrder
                ]
            ], 200);
        }
        if (
            $request->state == OrderState::DELIVERED->value &&
            ($order->payment_state != PaymentState::PAID->value)
        ) {
            if (isset($request->payment_state) && $request->payment_state != PaymentState::PAID->value)
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
        } catch (\Exception $e) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.update.error', ['name' => 'order']),
                'errors' => $e->getMessage()
            ], 400);
        }

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
                'items:id,variant_id,amount,sale_price,standard_price,total,order_id,rating,review',
                'items.variant:id,name,product_id,sku',
                'items.variant.product:id,name',
                'address:id,name,phone,address_line,province,district,ward'
            )
        ], 200);
    }
    public function statistics(Request $request)
    {
        $data = Order::selectRaw('count(orders.id) as total_order, sum(order_items.sale_price * order_items.amount) as total_sale, sum(order_items.standard_price * order_items.amount) as total_standard, sum(order_items.amount) as total_amount, orders.state as state');
        $data = $data->join('order_items', 'order_items.order_id', '=', 'orders.id');
        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
        }
        $data = $data->groupBy('state')->get();
        return response()->json([
            'code' => 200,
            'message' => __('messages.get.success'),
            'data' => $data
        ], 200);
    }
}
