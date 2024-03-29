<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
    // Route::match(['get', 'post'], 'login', 'authenticate')->name('login');
    Route::post('reset', 'reset')->name('reset');
    // Route::get('logout', 'logout')->name('logout');
});

Route::controller(ProductController::class)->prefix('product')->name('product.')->group(function () {
    Route::get('query', 'query')->name('query');
    Route::get('/{code}', 'getDataById')->name('getDataById');
    Route::delete('delete/{id}', 'destroy')->name('delete');
});

Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
    Route::get('query', 'query')->name('query');
    Route::delete('delete/{id}', 'destroy')->name('delete');
});

Route::controller(TransactionReportController::class)->prefix('report')->name('report.')->group(function () {
    Route::get('query', 'query')->name('query');
    Route::get('chart-data', 'chartData')->name('chart-data');
    Route::post('delete', 'destroy')->name('delete');
});

Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function () {
    Route::get('query', 'query')->name('query');
    Route::delete('delete/{id}', 'destroy')->name('delete');
    Route::get('/detail-product/{id}', 'getDetailProduct')->name('detail-product');
});
