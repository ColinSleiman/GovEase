<?php

use Illuminate\Support\Facades\Route;

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