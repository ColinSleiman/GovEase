<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('requests', RequestController::class);
Route::resource('statuses', StatusController::class);
Route::resource('payments', PaymentController::class);
Route::resource('document-requests', DocumentRequestController::class);
Route::resource('documents', DocumentController::class);
