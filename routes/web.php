<?php

use App\Http\Controllers\Web\BrandController;
use App\Http\Controllers\Web\ProductController;
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
    return ['message' => "Server is running"];
});
Route::prefix('brands')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('web.brands.index');
    Route::get('/{id}', [BrandController::class, 'show'])->name('web.brand.show');
});
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('web.product.index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('web.product.show');
});
Route::get('/search', [ProductController::class, 'search'])->name('web.product.search');
// Route::prefix('specs-types')->group(function () {
//     Route::get('/', [SpecsTypeController::class, 'index']);
//     Route::get('/{id}', [SpecsTypeController::class, 'show']);
// });

