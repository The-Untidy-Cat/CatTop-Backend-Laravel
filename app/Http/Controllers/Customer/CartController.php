<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $cart = Cart::where([['customer_id', '=', $request->user()->customer()->first()->id]]);
        $cart = $cart->with([
            'variant:id,name,product_id,sale_price,discount,standard_price,image,state',
            'variant.product:id,name,slug,image,state',
        ])->get();
        return response()->json([
            'code' => 200,
            'data' => ['cart' => $cart]
        ], 200);
    }
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'variant_id' => 'required|exists:product_variants,id',
                'amount' => 'required|integer'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('message.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $cart = Cart::where([['customer_id', '=', $request->user()->customer()->first()->id], ['variant_id', '=', $request->variant_id]])->first();
        if ($cart) {
            $cart->amount += $request->amount;
            $cart->save();
        } else {
            $cart = Cart::create([
                'customer_id' => $request->user()->customer()->first()->id,
                'variant_id' => $request->variant_id,
                'amount' => $request->amount
            ]);
        }
        $cart = Cart::where([['customer_id', '=', $request->user()->customer()->first()->id]]);
        $cart = $cart->with([
            'variant:id,name,product_id,sale_price,discount,standard_price,image,state',
            'variant.product:id,name,slug,image,state',
        ])->get();
        return response()->json([
            'code' => 200,
            'data' => ['cart' => $cart]
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json([
                'code' => 404,
                'message' => __('message.not_found')
            ], 404);
        }
        $validate = Validator::make(
            [
                'amount' => $request->amount
            ],
            [
                'amount' => 'required|integer|min:0'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('message.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        if ($request->amount == 0) {
            $cart->delete();
        } else {
            $cart->amount = $request->amount;
            $cart->save();
        }

        $cart = Cart::where([['customer_id', '=', $request->user()->customer()->first()->id]]);
        $cart = $cart->with([
            'variant:id,name,product_id,sale_price,discount,standard_price,image,state',
            'variant.product:id,name,slug,image,state',
        ])->get();
        return response()->json([
            'code' => 200,
            'data' => ['cart' => $cart]
        ], 200);
    }
    public function clear(Request $request)
    {
        $cart = Cart::where([['customer_id', '=', $request->user()->customer()->first()->id]]);
        $cart->delete();
        return response()->json([
            'code' => 200,
            'message' => __('message.delete.success')
        ], 200);
    }
}
