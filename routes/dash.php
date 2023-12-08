<?php

use App\Http\Controllers\Dashboard\OrderItemController;
use App\Http\Controllers\Dashboard\SearchReadController;
use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\CustomerController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProductVariantController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum', 'auth.dash'])->group(
    function () {
        Route::get('/user', [UserController::class, 'getProfile']);
        Route::post('/search_read', [SearchReadController::class, 'index']);
        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'index']);
            Route::post('/', [BrandController::class, 'create']);
            Route::get('/state', [BrandController::class, 'state']);
            Route::get('/{id}', [BrandController::class, 'show']);
            Route::put('/{id}', [BrandController::class, 'update']);
        });
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/', [ProductController::class, 'create']);
            Route::get('/{product_id}', [ProductController::class, 'show']);
            Route::put('/{product_id}', [ProductController::class, 'update']);
            Route::prefix('{product_id}/variants')->group(function () {
                Route::get('/', [ProductVariantController::class, 'index']);
                Route::post('/', [ProductVariantController::class, 'create']);
                Route::get('/{variant_id}', [ProductVariantController::class, 'show']);
                Route::put('/{variant_id}', [ProductVariantController::class, 'update']);
            });
        });
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'index']);
            Route::post('/', [CustomerController::class, 'create']);
            Route::get('/{id}', [CustomerController::class, 'show']);
            Route::put('/{id}', [CustomerController::class, 'update']);
        });
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'create']);
            Route::prefix('{order_id}')->group(function () {
                Route::get('/', [OrderController::class, 'show']);
                Route::put('/', [OrderController::class, 'update']);
                Route::prefix('items')->group(function () {
                    Route::get('/', [OrderItemController::class, 'index']);
                    Route::post('/', [OrderItemController::class, 'create']);
                    Route::put('/{item_id}', [OrderItemController::class, 'update']);
                    Route::delete('/{item_id}', [OrderItemController::class, 'delete']);
                });
            });
        });
    }
);
