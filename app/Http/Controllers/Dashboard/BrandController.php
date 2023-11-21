<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\BrandState;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = DatabaseController::searchRead(
            "Brand",
            [],
            ["id", "name", "image", "state"],
            [],
            [],
            ['*'],
            $request->offset ? $request->offset : 0,
            $request->limit ? $request->limit : 10
        );
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'brands']),
            'data' => $brands
        ], 200);
    }
    public function show($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        return response()->json([
            'code' => 200,
            'message' => __('messages.get.success', ['name' => 'brand']),
            'data' => $brand
        ], 200);
    }
    public function store(Request $request)
    {
        $brand = new Brand();
        $validate = $brand->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $brand->fill($request->all());
        $brand->state = BrandState::ACTIVE;
        $brand->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => 'brand']),
            'data' => $brand->only(['id', 'name', 'slug', 'description', 'image', 'state', 'parent_id'])
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
        $validate = $brand->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $brand->fill($request->all());
        $brand->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.update.success', ['name' => 'brand']),
            'data' => $brand->only(['id', 'name', 'slug', 'description', 'image', 'state', 'parent_id'])
        ], 200);
    }
    public function state()
    {
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'brand states']),

        ], 200);
    }
}
