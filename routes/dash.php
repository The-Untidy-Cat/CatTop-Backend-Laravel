<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\DashPermission;
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
            Route::get('/{brand}', [BrandController::class, 'show']);
            Route::put('/{brand}', [BrandController::class, 'update']);
            Route::delete('/{brand}', [BrandController::class, 'destroy']);
        });
    }
);