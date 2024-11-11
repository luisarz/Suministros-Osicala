<?php

use App\Http\Controllers\DTEController;
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
Route::get('/senDTE/{idVenta}', [DTEController::class, 'generarDTE'])->middleware(['auth'])->name('sendDTE');
Route::get('/sendAnularDTE/{idVenta}', [DTEController::class, 'anularDTE'])->middleware(['auth'])->name('sendAnularDTE');
Route::get('/printDTE/{idVenta}', [DTEController::class, 'printDTE'])->middleware(['auth'])->name('printDTE');
require __DIR__.'/auth.php';
