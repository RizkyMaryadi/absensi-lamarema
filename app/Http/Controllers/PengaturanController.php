<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class PengaturanController extends Controller
{
    public function pengaturan()
    {
        $batas_waktu = Setting::where('key', 'batas_waktu_absen')->first()->value ?? '08:00';
        $toleransi   = Setting::where('key', 'toleransi_menit')->first()->value ?? 0;
        $jam_pulang  = Setting::where('key', 'jam_pulang')->first()->value ?? '17:00'; 
        $batas_akhir_pulang = Setting::where('key', 'batas_akhir_pulang')->first()->value ?? '19:00';
        
        return view('absensi.pengaturan', compact('batas_waktu', 'toleransi', 'jam_pulang', 'batas_akhir_pulang'));
    }

    public function simpanPengaturan(Request $request)
    {
        Setting::updateOrCreate(['key' => 'batas_waktu_absen'], ['value' => $request->batas_waktu]);
        Setting::updateOrCreate(['key' => 'toleransi_menit'], ['value' => $request->toleransi]);
        Setting::updateOrCreate(['key' => 'jam_pulang'], ['value' => $request->jam_pulang]);
        Setting::updateOrCreate(['key' => 'batas_akhir_pulang'], ['value' => $request->batas_akhir_pulang]);

        Artisan::call('optimize:clear');

        return redirect()->back()->with('success', 'Pengaturan Lamarema Fashion Berhasil Diperbarui.');
    }
}
