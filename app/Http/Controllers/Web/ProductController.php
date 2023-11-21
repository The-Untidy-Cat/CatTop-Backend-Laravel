<?php

namespace App\Http\Controllers\Web;

use App\Enums\BrandState;
use App\Enums\ProductState;
use App\Enums\ProductVariantState;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $products = DatabaseController::searchRead('Products', [['state', "=", ProductState::PUBLISHED]], ['id', 'name', 'slug', 'brand_id'], [
        ]);
    }
    public function search(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'offset' => 'integer|min:0',
                'limit' => 'integer|min:0',
                'brand' => 'array',
                'name' => 'string',
                'min_price' => 'integer|min:0',
                'max_price' => 'integer|min:0',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'code' => 400,
                    'message' => __('messages.validation_error'),
                    'errors' => $validate->errors()
                ], 400);
            }
            $conditions = [
                ['products.state', '=', ProductState::PUBLISHED],
                ['brands.state', '=', BrandState::ACTIVE],
                ['product_variants.state', '=', ProductVariantState::PUBLISHED],
                ['product_variants.sale_price', '<=', $request->max_price ?? 999999999],
                ['product_variants.sale_price', '>=', $request->min_price ?? 0]
            ];

            if (isset($request->name)) {
                $conditions[] = "&&";
                $conditions[] = ['products.name', 'like', '%' . $request->name . '%'];
            }
            if (isset($request->brand)) {
                $conditions[] = "&&";
                foreach ($request->brand as $condition) {
                    $conditions[] = ['brands.name', '=', $condition];
                    $conditions[] = "||";
                }
            }
            // $products = DatabaseController::searchRead('Product', $conditions, ['id', 'name', 'slug', 'brand_id'], ['brand:id,name,image'], $request->offset ?? 0, $request->limit ?? 10);
            // $products = Product::join('brands', 'brands.id', '=', 'products.brand_id')
            //     ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            //     ->select(['products.id', 'products.name', 'products.slug'])
            //     ->where($conditions)
            //     ->distinct();

            $products = DatabaseController::searchRead(
                'Product',
                $conditions,
                ['products.id', 'products.name', 'products.slug'],
                [
                    // 'variants' => function ($q) use ($request) {
                    //     $q->select(['id', 'product_id', DB::raw("json_unquote(JSON_EXTRACT(specifications, '$.color')) as color")])->where([
                    //         ['state', '=', ProductVariantState::PUBLISHED],
                    //         ['sale_price', '<=', $request->max_price ?? 999999999],
                    //         ['sale_price', '>=', $request->min_price ?? 0]
                    //     ]);
                    // },
                ],
                [
                    "left",
                    ["brands", "brands.id", "=", "products.brand_id"],
                    ["product_variants", "products.id", "=", "product_variants.product_id"]
                ],
                ['products.id'],
                $request->offset ?? 0,
                $request->limit ?? 10
            );

            // if (isset($request->brand_id)) {
            //     foreach ($request->brand_id as $condition) {
            //         $products = $products->where('brands.id', '=', $condition, 'or');
            //     }
            // }

            // if (isset($request->max_price)) {
            //     $products->with([
            //         'variants' => function ($query) use ($request) {
            //             $query->where(['sale_price', '<=', $request->max_price], ['sale_price', '>=', $request->min_price ?? 0]);
            //         }
            //     ]);
            // }

            // $result = $products
            //     ->offset($request->offset ?? 0)
            //     ->limit($request->limit ?? 10)
            //     ->get();
            return response()->json([
                'code' => 200,
                'message' => __('messages.list.success', ['name' => 'products']),
                'data' => $products,
                // [
                //     'records' => $result,
                //     'length' => $products->count('products.id'),
                //     'offset' => $request->offset ?? 0,
                //     'limit' => $request->limit ?? 10,
                //     'test' => $products
                // ]
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'code' => 500,
                'message' => __('messages.internal_server_error'),
                'errors' => $th->getMessage()
            ], 500);
        }

    }
    public function show($slug)
    {
        try {
            $validate = Validator::make(['slug' => $slug], [
                'slug' => 'required|string|exists:products,slug',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'code' => 400,
                    'message' => __('messages.validation_error'),
                    'errors' => $validate->errors()
                ], 400);
            }
            $product = Product::where('slug', $slug);
            if (!$product) {
                return response()->json([
                    'code' => 404,
                    'message' => __('messages.not_found', ['name' => 'product']),
                ], 404);
            }
            $product = $product
                ->with([
                    'brand:id,name,slug,description,image',
                    'variants' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'image',
                            'description',
                            "standard_price",
                            "tax_rate",
                            "discount",
                            "extra_fee",
                            "sale_price",
                            "specifications",
                            "product_id"
                        ])
                            ->where('state', '=', ProductVariantState::PUBLISHED)->orWhere('state', '=', ProductVariantState::OUT_OF_STOCK);
                    }
                ])
                ->first(['id', 'name', 'slug', 'brand_id', 'description', 'image']);
            return response()->json([
                "code" => 200,
                'data' => $product
            ], 200);

        } catch (\Exception $th) {
            return response()->json([
                'code' => 500,
                'message' => __('messages.internal_server_error'),
                'errors' => $th->getMessage()
            ], 500);
        }
    }
}
