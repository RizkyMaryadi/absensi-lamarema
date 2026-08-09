<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanIzin;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanIzinController extends Controller
{
    // === METHOD UNTUK PEGAWAI ===
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:Izin,Sakit,Cuti',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $pengajuan = new PengajuanIzin();
        $pengajuan->pegawai_id = Auth::id();
        $pengajuan->jenis = $request->jenis;
        $pengajuan->tanggal_mulai = $request->tanggal_mulai;
        $pengajuan->tanggal_selesai = $request->tanggal_selesai;
        $pengajuan->alasan = $request->alasan;
        $pengajuan->status = 'Menunggu';

        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_izin', 'public');
            $pengajuan->bukti_foto = $path;
        }

        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim dan sedang menunggu persetujuan.');
    }

    // === METHOD UNTUK ADMIN ===
    public function index()
    {
        $pengajuan = PengajuanIzin::with('pegawai')->latest()->get();
        return view('absensi.pengajuan', compact('pengajuan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $pengajuan = PengajuanIzin::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        // Jika disetujui, otomatis generate absen (optional sesuai logika skripsi yang disarankan)
        if ($request->status === 'Disetujui') {
            $start = Carbon::parse($pengajuan->tanggal_mulai);
            $end = Carbon::parse($pengajuan->tanggal_selesai);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                // Lewati weekend jika perlu (opsional, untuk sementara masukkan semua)
                Attendance::updateOrCreate(
                    [
                        'pegawai_id' => $pengajuan->pegawai_id,
                        'date' => $date->format('Y-m-d')
                    ],
                    [
                        'status' => $pengajuan->jenis,
                        'check_in' => null,
                        'check_out' => null,
                        'note' => $pengajuan->alasan
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Status pengajuan berhasil diupdate!');
    }
}
