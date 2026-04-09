<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\Register;

Route::get('/', function () { return view('layouts.app', ['title' => 'Home']); });

// auth routes
Route::post('/register', [Register::class, '__invoke'])->middleware('guest');
Route::post('/login', [Register::class, '__invoke'])->middleware('guest');
Route::post('/logout', [Register::class, '__invoke'])->middleware('auth')->name('logout');

// resources routes
Route::resource('municipalities', MunicipalityController::class);
Route::resource('offices', OfficeController::class);
Route::resource('service-categories', ServiceCategoryController::class);
Route::resource('services', ServiceController::class);

Route::resource('statuses', StatusController::class);
Route::resource('requests', RequestController::class);
Route::resource('documents', DocumentController::class);
Route::resource('document-requests', DocumentRequestController::class);
Route::resource('payments', PaymentController::class);

// admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/offices', [AdminController::class, 'offices'])->name('offices.index');
    Route::get('/offices/create', [AdminController::class, 'officesCreate'])->name('offices.create');

    Route::get('/municipalities', [AdminController::class, 'municipalities'])->name('municipalities.index');
    Route::get('/municipalities/create', [AdminController::class, 'municipalitiesCreate'])->name('municipalities.create');

    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('users.create');

    Route::get('/requests', [AdminController::class, 'requests'])->name('requests.index');
    Route::get('/services/monitor', [AdminController::class, 'servicesMonitor'])->name('services.monitor');
    Route::get('/reports/office-requests', [AdminController::class, 'reportsOfficeRequests'])->name('reports.office-requests');
    Route::get('/reports/revenue', [AdminController::class, 'reportsRevenue'])->name('reports.revenue');

    Route::get('/services', [AdminController::class, 'services'])->name('services.index');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications.index');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs.index');
    Route::get('/help', [AdminController::class, 'help'])->name('help.index');
});
