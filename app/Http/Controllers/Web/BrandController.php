<?php

namespace App\Http\Controllers\Web;

use App\Enums\BrandState;
use App\Enums\ProductState;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brand = Brand::where('state', BrandState::ACTIVE)->get(['id', 'name', 'image']);
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'brands']),
            'data' => $brand
        ], 200);
    }
    public function show(Request $request)
    {
        $brand = Brand::find($request->id);
        if (!$brand) {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }

        foreach ($brand->products() as $product) {
            $product->makeHidden('brand_id');
        }
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'brand']),
            'data' => $brand->with([
                'products' => function ($q) {
                    $q->select(['id','name','slug', 'brand_id'])->where('state', ProductState::PUBLISHED);
                }
            ])->first(['id', 'name', 'image', 'description'])
        ], 200);
    }
}
