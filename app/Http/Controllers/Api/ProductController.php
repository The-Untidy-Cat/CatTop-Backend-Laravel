<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $database = new DatabaseController;
        $products = $database->query($request, "products", ["id", "name", "slug", "description", "brand_id", "image"]);
        return response()->json([
            "code" => 200,
            "data" => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $product = new Product();
        $validator = $product->validate($request->all());
        if ($validator->fails()) {
            return response()->json([
                "code" => 400,
                "data" => $validator->errors()
            ], 400);
        }
        $product->name = $request->name;
        $product->id = $request->id;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->image = $request->image;
        $product->save();
        return response()->json([
            "code" => 201,
            "data" => $product
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                "code" => 404,
                "message" => "Product not found"
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
        $product->slug = $request->slug ?? $product->slug;
        $product->description = $request->description ?? $product->description;
        $product->brand_id = $request->brand_id ?? $product->brand_id;
        $product->image = $request->image ?? $product->image;
        $product->save();
        return response()->json([
            "code" => 201,
            "data" => $product
        ], 201);
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
