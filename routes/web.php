<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('auth.login'));
});

Route::controller(AuthController::class)->name('auth.')->group(function () {
    Route::match(['get', 'post'], 'login', 'authenticate')->name('login');
    Route::get('logout', 'logout')->name('logout');
});

Route::controller(SaleController::class)->prefix('sale')->name('sale.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::get('/{sale?}', 'show')->name('show');
});

Route::controller(ProductController::class)->prefix('product')->name('product.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('import', 'importExcel')->name('import');
    Route::match(['get', 'post'], 'store', 'store')->name('store');
    Route::match(['get', 'post'], '{id}', 'update')->name('update');
});

Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('store', 'store')->name('store');
    Route::match(['get', 'post'], '{id}', 'update')->name('update');
});

Route::controller(TransactionReportController::class)->prefix('transaction')->name('transaction.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/download', 'downloadPage')->name('download');
    Route::get('/download/pdf', 'generatePdf')->name('pdf');
    Route::post('store', 'store')->name('store');
    Route::match(['get', 'post'], '{id}', 'update')->name('update');
});

Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::match(['get', 'post'], 'store', 'store')->name('store');
        Route::match(['get', 'post'], '{nip}', 'update')->name('update');
    });
});
