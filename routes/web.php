<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserRequestController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReviewController;
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
Route::resource('appointments', AppointmentController::class);

Route::Resource('statuses', StatusController::class);
Route::Resource('requests', RequestController::class);
Route::Resource('documents', DocumentController::class);
Route::Resource('payments', PaymentController::class);
Route::Resource('review', ReviewController::class);

// pivot tables
Route::Resource('document-requests', DocumentRequestController::class);
Route::Resource('user-requests', UserRequestController::class);
