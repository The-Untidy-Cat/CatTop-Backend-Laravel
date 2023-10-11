<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


// middleware(VerifyCsrfToken::class)->

// Route::post("/login", "App\Http\Controllers\Api\AuthController@customerLogin");
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get("/profile", "App\Http\Controllers\Api\CustomerController@profile");
//     Route::post("/logout", "App\Http\Controllers\Api\AuthController@customerLogout");

// require __DIR__.'/api.php';
