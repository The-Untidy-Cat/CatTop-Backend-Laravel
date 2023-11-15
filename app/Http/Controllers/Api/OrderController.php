<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderState;
use App\Enums\PaymentState;
use App\Http\Controllers\Api\DatabaseController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $db = new DatabaseController();
        $data = $db->searchRead(
            'Order',
            $request->route()->getName() == "customer.order.index"
            ? [['customer_id', '=', $request->user()->customer()->id]]
            : [],
            [
                //     'order_id',
                // 'total',
                'state',
                'shopping_method',
                'payment_method',
                'payment_state'
            ],
        );
        return response()->json([
            'code' => 200,
            'data' => $data
        ], 200);
    }
    public function store(Request $request)
    {
        $order = new Order();
        $request->merge([
            "customer_id" => ($request->route()->getName() == "customer.order.store")
                ? $request->user()->customer()->id
                : (isset($request->customer_id)
                    ? $request->customer_id
                    : null),
            "employee_id" => ($request->route()->getName() == "dash.order.store")
                ? $request->user()->employee()->id
                : (isset($request->employee_id)
                    ? $request->employee_id
                    : null),
            "state" => OrderState::DRAFT->value,
            "payment_state" => PaymentState::UNPAID->value
        ]);
        $validate = $order->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $order->customer_id = $request->customer_id;
        $order->employee_id = $request->employee_id;
        $order->payment_state = $request->payment_state;
        $order->payment_method = $request->payment_method;
        $order->shopping_method = $request->shopping_method;
        $order->state = $request->state;
        $order->note = isset($request->note) ? $request->note : null;
        $order->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => "order"]),
            "data" => $order
        ], 200);
    }
}
