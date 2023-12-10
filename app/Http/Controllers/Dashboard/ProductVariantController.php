<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ProductVariantState;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductVariantController extends Controller
{
    public function index(Request $request, $product_id)
    {
        $validate = Validator::make(['product_id' => $product_id], [
            'product_id' => 'required|exists:products,id'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $variants = DatabaseController::searchRead(
            'ProductVariant',
            [['product_id', '=', $product_id]],
            ['id', 'sku', 'name', 'standard_price', 'discount', 'sale_price', 'state'],
            [],
            [],
            ['*'],
            $request->offset ? $request->offset : 0,
            $request->limit ? $request->limit : 10,
        );
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ["name" => "Product Variants"]),
            'data' => $variants
        ], 200);
    }
    public function show($product_id, $variant_id)
    {
        $validate = Validator::make(['product_id' => $product_id], [
            'product_id' => 'required|exists:products,id'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $variant = ProductVariant::where('product_id', $product_id)->where('id', $variant_id);
        if (!$variant) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'message' => __('messages.get.success', ["name" => "Product Variant"]),
            'data' => $variant->first([
                'id',
                'SKU',
                'image',
                'name',
                'standard_price',
                'tax_rate',
                'discount',
                'extra_fee',
                'cost_price',
                'specifications',
                'state'
            ])
        ], 200);
    }
    public function create(Request $request, $product_id)
    {
        $variant = new ProductVariant();
        $request->merge(['product_id' => $product_id]);
        $validate = $variant->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $variant->fill($request->all());
        $variant->state = ProductVariantState::PUBLISHED;
        $variant->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ["name" => "Product Variant"]),
            'data' => $variant->get([
                'id',
                'sku',
                'name',
                'standard_price',
                'tax_rate',
                'discount',
                'extra_fee',
                'cost_price',
                'specifications',
                'state',
                'sale_price'
            ])
        ], 200);
    }
    public function update(Request $request, $product_id, $variant_id)
    {
        $variant = ProductVariant::where('product_id', $product_id)->where('id', $variant_id)->first();
        if (!$variant) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            "name" => "string",
            // "description" => "required_with:specifications",
            "standard_price" => "numeric",
            "tax_rate" => "min:0|max:1",
            "discount" => "min:0|max:1",
            "extra_fee" => "numeric",
            "cost_price" => "numeric",
            "specifications" => "array",
            "SKU" => "unique:product_variants,SKU",
            "image" => "url",
            "specifications.cpu.name" => ["required_with:specifications", "string"],
            "specifications.cpu.cores" => ["required_with:specifications", "numeric"],
            "specifications.cpu.threads" => ["required_with:specifications", "numeric"],
            "specifications.cpu.base_clock" => ["required_with:specifications", "numeric"],
            "specifications.cpu.turbo_clock" => ["required_with:specifications", "numeric"],
            "specifications.cpu.cache" => ["required_with:specifications", "numeric"],
            "specifications.ram.capacity" => ["required_with:specifications", "numeric"],
            "specifications.ram.type" => ["required_with:specifications", "string"],
            "specifications.ram.frequency" => ["required_with:specifications", "numeric"],
            "specifications.storage.drive" => ["required_with:specifications", "string"],
            "specifications.storage.capacity" => ["required_with:specifications", "numeric"],
            "specifications.storage.type" => ["required_with:specifications", "string"],
            "specifications.display.size" => ["required_with:specifications", "string"],
            "specifications.display.resolution" => ["required_with:specifications", "string"],
            "specifications.display.technology" => ["required_with:specifications", "string"],
            "specifications.display.refresh_rate" => ["required_with:specifications", "numeric"],
            "specifications.display.touch" => ["required_with:specifications", "boolean"],
            "specifications.gpu.name" => ["required_with:specifications", "string"],
            "specifications.gpu.memory" => ["required_with:specifications", "numeric"],
            "specifications.gpu.type" => ["required_with:specifications", "string"],
            "specifications.gpu.frequency" => ["required_with:specifications", "numeric"],
            "specifications.ports" => ["required_with:specifications", "string"],
            "specifications.keyboard" => ["required_with:specifications", "string"],
            "specifications.touchpad" => ["required_with:specifications", "boolean"],
            "specifications.webcam" => ["required_with:specifications", "boolean"],
            "specifications.battery" => ["required_with:specifications", "numeric"],
            "specifications.weight" => ["required_with:specifications", "numeric"],
            "specifications.os" => ["required_with:specifications", "string"],
            "specifications.warranty" => ["required_with:specifications", "numeric"]
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $variant->fill($request->all());
        $variant->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.update.success', ["name" => "Product Variant"]),
            'data' => $variant->get([
                'id',
                'sku',
                'name',
                'standard_price',
                'tax_rate',
                'discount',
                'extra_fee',
                'cost_price',
                'specifications',
                'state'
            ])
        ], 200);
    }
}
