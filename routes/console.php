<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ==========================================
// JADWAL OTOMATIS: GENERATE TIDAK HADIR
// ==========================================
Schedule::call(function () {
    $hariIni = Carbon::today()->format('Y-m-d');
    
    // Ambil semua data pegawai
    $pegawais = User::where('role', 'pegawai')->get();

    foreach ($pegawais as $pegawai) {
        // Cek apakah pegawai ini sudah punya data absen hari ini?
        $sudahAbsen = Attendance::where('user_id', $pegawai->id)
                                ->whereDate('date', $hariIni)
                                ->exists();
        
        // Jika TIDAK ADA data absen, masukkan sebagai "Tidak Hadir"
        if (!$sudahAbsen) {
            Attendance::create([
                'user_id' => $pegawai->id,
                'date' => $hariIni,
                'check_in' => null,
                'check_out' => null,
                'status' => 'Tidak Hadir', // Bisa diganti 'Alpa' jika mau
            ]);
        }
    }
})->dailyAt('23:50'); // Berjalan otomatis setiap jam 23:50 malam