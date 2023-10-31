<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SpecsTypeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BrandController;
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
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::group(['prefix' => '/brands'], function () {
            Route::get('/', [BrandController::class, 'index']);
            Route::post('/', [BrandController::class, 'store']);
            Route::get('/{id}', [BrandController::class, 'show']);
            Route::put('/{id}', [BrandController::class, 'update']);
            Route::delete('/{id}', [BrandController::class, 'destroy']);
        });
        Route::group(['prefix' => '/specs-types'], function () {
            Route::get('/', [SpecsTypeController::class, 'index']);
            Route::post('/', [SpecsTypeController::class, 'store']);
            Route::get('/{id}', [SpecsTypeController::class, 'show']);
            Route::put('/{id}', [SpecsTypeController::class, 'update']);
            Route::delete('/{id}', [SpecsTypeController::class, 'destroy']);
        });
        Route::group(['prefix' => '/products'], function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/', [ProductController::class, 'store']);
            Route::get('/{id}', [ProductController::class, 'show']);
            Route::put('/{id}', [ProductController::class, 'update']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });
    }
);
