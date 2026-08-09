<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Pegawai;

class RekapAbsensiController extends Controller
{
    public function rekap(Request $request)
    {
        $filter_type = $request->input('filter_type', 'harian');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = $request->input('bulan', date('Y-m'));
        $search = $request->input('search'); 

        $query = Attendance::with('pegawai');

        if ($filter_type === 'bulanan') {
            $query->where('date', 'like', $bulan . '%');
        } else {
            $query->whereDate('date', $tanggal);
        }

        $absensi = $query->when($search, function ($query, $search) {
                return $query->whereHas('pegawai', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get(); 

        return view('absensi.rekap', compact('absensi', 'filter_type', 'tanggal', 'bulan'));
    }

    public function exportExcel(Request $request)
    {
        $filter_type = $request->input('filter_type', 'harian');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = $request->input('bulan', date('Y-m'));
        
        $query = Attendance::with('pegawai');

        if ($filter_type === 'bulanan') {
            $query->where('date', 'like', $bulan . '%');
            $fileName = "Rekap_Absensi_Lamarema_Bulan_{$bulan}.csv";
        } else {
            $query->whereDate('date', $tanggal);
            $fileName = "Rekap_Absensi_Lamarema_Tanggal_{$tanggal}.csv";
        }

        $absensi = $query->orderBy('date', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Nama Pegawai', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status');

        $callback = function() use($absensi, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($file, $columns, ';');

            $no = 1;
            foreach ($absensi as $row) {
                fputcsv($file, array(
                    $no++,
                    $row->pegawai->name ?? 'Tidak Diketahui',
                    date('d-m-Y', strtotime($row->date)),
                    $row->check_in,
                    $row->check_out ?? 'Belum Keluar',
                    $row->status
                ), ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function hapusAbsensi($id)
    {
        $absen = Attendance::findOrFail($id);
        $absen->delete();
        return redirect()->back()->with('success', 'Data absensi berhasil dihapus.');
    }

    public function tandaiTidakHadir()
    {
        $hariIni = \Carbon\Carbon::today()->format('Y-m-d');
        $pegawais = Pegawai::where('role', 'pegawai')->get();
        $jumlahAlpa = 0;

        foreach ($pegawais as $pegawai) {
            $sudahAbsen = Attendance::where('pegawai_id', $pegawai->id)
                                    ->whereDate('date', $hariIni)
                                    ->exists();
            
            if (!$sudahAbsen) {
                Attendance::create([
                    'pegawai_id'   => $pegawai->id,
                    'date'      => $hariIni,
                    'check_in'  => '00:00:00', 
                    'check_out' => '00:00:00', 
                    'status'    => 'Tidak Hadir',
                ]);
                $jumlahAlpa++;
            }
        }

        return redirect()->back()->with('success', "Absensi ditutup! $jumlahAlpa pegawai ditandai Tidak Hadir.");
    }

    public function inputIzinSakitAdmin(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'date'    => 'required|date',
            'status'  => 'required|in:Izin,Sakit',
        ]);

        $absenExisting = Attendance::where('pegawai_id', $request->pegawai_id)
                                  ->whereDate('date', $request->date)
                                  ->first();

        date_default_timezone_set('Asia/Jakarta');

        if ($absenExisting) {
            $absenExisting->update([
                'status'    => $request->status,
                'check_out' => date('H:i:s'), 
            ]);

            return redirect()->back()->with('success', "Data diperbarui! Pegawai yang sudah masuk tadi pagi, kini ditandai pulang sebagai {$request->status}.");
        } else {
            Attendance::create([
                'pegawai_id'   => $request->pegawai_id,
                'date'      => $request->date,
                'check_in'  => '00:00:00', 
                'check_out' => '00:00:00',
                'status'    => $request->status, 
            ]);

            return redirect()->back()->with('success', "Sukses! Pegawai ditandai absen {$request->status} seharian penuh.");
        }
    }

    public function inputManual()
    {
        $pegawais = Pegawai::where('role', 'pegawai')->orderBy('name', 'asc')->get();
        return view('absensi.input-manual', compact('pegawais'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'date'       => 'required|date',
            'check_in'   => 'required|date_format:H:i',
            'check_out'  => 'nullable|date_format:H:i',
            'status'     => 'required|string',
        ]);

        $checkIn = $request->check_in . ':00';
        $checkOut = $request->check_out ? $request->check_out . ':00' : null;

        $existing = Attendance::where('pegawai_id', $request->pegawai_id)
                              ->whereDate('date', $request->date)
                              ->first();
        
        date_default_timezone_set('Asia/Jakarta');

        if ($existing) {
            $existing->update([
                'check_in'  => $checkIn,
                'check_out' => $checkOut,
                'status'    => $request->status,
            ]);
            return redirect()->back()->with('success', 'Data absensi manual berhasil diperbarui!');
        }

        Attendance::create([
            'pegawai_id' => $request->pegawai_id,
            'date'       => $request->date,
            'check_in'   => $checkIn,
            'check_out'  => $checkOut,
            'status'     => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data absensi manual berhasil ditambahkan!');
    }
}
