<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::resource('obat', ObatController::class);
Route::resource('pasien', PasienController::class);
Route::resource('dashboard', DashboardController::class);
