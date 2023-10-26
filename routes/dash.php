<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BrandController;
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
    }
);
