<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== LANDING PAGE =====
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ===== AUTHENTICATION ROUTES =====
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes (optional)
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');

// ===== ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Poli
    Route::get('/poli', [AdminController::class, 'indexPoli'])->name('poli.index');
    Route::post('/poli', [AdminController::class, 'storePoli'])->name('poli.store');
    Route::put('/poli/{id}', [AdminController::class, 'updatePoli'])->name('poli.update');
    Route::delete('/poli/{id}', [AdminController::class, 'destroyPoli'])->name('poli.destroy');
    
    // Manajemen Dokter
    Route::get('/dokter', [AdminController::class, 'indexDokter'])->name('dokter.index');
    Route::post('/dokter', [AdminController::class, 'storeDokter'])->name('dokter.store');
    Route::put('/dokter/{id}', [AdminController::class, 'updateDokter'])->name('dokter.update');
    Route::delete('/dokter/{id}', [AdminController::class, 'destroyDokter'])->name('dokter.destroy');
    
    // Manajemen Pasien
    Route::get('/pasien', [AdminController::class, 'indexPasien'])->name('pasien.index');
    Route::post('/pasien', [AdminController::class, 'storePasien'])->name('pasien.store');
    Route::put('/pasien/{id}', [AdminController::class, 'updatePasien'])->name('pasien.update');
    Route::delete('/pasien/{id}', [AdminController::class, 'destroyPasien'])->name('pasien.destroy');
    
    // Manajemen Obat
    Route::get('/obat', [AdminController::class, 'indexObat'])->name('obat.index');
    Route::post('/obat', [AdminController::class, 'storeObat'])->name('obat.store');
    Route::put('/obat/{id}', [AdminController::class, 'updateObat'])->name('obat.update');
    Route::delete('/obat/{id}', [AdminController::class, 'destroyObat'])->name('obat.destroy');
});

// ===== DOKTER ROUTES =====
Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'role:dokter'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dashboard');
    
    // Jadwal Periksa
    Route::get('/jadwal', [DokterController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal', [DokterController::class, 'jadwalStore'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [DokterController::class, 'jadwalUpdate'])->name('jadwal.update');
    Route::put('/jadwal/{id}/activate', [DokterController::class, 'jadwalActivate'])->name('jadwal.activate');
    
    // Periksa Pasien
    Route::get('/periksa', [DokterController::class, 'periksa'])->name('periksa');
    Route::get('/periksa/{daftarPoliId}/edit', [DokterController::class, 'periksaEdit'])->name('periksa.edit');
    Route::put('/periksa/{daftarPoliId}', [DokterController::class, 'periksaUpdate'])->name('periksa.update');
    Route::get('/periksa/{periksaId}/show', [DokterController::class, 'periksaShow'])->name('periksa.show');
    
    // Manajemen Obat
    Route::get('/obat', [DokterController::class, 'obat'])->name('obat');
    Route::post('/obat', [DokterController::class, 'obatStore'])->name('obat.store');
    Route::put('/obat/{id}', [DokterController::class, 'obatUpdate'])->name('obat.update');
    Route::delete('/obat/{id}', [DokterController::class, 'obatDestroy'])->name('obat.destroy');
    
    // Profil Dokter
    Route::get('/profil', [DokterController::class, 'profil'])->name('profil');
    Route::put('/profil', [DokterController::class, 'profilUpdate'])->name('profil.update');
    Route::put('/password', [DokterController::class, 'passwordUpdate'])->name('password.update');
});

// ===== PASIEN ROUTES =====
Route::prefix('pasien')->name('pasien.')->middleware(['auth', 'role:pasien'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PasienController::class, 'dashboard'])->name('dashboard');
    
    // Pendaftaran Periksa
    Route::get('/periksa', [PasienController::class, 'periksa'])->name('periksa');
    Route::post('/periksa', [PasienController::class, 'periksaStore'])->name('periksa.store');
    Route::delete('/periksa/{id}', [PasienController::class, 'periksaDestroy'])->name('periksa.destroy');
    
    // Riwayat Periksa
    Route::get('/riwayat', [PasienController::class, 'riwayat'])->name('riwayat');

    // Profil Pasien
    Route::get('/profil', [PasienController::class, 'profil'])->name('profil');
    Route::put('/profil', [PasienController::class, 'profilUpdate'])->name('profil.update');
    Route::put('/password', [PasienController::class, 'passwordUpdate'])->name('password.update');
});

// ===== REDIRECT ROUTES BERDASARKAN ROLE SETELAH LOGIN =====
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isDokter()) {
            return redirect()->route('dokter.dashboard');
        } elseif ($user->isPasien()) {
            return redirect()->route('pasien.dashboard');
        }
        
        return redirect('/');
    })->name('dashboard');
});

// ===== FALLBACK ROUTE =====
Route::fallback(function () {
    return view('errors.404');
});