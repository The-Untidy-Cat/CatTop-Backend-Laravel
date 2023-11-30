<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderState;
use App\Enums\PaymentMethod;
use App\Enums\ProductVariantState;
use App\Enums\ShoppingMethod;
use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->customer()->first()
            ->orders()->with([
                    'items:variant_id,amount',
                    'items.variant:id,name',
                    'items.variant.product:id,name,slug,image',
                ])->get(['id', 'state', 'created_at']);
        return response()->json([
            'code' => 200,
            'data' => ['orders' => $orders]
        ], 200);
    }
    public function show($id)
    {
        $order = auth()->user()->customer()->first()
            ->orders()->with([
                    'items:variant_id,amount',
                    'items.variant:id,name',
                    'items.variant.product:id,name,slug,image',
                ])->find($id);
        if (!$order) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found')
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'data' => ['order' => $order]
        ], 200);
    }
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'address_id' => 'required|exists:address_books,id',
                'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
                'note' => ['string'],
                'items' => ['array']
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'code' => 400,
                    'message' => __('message.validation.error'),
                    'errors' => $validate->errors()
                ], 400);
            }
            $address = auth()->user()->customer()->first()->addressBooks()->find($request->address_id);
            if (!$address) {
                return response()->json([
                    'code' => 404,
                    'message' => __('messages.not_found')
                ], 404);
            }
            if (isset($request->items)) {
                $validate = Validator::make($request->all(), [
                    'items.*.variant_id' => ['required', 'exists:product_variants,id'],
                    'items.*.amount' => ['required', 'integer', 'min:1', 'max:10'],
                ]);
                if ($validate->fails()) {
                    return response()->json([
                        'code' => 400,
                        'message' => __('message.validation.error'),
                        'errors' => $validate->errors()
                    ], 400);
                }
                $order = auth()->user()->customer()->first()->orders()->create([
                    'state' => OrderState::PENDING,
                    'customer_id' => auth()->user()->customer()->first()->id,
                    'employee_id' => null,
                    'shopping_method' => ShoppingMethod::ONLINE,
                    'payment_method' => $request->payment_method,
                    'address_id' => $request->address_id,
                    'note' => $request->note
                ]);
                foreach ($request->items as $item) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if (!$variant || $variant->state != ProductVariantState::PUBLISHED) {
                        $order->delete();
                        return response()->json([
                            'code' => 404,
                            'message' => __('messages.not_found', ['name' => 'variant'])
                        ], 404);
                    }
                    $order->items()->create([
                        'variant_id' => $item['variant_id'],
                        'amount' => $item['amount']
                    ]);
                    // echo $item['variant_id'] . " " . $item['amount'];
                }
                $order = $order->with([
                    'items:variant_id,amount',
                    'items.variant:id,name',
                    'items.variant.product:id,name,slug,image',
                ])->find($order->id);
            } else {
                $cart = auth()->user()->customer()->first()->cart()->with([
                    'variant:id,name,product_id,sale_price',
                    'variant.product:id,name,slug',
                ])->get();
                if ($cart->count() == 0) {
                    return response()->json([
                        'code' => 400,
                        'message' => __('messages.cart.empty')
                    ], 400);
                }
                $order = auth()->user()->customer()->first()->orders()->create([
                    'state' => OrderState::PENDING,
                    'customer_id' => auth()->user()->customer()->first()->id,
                    'employee_id' => null,
                    'shopping_method' => ShoppingMethod::ONLINE,
                    'payment_method' => $request->payment_method,
                    'address_id' => $request->address_id,
                    'note' => $request->note
                ]);
                foreach ($cart as $item) {
                    $variant = ProductVariant::find($item->variant_id);
                    if (!$variant || $variant->state != ProductVariantState::PUBLISHED) {
                        $order->delete();
                        return response()->json([
                            'code' => 404,
                            'message' => __('messages.not_found', ['name' => 'variant'])
                        ], 404);
                    }
                    $order->items()->create([
                        'variant_id' => $item->variant_id,
                        'amount' => $item->amount
                    ]);
                }
                $cart->each->delete();
                $order = $order->with([
                    'items:variant_id,amount',
                    'items.variant:id,name',
                    'items.variant.product:id,name,slug,image',
                ])->find($order->id);
            }
            return response()->json([
                'code' => 200,
                'data' => ['order' => $order]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => __('messages.internal_server_error'),
                'errors' => $th->getMessage()
            ], 500);
        }

    }
}
