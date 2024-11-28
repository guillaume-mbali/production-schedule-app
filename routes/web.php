<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\HomeController;

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
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('home', HomeController::class);
    Route::resource('order', OrderController::class);
    Route::resource('client', ClientController::class);
    Route::resource('productType', ProductTypeController::class);
    Route::resource('product', ProductController::class);

    Route::get('order/stepper/step1', [OrderController::class, 'createStepOne'])->name('order.stepper.step1');
    Route::post('order/stepper/step1', [OrderController::class, 'postCreateStepOne'])->name('order.stepper.postStepOne');
    Route::get('order/stepper/step2', [OrderController::class, 'createStepTwo'])->name('order.stepper.step2');
    Route::post('order/stepper/step2', [OrderController::class, 'postCreateStepTwo'])->name('order.stepper.postStepTwo');
});
