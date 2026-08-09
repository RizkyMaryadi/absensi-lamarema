<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PengajuanIzinController;

// Controllers Baru
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekapAbsensiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\KioskController;

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
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard'); 

    // Rekap Absensi
    Route::get('/data-absen', [RekapAbsensiController::class, 'rekap'])->name('rekap');
    Route::delete('/hapus-absen/{id}', [RekapAbsensiController::class, 'hapusAbsensi'])->name('absen.hapus');

    // Manajemen Profil Admin
    Route::get('/profil', [ProfilController::class, 'profil'])->name('profil');
    Route::post('/profil/update', [ProfilController::class, 'profilUpdate'])->name('profil.update');

    Route::post('/generate-tidak-hadir', [RekapAbsensiController::class, 'tandaiTidakHadir'])->name('generate.alpa');
    
    // -------------------------------------------------------------------------
    // INPUT IZIN / SAKIT MANUAL OLEH ADMIN
    // -------------------------------------------------------------------------
    Route::post('/input-izin-sakit', [RekapAbsensiController::class, 'inputIzinSakitAdmin'])->name('admin.input_izin_sakit');
    
    // -------------------------------------------------------------------------
    // INPUT ABSEN MANUAL (Mati Listrik / Error)
    // -------------------------------------------------------------------------
    Route::get('/input-absen-manual', [RekapAbsensiController::class, 'inputManual'])->name('admin.input_manual');
    Route::post('/input-absen-manual', [RekapAbsensiController::class, 'storeManual'])->name('admin.store_manual');
    // -------------------------------------------------------------------------

    // ==========================================
    // PENGAJUAN IZIN / CUTI
    // ==========================================
    Route::post('/pengajuan-izin', [PengajuanIzinController::class, 'store'])->name('pengajuan.store');
    Route::get('/laporan-pengajuan', [PengajuanIzinController::class, 'index'])->name('pengajuan.index');
    Route::post('/laporan-pengajuan/{id}/status', [PengajuanIzinController::class, 'updateStatus'])->name('pengajuan.status');

    // ==========================================
    // MANAJEMEN PEGAWAI & JABATAN
    // ==========================================
    Route::post('/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    
    Route::get('/tambah-pegawai', [PegawaiController::class, 'tambahPegawai'])->name('pegawai.tambah'); 
    Route::post('/tambah-pegawai', [PegawaiController::class, 'simpanPegawai']); 
    Route::get('/data-pegawai', [PegawaiController::class, 'dataPegawai']);

    Route::delete('/hapus-pegawai/{id}', [PegawaiController::class, 'hapusPegawai'])->name('pegawai.Dynamic'); 
    Route::get('/daftar-wajah/{id}', [PegawaiController::class, 'daftar'])->name('pegawai.daftar');

    // Edit Pegawai
    Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');

    // Export Laporan
    Route::get('/export-excel', [RekapAbsensiController::class, 'exportExcel'])->name('export.excel');

    // ==========================================
    // PENGATURAN SISTEM
    // ==========================================
    Route::get('/pengaturan', [PengaturanController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [PengaturanController::class, 'simpanPengaturan'])->name('pengaturan.simpan');

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
Route::get('/scan', [KioskController::class, 'index'])->name('scan');
Route::get('/get-pegawai', [KioskController::class, 'getPegawai']);
Route::post('/catat-kehadiran', [KioskController::class, 'catatKehadiran']);
Route::post('/simpan-wajah', [PegawaiController::class, 'simpanWajah']);


// ==========================================
// 4. RUTE BOT TELEGRAM (Tanpa Login / Publik)
// ==========================================
Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);