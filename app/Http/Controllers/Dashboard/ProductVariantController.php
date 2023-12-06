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
    public function store(Request $request, $product_id)
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
            "name" => "required",
            "description" => "required",
            "standard_price" => "required",
            "tax_rate" => "required",
            "discount" => "required",
            "extra_fee" => "required",
            "cost_price" => "required",
            "specifications" => "required",
            "SKU" => "required",
            "image" => "required|url",
            "specifications.cpu.name" => ["required", "string"],
            "specifications.cpu.cores" => ["required", "numeric"],
            "specifications.cpu.threads" => ["required", "numeric"],
            "specifications.cpu.base_clock" => ["required", "numeric"],
            "specifications.cpu.turbo_clock" => ["required", "numeric"],
            "specifications.cpu.cache" => ["required", "numeric"],
            "specifications.ram.capacity" => ["required", "numeric"],
            "specifications.ram.type" => ["required", "string"],
            "specifications.ram.frequency" => ["required", "numeric"],
            "specifications.storage.drive" => ["required", "string"],
            "specifications.storage.capacity" => ["required", "numeric"],
            "specifications.storage.type" => ["required", "string"],
            "specifications.display.size" => ["required", "string"],
            "specifications.display.resolution" => ["required", "string"],
            "specifications.display.technology" => ["required", "string"],
            "specifications.display.refresh_rate" => ["required", "numeric"],
            "specifications.display.touch" => ["required", "boolean"],
            "specifications.gpu.name" => ["required", "string"],
            "specifications.gpu.memory" => ["required", "numeric"],
            "specifications.gpu.type" => ["required", "string"],
            "specifications.gpu.frequency" => ["required", "numeric"],
            "specifications.ports" => ["required", "string"],
            "specifications.keyboard" => ["required", "string"],
            "specifications.touchpad" => ["required", "string"],
            "specifications.webcam" => ["required", "string"],
            "specifications.battery" => ["required", "numeric"],
            "specifications.weight" => ["required", "numeric"],
            "specifications.os" => ["required", "string"],
            "specifications.warranty" => ["required", "numeric"]
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
