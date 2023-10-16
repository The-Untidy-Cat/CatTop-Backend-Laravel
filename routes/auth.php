<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post("/register", [AuthController::class, "customerRegister"]);
//     Route::post("/customer", [AuthController::class, "customerBasicLogin"]);
//     Route::post("/dash", [AuthController::class, "employeesBasicLogin"]);
// });
Route::get('/csrf', [CsrfCookieController::class, 'show'])->name('csrf');
Route::post("/register", [AuthController::class, "customerRegister"]);
Route::post("/customer", [AuthController::class, "customerBasicLogin"]);
Route::post("/dash", [AuthController::class, "employeesBasicLogin"]);