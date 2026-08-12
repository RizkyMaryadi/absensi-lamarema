<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Attendance;
use App\Models\PengajuanIzin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $pegawai = Auth::user();
        $hariIni = Carbon::today();

        // JALUR 1: JIKA YANG LOGIN ADALAH PEGAWAI
        if ($pegawai->role === 'pegawai') {
            $absensiHariIni = Attendance::where('pegawai_id', $pegawai->id)
                                ->whereDate('date', $hariIni)
                                ->get();

            $rekapAbsenPegawai = Attendance::where('pegawai_id', $pegawai->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

            $riwayatPengajuan = PengajuanIzin::where('pegawai_id', $pegawai->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return view('absensi.dashboard-pegawai', compact('absensiHariIni', 'rekapAbsenPegawai', 'riwayatPengajuan'));
        }

        // JALUR 2: JIKA YANG LOGIN ADALAH ADMIN
        $totalPegawai = Pegawai::where('role', 'pegawai')->count();
        $absensiHariIniAdmin = Attendance::whereDate('date', $hariIni)->get();
        $totalHadir = $absensiHariIniAdmin->count();
        $totalBelumHadir = $totalPegawai - $totalHadir;

        $pegawais = Pegawai::where('role', 'pegawai')
                        ->orderBy('name', 'asc') 
                        ->get();

        return view('absensi.dashboard', [
            'totalPegawai' => $totalPegawai,
            'totalHadir' => $totalHadir,
            'totalBelumHadir' => $totalBelumHadir,
            'absensiHariIni' => $absensiHariIniAdmin,
            'pegawais' => $pegawais 
        ]);
    }
}
