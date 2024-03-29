<?php


use App\Http\Controllers\Customer\AddressBookController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\UserController;
use App\Http\Middleware\CustomerPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', CustomerPermission::class])->group(
    function () {
        Route::group(['prefix' => '/user'], function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/change-password', [UserController::class, 'changePassword']);
            Route::put('/', [UserController::class, 'update']);
        });
        Route::group(['prefix' => '/orders'], function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::post('/', [OrderController::class, 'create']);
            Route::group(['prefix' => '/{id}'], function () {
                Route::get('/', [OrderController::class, 'show']);
                Route::put('/', [OrderController::class, 'update']);
                Route::delete('/', [OrderController::class, 'destroy']);
                Route::post('/items/{item_id}/rate', [OrderController::class, 'rate']);
            });
        });
        Route::group(['prefix' => '/address'], function () {
            Route::get('/', [AddressBookController::class, 'index']);
            Route::post('/', [AddressBookController::class, 'create']);
            Route::put('/{id}', [AddressBookController::class, 'update']);
        });
        Route::group(['prefix' => '/cart'], function () {
            Route::get('/', [CartController::class, 'show']);
            Route::post('/', [CartController::class, 'create']);
            Route::put('/{id}', [CartController::class, 'update']);
            Route::delete('/', [CartController::class, 'clear']);
        });
    }
);
