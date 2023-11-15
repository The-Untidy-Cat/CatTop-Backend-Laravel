<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CustomerPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', CustomerPermission::class])->group(
    function () {
        Route::get('/user', [UserController::class, 'getProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::group(['prefix' => '/products'], function () {
            Route::get('/', [\App\Http\Controllers\Api\OrderController::class, 'index'])->name('customer.order.index');
            // Route::post('/', [ProductController::class, 'store']);
            // Route::get('/{id}', [ProductController::class, 'show']);
            // Route::put('/{id}', [ProductController::class, 'update']);
            // Route::delete('/{id}', [ProductController::class, 'destroy']);
        });
    }
);
