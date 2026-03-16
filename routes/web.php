<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\Register;



Route::get('/', function () {
    return view('welcome');
});


Route::post('/register', [Register::class, '__invoke'])
        ->middleware('guest');

Route::post('/login', [Register::class, '__invoke'])
        ->middleware('guest');

Route::post('/logout', [Register::class, '__invoke'])
        ->middleware('auth')
        ->name('logout');

Route::resource('municipalities', MunicipalityController::class);
Route::resource('offices', OfficeController::class);
Route::resource('service-categories', ServiceCategoryController::class);
Route::resource('services', ServiceController::class);

Route::Resource('statuses', StatusController::class);
Route::Resource('requests', RequestController::class);
Route::Resource('documents', DocumentController::class);
Route::Resource('document-requests', DocumentRequestController::class);
Route::Resource('payments', PaymentController::class);

// Note: Any custom method you create will not be automatically called by resource (only CRUD methods).
// To use it, you must define a separate route.
