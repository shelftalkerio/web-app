<?php

use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Backend', 'prefix' => 'admin'], function () {
    Route::resource('companies', App\Http\Controllers\CompanyController::class);

    Route::resource('stores', App\Http\Controllers\StoreController::class);

    Route::resource('products', App\Http\Controllers\ProductController::class);

    Route::resource('modules', App\Http\Controllers\ModuleController::class);
});
