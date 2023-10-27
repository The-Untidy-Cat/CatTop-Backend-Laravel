<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Csrfcontroller;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/csrf', [Csrfcontroller::class, 'show'])->name('csrf');
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyPin']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post("/register", [AuthController::class, "customerRegister"]);
Route::post("/customer", [AuthController::class, "customerBasicLogin"]);
Route::post("/dash", [AuthController::class, "employeesBasicLogin"]);
