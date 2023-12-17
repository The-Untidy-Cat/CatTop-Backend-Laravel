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
        try {
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
                    'variants:id,name,sku,product_id,standard_price,sale_price,discount,state', 'brand:id,name,image',
                    // 'orders:orders.id,orders.created_at',
                )->only(['id', 'name', 'slug', 'description', 'image', 'state', 'variants', 'brand'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('messages.server_error'),
                'errors' => $e->getMessage()
            ], 500);
        }

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
            'data' => $product->only(['id', 'name', 'slug', 'description', 'image', 'state', 'brand_id'])
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
    public function statistics(Request $request)
    {
        try {
            $products = Product::join('product_variants', 'product_variants.product_id', '=', 'products.id')
                ->join('order_items', 'order_items.variant_id', '=', 'product_variants.id')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->selectRaw('products.id, products.name as product_name, product_variants.name as variant_name, count(order_items.id) as total_order, sum(order_items.amount) as total_amount, sum(order_items.amount * order_items.sale_price) as total_sale')
                ->groupBy('products.id', 'products.name', 'product_variants.name', 'products.state')
                ->orderBy('total_order', 'desc');
            if ($request->has('start_date') && $request->has('end_date')) {
                $products->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
            }
            return response()->json([
                'code' => 200,
                'message' => __('messages.list.success', ['name' => 'products']),
                'data' => $products->distinct()->limit(10)->get()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('messages.server_error'),
                'errors' => $e->getMessage()
            ], 500);
        }

    }
}
