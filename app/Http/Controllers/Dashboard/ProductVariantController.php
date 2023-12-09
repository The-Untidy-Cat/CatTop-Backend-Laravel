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
            // "description" => "required_if:specifications",
            "standard_price" => "number",
            "tax_rate" => "min:0|max:1",
            "discount" => "min:0|max:1",
            "extra_fee" => "number",
            "cost_price" => "number",
            "specifications" => "array",
            "SKU" => "unique:product_variants,SKU",
            "image" => "url",
            "specifications.cpu.name" => ["required_if:specifications", "string"],
            "specifications.cpu.cores" => ["required_if:specifications", "numeric"],
            "specifications.cpu.threads" => ["required_if:specifications", "numeric"],
            "specifications.cpu.base_clock" => ["required_if:specifications", "numeric"],
            "specifications.cpu.turbo_clock" => ["required_if:specifications", "numeric"],
            "specifications.cpu.cache" => ["required_if:specifications", "numeric"],
            "specifications.ram.capacity" => ["required_if:specifications", "numeric"],
            "specifications.ram.type" => ["required_if:specifications", "string"],
            "specifications.ram.frequency" => ["required_if:specifications", "numeric"],
            "specifications.storage.drive" => ["required_if:specifications", "string"],
            "specifications.storage.capacity" => ["required_if:specifications", "numeric"],
            "specifications.storage.type" => ["required_if:specifications", "string"],
            "specifications.display.size" => ["required_if:specifications", "string"],
            "specifications.display.resolution" => ["required_if:specifications", "string"],
            "specifications.display.technology" => ["required_if:specifications", "string"],
            "specifications.display.refresh_rate" => ["required_if:specifications", "numeric"],
            "specifications.display.touch" => ["required_if:specifications", "boolean"],
            "specifications.gpu.name" => ["required_if:specifications", "string"],
            "specifications.gpu.memory" => ["required_if:specifications", "numeric"],
            "specifications.gpu.type" => ["required_if:specifications", "string"],
            "specifications.gpu.frequency" => ["required_if:specifications", "numeric"],
            "specifications.ports" => ["required_if:specifications", "string"],
            "specifications.keyboard" => ["required_if:specifications", "string"],
            "specifications.touchpad" => ["required_if:specifications", "string"],
            "specifications.webcam" => ["required_if:specifications", "string"],
            "specifications.battery" => ["required_if:specifications", "numeric"],
            "specifications.weight" => ["required_if:specifications", "numeric"],
            "specifications.os" => ["required_if:specifications", "string"],
            "specifications.warranty" => ["required_if:specifications", "numeric"]
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
