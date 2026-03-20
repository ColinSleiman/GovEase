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
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;


Route::get('/', function () {
    return view('welcome');
});


Route::post('/register', [Register::class, '__invoke'])
        ->middleware('guest');

Route::post('/login', [Login::class, '__invoke'])
        ->middleware('guest');

Route::post('/logout', [Logout::class, '__invoke'])
        ->middleware('auth')
        ->name('logout');

Route::resource('municipalities', MunicipalityController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
Route::resource('offices', OfficeController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
Route::resource('service-categories', ServiceCategoryController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
Route::resource('services', ServiceController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
Route::resource('appointments', AppointmentController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);

Route::resource('statuses', StatusController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
Route::resource('requests', RequestController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
Route::resource('documents', DocumentController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);
Route::resource('payments', PaymentController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);

Route::resource('reviews', ReviewController::class)->only([
    'index',
    'store',
    'show',
    'update',
    'destroy',
]);

// pivot tables
Route::get('document-requests', [DocumentRequestController::class, 'index']);
Route::post('document-requests', [DocumentRequestController::class, 'store']);
Route::get('document-requests/{request}/{document}', [DocumentRequestController::class, 'show']);
Route::put('document-requests/{request}/{document}', [DocumentRequestController::class, 'update']);
Route::delete('document-requests/{request}/{document}', [DocumentRequestController::class, 'destroy']);

Route::get('user-requests', [UserRequestController::class, 'index']);
Route::post('user-requests', [UserRequestController::class, 'store']);
Route::get('user-requests/{user}/{request}', [UserRequestController::class, 'show']);
Route::put('user-requests/{user}/{request}', [UserRequestController::class, 'update']);
Route::delete('user-requests/{user}/{request}', [UserRequestController::class, 'destroy']);
