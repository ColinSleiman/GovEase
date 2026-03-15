<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;


Route::get('/', function () {
    return view('welcome');
});


Route::post('/register', [App\Http\Controllers\Auth\Register::class, '__invoke'])
        ->middleware('guest');

Route::post('/login', [App\Http\Controllers\Auth\Login::class, '__invoke'])
        ->middleware('guest');
        
Route::post('/logout', [App\Http\Controllers\Auth\Logout::class, '__invoke'])
        ->middleware('auth')
        ->name('logout');
Route::resource('municipalities', MunicipalityController::class);
Route::resource('offices', OfficeController::class);
Route::resource('service-categories', ServiceCategoryController::class);
Route::resource('services', ServiceController::class);

// Note: Any custom method you create will not be automatically called by resource (only CRUD methods).
// To use it, you must define a separate route.
