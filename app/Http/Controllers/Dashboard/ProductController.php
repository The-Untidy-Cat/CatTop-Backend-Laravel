<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProductState;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
    public function show($product_id)
    {
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'product']),
            'data' => $product->load(
                'variants:id,name,sku,product_id,standard_price,sale_price,discount,state', 'brand:id,name,image'
            )->only(['id', 'name', 'slug', 'description', 'image', 'state', 'variants', 'brand'])
        ], 200);
    }
    public function create(Request $request)
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
    public function update(Request $request, $product_id)
    {
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            "slug" => "unique:products,slug",
            "brand_id" => "exists:brands,id",
            "state" => [Rule::enum(ProductState::class)]
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
