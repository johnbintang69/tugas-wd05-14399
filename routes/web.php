<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Autentikasi
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes Pasien
Route::prefix('pasien')->middleware(['auth', 'role:pasien'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PasienController::class, 'dashboard'])->name('pasien.dashboard');
    
    // Periksa
    Route::get('/periksa', [PasienController::class, 'periksa'])->name('pasien.periksa');
    Route::post('/periksa', [PasienController::class, 'periksaStore'])->name('pasien.periksa.store');
    Route::delete('/periksa/{id}', [PasienController::class, 'periksaDestroy'])->name('pasien.periksa.destroy');
    
    // Riwayat
    Route::get('/riwayat', [PasienController::class, 'riwayat'])->name('pasien.riwayat');
});

// Routes Dokter
Route::prefix('dokter')->middleware(['auth', 'role:dokter'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dokter.dashboard');
    
    // Periksa
    Route::get('/periksa', [DokterController::class, 'periksa'])->name('dokter.periksa');
    Route::get('/periksa/{id}/edit', [DokterController::class, 'periksaEdit'])->name('dokter.periksa.edit');
    Route::put('/periksa/{id}', [DokterController::class, 'periksaUpdate'])->name('dokter.periksa.update');
    Route::get('/periksa/{id}', [DokterController::class, 'periksaShow'])->name('dokter.periksa.show');
    
    // Obat
    Route::get('/obat', [DokterController::class, 'obat'])->name('dokter.obat');
    Route::post('/obat', [DokterController::class, 'obatStore'])->name('dokter.obat.store');
    Route::put('/obat/{id}', [DokterController::class, 'obatUpdate'])->name('dokter.obat.update');
    Route::delete('/obat/{id}', [DokterController::class, 'obatDestroy'])->name('dokter.obat.destroy');
});

// Middleware untuk redirect berdasarkan role
Route::get('/home', function() {
    if (auth()->check()) {
        if (auth()->user()->isDokter()) {
            return redirect()->route('dokter.dashboard');
        } else {
            return redirect()->route('pasien.dashboard');
        }
    }
    
    return redirect()->route('login');
})->name('home');