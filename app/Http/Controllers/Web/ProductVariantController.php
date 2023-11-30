<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function show($id)
    {
        $variant = ProductVariant::where([['id', '=', $id]]);
        if ($variant) {
            $variant = $variant->with('product:id,slug,name,image,state')
                ->first(['id', 'name', 'sale_price', 'standard_price', 'discount', 'product_id', 'image', 'state']);
            return response()->json([
                'code' => 200,
                'message' => __('messages.success'),
                'data' => $variant
            ], 200);
        } else {
            return response()->json([
                'code' => 404,
                'message' => __('messages.not_found'),
            ], 404);
        }
    }
}
