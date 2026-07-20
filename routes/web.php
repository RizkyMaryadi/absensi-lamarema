<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\TelegramController;

/*
|--------------------------------------------------------------------------
| Web Routes - Absensi Lamarema Fashion
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. RUTE TAMU (Belum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    // --- DIUBAH: SEKARANG MENGGUNAKAN JALUR WEB/EMAIL AGAR TABEL TOKEN TERISI ---
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email'); 
    
    // --- RUTE RESET PASSWORD (Setelah user klik link token dari web/email) ---
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


// ==========================================
// 2. RUTE ADMIN (Wajib Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Dashboard Admin
    Route::get('/', [AbsensiController::class, 'dashboard'])->name('dashboard'); 

    // Rekap Absensi
    Route::get('/data-absen', [AbsensiController::class, 'rekap'])->name('rekap');
    Route::delete('/hapus-absen/{id}', [AbsensiController::class, 'hapusAbsensi'])->name('absen.hapus');

    // Manajemen Profil Admin
    Route::get('/profil', [AbsensiController::class, 'profil'])->name('profil');
    Route::post('/profil/update', [AbsensiController::class, 'profilUpdate'])->name('profil.update');

    Route::post('/generate-tidak-hadir', [AbsensiController::class, 'tandaiTidakHadir'])->name('generate.alpa');
    
    // -------------------------------------------------------------------------
    // INPUT IZIN / SAKIT MANUAL OLEH ADMIN
    // -------------------------------------------------------------------------
    Route::post('/input-izin-sakit', [AbsensiController::class, 'inputIzinSakitAdmin'])->name('admin.input_izin_sakit');
    // -------------------------------------------------------------------------

    // ==========================================
    // MANAJEMEN PEGAWAI
    // ==========================================
    Route::get('/tambah-pegawai', [AbsensiController::class, 'tambahPegawai'])->name('pegawai.tambah'); 
    Route::post('/tambah-pegawai', [AbsensiController::class, 'simpanPegawai']); 
    Route::get('/data-pegawai', [AbsensiController::class, 'dataPegawai']);

    Route::delete('/hapus-pegawai/{id}', [AbsensiController::class, 'hapusPegawai'])->name('pegawai.Dynamic'); 
    Route::get('/daftar-wajah/{id}', [AbsensiController::class, 'daftar'])->name('pegawai.daftar');

    // Edit Pegawai sudah diubah menggunakan AbsensiController
    Route::get('/pegawai/{id}/edit', [AbsensiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{id}', [AbsensiController::class, 'update'])->name('pegawai.update');

    // Export Laporan
    Route::get('/export-excel', [AbsensiController::class, 'exportExcel'])->name('export.excel');

    // ==========================================
    // PENGATURAN SISTEM
    // ==========================================
    Route::get('/pengaturan', [AbsensiController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [AbsensiController::class, 'simpanPengaturan'])->name('pengaturan.simpan');

    // Memproses Logout
    Route::match(['get', 'post'], '/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});


// ==========================================
// 3. RUTE KIOSK / MESIN ABSEN (Tanpa Login)
// ==========================================
Route::get('/scan', [AbsensiController::class, 'index'])->name('scan');
Route::get('/get-pegawai', [AbsensiController::class, 'getPegawai']);
Route::post('/catat-kehadiran', [AbsensiController::class, 'catatKehadiran']);
Route::post('/simpan-wajah', [AbsensiController::class, 'simpanWajah']);


// ==========================================
// 4. RUTE BOT TELEGRAM (Tanpa Login / Publik)
// ==========================================
Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);