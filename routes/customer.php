<?php


use App\Http\Controllers\Customer\AddressBookController;
use App\Http\Controllers\Customer\UserController;
use App\Http\Middleware\CustomerPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', CustomerPermission::class])->group(
    function () {
        Route::group(['prefix' => '/user'], function () {
            Route::get('/', [UserController::class, 'getProfile']);
            Route::put('/change-password', [UserController::class, 'changePassword']);
        });
        Route::group(['prefix' => '/orders'], function () {
            // Route::post('/', [ProductController::class, 'store']);
            // Route::get('/{id}', [ProductController::class, 'show']);
            // Route::put('/{id}', [ProductController::class, 'update']);
            // Route::delete('/{id}', [ProductController::class, 'destroy']);
        });
        Route::group(['prefix' => '/address'], function () {
            Route::get('/', [AddressBookController::class, 'index']);
            Route::post('/', [AddressBookController::class, 'store']);
            Route::put('/{id}', [AddressBookController::class, 'update']);
        });
    }
);
