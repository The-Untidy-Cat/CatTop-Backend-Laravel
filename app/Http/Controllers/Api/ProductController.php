<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProductState;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $db = new DatabaseController();
        $offset = $request->has("offset") ? $request->offset : 0;
        $limit = $request->has("limit") ? $request->limit : 10;
        $data = $db->searchRead(
            'Product',
            ($request->route()->getName() == "web.product.index") ? [["state", "=", ProductState::PUBLISHED], ["state", "=", ProductState::PUBLISHED]] : [],
            ["id", "name", "description", "slug", "image", "brand_id"],
            ["variants:id,name,specifications,standard_price,discount,product_id"],
            $offset,
            $limit
        );
        return response()->json([
            "code" => 200,
            "message" => __('messages.get.success', ['name' => 'product']),
            "data" => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $product = new Product();
        if ($request->slug == null) {
            $request->slug = Str::slug($request->name, "-");
        }
        $validator = $product->validate($request->all());
        if ($validator->fails()) {
            return response()->json([
                "code" => 400,
                "data" => $validator->errors()
            ], 400);
        }
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->image = $request->image;
        $product->save();
        return response()->json([
            "code" => 200,
            "messsage" => __("messages.crete.success"),
            "data" => $product
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                "code" => 404,
                "message" => __("messages.model.notfound")
            ], 404);
        }
        $validator = $product->validate($request->all());
        if ($validator->fails()) {
            return response()->json([
                "code" => 400,
                "data" => $validator->errors()
            ], 400);
        }
        $product->name = $request->name ?? $product->name;
        $product->id = $request->id ?? $product->id;
        $product->slug = $request->slug ?? Str::slug($product->name, "-");
        $product->description = $request->description ?? $product->description;
        $product->brand = $request->brand ?? $product->brand;
        $product->image = $request->image ?? $product->image;
        $product->save();
        return response()->json([
            "code" => 200,
            "data" => $product
        ], 200);
    }
    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                "code" => 404,
                "message" => "Product not found"
            ], 404);
        }
        return response()->json([
            "code" => 200,
            "data" => $product
        ], 200);
    }
    public function destroy($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                "code" => 404,
                "message" => "Product not found"
            ], 404);
        }
        $product->delete();
        return response()->json([
            "code" => 200,
            "message" => "Product deleted"
        ], 200);
    }
}
