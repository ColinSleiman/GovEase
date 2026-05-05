<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OTPController;

// Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MunicipalityController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\UserController;

// Public
use App\Http\Controllers\Public\DocumentController;
use App\Http\Controllers\Public\DocumentRequestController;
use App\Http\Controllers\Public\MessageController;
use App\Http\Controllers\Public\PaymentController;
use App\Http\Controllers\Public\RequestController;
use App\Http\Controllers\Public\ReviewController;


Route::get('/', function () { return view('layouts.app', ['title' => 'Home']); })->name('home');
Route::get('/portal-access', function () { return view('auth.portal', ['title' => 'Portal Access']); })->middleware('guest')->name('portal.access');


// auth routes
Route::post('/register', [RegisterController::class, '__invoke'])->name('register');
Route::post('/login', [LoginController::class, '__invoke'])->middleware('guest')->name('login');
Route::middleware(['check.auth'])->group(function () {
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');

    Route::get('/verify-otp', [OTPController::class, 'show'])->name('otp.show');
    Route::post('/otp/send', [OTPController::class, 'send'])->name('otp.send');
    Route::post('/otp/verify', [OTPController::class, 'verify'])->name('otp.verify');
});

Route::get('/api/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/api/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');


// resources routes
Route::resource('service-categories', ServiceCategoryController::class);
Route::resource('services', ServiceController::class);

Route::resource('statuses', StatusController::class);
Route::resource('requests', RequestController::class);
Route::resource('documents', DocumentController::class);
Route::resource('document-requests', DocumentRequestController::class);
Route::resource('payments', PaymentController::class);


// admin routes
Route::middleware(['check.auth', 'check.admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('offices', OfficeController::class);
        Route::resource('municipalities', MunicipalityController::class);
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

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
