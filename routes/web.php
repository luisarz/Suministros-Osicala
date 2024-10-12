<?php

use App\Http\Controllers\hoja;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/ejecutar', [hoja::class, 'ejecutar']);

require __DIR__.'/auth.php';
