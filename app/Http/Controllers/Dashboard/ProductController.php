<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = DatabaseController::searchRead("Product", [], ["id", "name", "image", "state", "brand_id"], [], [], ['*'], $request->offset ? $request->offset : 0, $request->limit ? $request->limit : 10);
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'products']),
            'data' => $products
        ], 200);
    }
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'product']),
            'data' => $product->get(['id', 'name', 'slug', 'description', 'image', 'state', 'brand_id'])
        ], 200);
    }
    public function store(Request $request)
    {
        $product = new Product();
        $validate = $product->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $product->fill($request->all());
        $product->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => 'product']),
            'data' => $product->get(['id', 'name', 'slug', 'description', 'image', 'state', 'brand_id'])
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            "name" => "required",
            "slug" => "required",
            "description" => "required",
            "brand_id" => "required",
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $product->fill($request->all());
        $product->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.update.success', ['name' => 'product']),
            'data' => $product->get(['id', 'name', 'slug', 'description', 'image', 'state', 'brand_id'])
        ], 200);
    }
}
