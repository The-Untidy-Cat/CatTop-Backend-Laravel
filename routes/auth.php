<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CsrfController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/csrf', [CsrfController::class, 'show'])->name('csrf');
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyPin']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post("/register", [AuthController::class, "customerRegister"]);
Route::post("/customer", [AuthController::class, "customerBasicLogin"]);
Route::post("/dash", [AuthController::class, "employeesBasicLogin"]);
Route::middleware(['auth:sanctum'])->delete("/logout", [AuthController::class, "logout"]);
