<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::view('/makes', 'vehicles.makes')->name('makes');
        Route::view('/models', 'vehicles.models')->name('models');
        Route::view('/generations', 'vehicles.generations')->name('generations');
        Route::view('/engines', 'vehicles.engines')->name('engines');
        Route::view('/transmissions', 'vehicles.transmissions')->name('transmissions');
        Route::view('/variants', 'vehicles.variants')->name('variants');
        Route::get('/variants/{variant}', \App\Livewire\Vehicles\VehicleVariantShow::class)->name('variants.show');
        Route::view('/sources', 'vehicles.sources')->name('sources');
    });
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
