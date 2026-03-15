<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('municipalities', MunicipalityController::class);
Route::resource('offices', OfficeController::class);
Route::resource('service-categories', ServiceCategoryController::class);
Route::resource('services', ServiceController::class);

// Note: Any custom method you create will not be automatically called by resource (only CRUD methods).
// To use it, you must define a separate route.
