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
    }
);
