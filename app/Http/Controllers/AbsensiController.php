<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log;  
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Artisan;
use Exception;

class AbsensiController extends Controller
{
    // ==========================================
    // 1. DASHBOARD, PROFIL & MANAJEMEN PEGAWAI
    // ==========================================

    public function dashboard()
    {
        $user = auth()->user();
        $hariIni = \Carbon\Carbon::today();

        // JALUR 1: JIKA YANG LOGIN ADALAH PEGAWAI
        if ($user->role === 'pegawai') {
            $absensiHariIni = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $hariIni)
                                ->get();

            $rekapAbsenPegawai = Attendance::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return view('absensi.dashboard-pegawai', compact('absensiHariIni', 'rekapAbsenPegawai'));
        }

        // JALUR 2: JIKA YANG LOGIN ADALAH ADMIN
        $totalPegawai = \App\Models\User::where('role', 'pegawai')->count();
        $absensiHariIniAdmin = Attendance::whereDate('created_at', $hariIni)->get();
        $totalHadir = $absensiHariIniAdmin->count();
        $totalBelumHadir = $totalPegawai - $totalHadir;

        $pegawais = \App\Models\User::where('role', 'pegawai')
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

    public function profil()
    {
        $user = Auth::user();
        return view('absensi.profil', compact('user'));
    }

    public function profilUpdate(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', 
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = $request->password; //  Benar

        }

        $user->save();
        
        return redirect()->back()->with('success', 'Profil dan Password Anda berhasil diperbarui!');
    }

    public function tambahPegawai()
    {
        return view('absensi.tambah');
    }

    public function simpanPegawai(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'position' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:20|unique:users,phone_number', 
        ]);

        $passwordAcak = Str::random(10); 

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position, 
            'role' => 'pegawai',
            'password' => $passwordAcak,
            'face_descriptor' => null,
            'phone_number' => $request->phone_number, 
            'telegram_chat_id' => null, 
        ]);

        return redirect()->route('pegawai.daftar', $user->id)
                         ->with('success', 'Profil pegawai berhasil dibuat!')
                         ->with('passwordBawaan', $passwordAcak); 
    }
          
    public function hapusPegawai($id)
    {
        $user = User::find($id);
        if ($user) {
            Attendance::where('user_id', $id)->delete();
            $user->delete();
            
            // Mengarahkan kembali halaman ke halaman daftar pegawai dengan pesan sukses
            return redirect()->back()->with('success', 'Pegawai berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Pegawai tidak ditemukan.');
    }

    public function infoPegawai($id)
    {
        $pegawai = User::findOrFail($id);
        return view('absensi.detail-info', compact('pegawai'));
    }

    public function edit($id)
    {
        $pegawai = User::findOrFail($id);
        return view('absensi.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $id,
            'position'     => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $id,
            'password'     => 'nullable|string|min:6', 
        ]);

        $pegawai = User::findOrFail($id);
        $pegawai->name = $request->name;
        $pegawai->email = $request->email;
        $pegawai->position = $request->position;
        $pegawai->phone_number = $request->phone_number;

            if ($request->filled('password')) {
        $pegawai->password = $request->password; 
        }

        $pegawai->save();

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    // ==========================================
    // 2. MESIN ABSEN / SCAN (KIOSK)
    // ==========================================

    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'pegawai') {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Anda hanya boleh melakukan absen menggunakan mesin kamera yang ada di kantor.');
        }

        return view('absensi.scan');
    }

    public function getPegawai()
    {
        $users = User::whereNotNull('face_descriptor')
                     ->select('id', 'name', 'face_descriptor')
                     ->get();
        return response()->json($users);
    }

    public function catatKehadiran(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        try {
            $user = User::findOrFail($request->user_id);
            date_default_timezone_set('Asia/Jakarta');
            
            $today = date('Y-m-d');
            $jamSekarangFull = date('H:i:s');
            $jamMenitSekarang = date('H:i'); 

            $absencesHariIni = Attendance::where('user_id', $user->id)
                                      ->whereDate('date', $today)
                                      ->first();

            // -------------------------------------------------------------------------
            // LANGKAH A: PENGUNCI MESIN ABSEN]
            // Jika Admin sudah input Izin/Sakit/Tidak Hadir seharian penuh, tolak scan!
            // -------------------------------------------------------------------------
            if ($absencesHariIni && in_array($absencesHariIni->status, ['Izin', 'Sakit', 'Tidak Hadir'])) {
                return response()->json([
                    'status' => 'error', 
                    'message' => "❌ DITOLAK! {$user->name} tidak bisa scan. Status hari ini sudah dikunci sebagai: *{$absencesHariIni->status}*."
                ]);
            }
            // -------------------------------------------------------------------------

            $settingBatas = Setting::where('key', 'batas_waktu_absen')->first()->value ?? '08:00';
            $menitToleransi = Setting::where('key', 'toleransi_menit')->first()->value ?? 0;
            $jamPulangNormal = Setting::where('key', 'jam_pulang')->first()->value ?? '17:00';

            // --- A. LOGIKA ABSEN MASUK ---
            if (!$absencesHariIni) {
                $timeStampMasukNormal = strtotime($today . ' ' . $settingBatas . ':00');
                $timeStampBukaAbsen = $timeStampMasukNormal - 600; 
                
                if ($jamSekarangFull < date('H:i:s', $timeStampBukaAbsen)) {
                    return response()->json([
                        'status' => 'error', 
                        'message' => "Terlalu Pagi! Absen dibuka pukul " . date('H:i', $timeStampBukaAbsen)
                    ]);
                }
                
                $waktuBatasAkhir = $timeStampMasukNormal + ((int)$menitToleransi * 60) + 59;
                $statusKehadiran = ($jamSekarangFull <= date('H:i:s', $waktuBatasAkhir)) ? 'Tepat Waktu' : 'Terlambat';

                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'check_in' => $jamSekarangFull,
                    'status' => $statusKehadiran,
                ]);

                $this->sendTelegramNotification($user, $attendance, 'masuk');
                return response()->json(['status' => 'success', 'message' => "Sukses MASUK! {$user->name} tercatat {$statusKehadiran}."]);
            }

            // --- B. LOGIKA ABSEN KELUAR ---
            if ($absencesHariIni && !$absencesHariIni->check_out) {
                if ($jamMenitSekarang < $jamPulangNormal) {
                    return response()->json([
                        'status' => 'error', 
                        'message' => "❌ DITOLAK! Belum jam pulang. Minimal pukul {$jamPulangNormal}. Sekarang: {$jamMenitSekarang}."
                    ]);
                }

                $absencesHariIni->update(['check_out' => $jamSekarangFull]);
                $absencesHariIni->refresh(); 

                $this->sendTelegramNotification($user, $absencesHariIni, 'keluar');
                return response()->json(['status' => 'success', 'message' => "Sukses KELUAR! Selamat istirahat {$user->name}."]);
            }

            return response()->json(['status' => 'error', 'message' => "Anda sudah absen lengkap hari ini!"]);

        } catch (Exception $e) {
            Log::error("Sistem Absensi Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => "Sistem Error!"], 500);
        }
    }

    private function sendTelegramNotification(User $user, Attendance $attendance, string $tipe)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken || !$user->telegram_chat_id) return;

        date_default_timezone_set('Asia/Jakarta');
        $tanggalStr = Carbon::parse($attendance->date)->translatedFormat('l, d F Y');
        
        if ($tipe === 'masuk') {
            $iconStatus = $attendance->status === 'Terlambat' ? '🔴' : '🟢';
            $txt = "🔔 *LAPORAN ABSENSI MASUK*\n\n"
                 . "👤 *Pegawai:* {$user->name}\n"
                 . "📅 *Tanggal:* {$tanggalStr}\n"
                 . "🕒 *Jam:* " . date('H:i:s', strtotime($attendance->check_in)) . " WIB\n"
                 . "📊 *Status:* {$iconStatus} *{$attendance->status}*\n\n"
                 . "Selamat bekerja dan tetap semangat! 💪";
        } else {
            $txt = "🔕 *LAPORAN ABSENSI KELUAR*\n\n"
                 . "👤 *Pegawai:* {$user->name}\n"
                 . "📅 *Tanggal:* {$tanggalStr}\n"
                 . "🕒 *Jam:* " . date('H:i:s', strtotime($attendance->check_out)) . " WIB\n\n"
                 . "Terima kasih atas kerja keras Anda hari ini. Selamat istirahat! 👋";
        }

        try {
            Http::withoutVerifying()->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $user->telegram_chat_id,
                'text' => $txt,
                'parse_mode' => 'Markdown',
            ]);
        } catch (Exception $e) {
            Log::error("Telegram Error: " . $e->getMessage());
        }
    }

    // ==========================================
    // 3. REGISTRASI WAJAH & PENGATURAN
    // ==========================================

    public function daftar($id)
    {
        $user = User::findOrFail($id);
        return view('absensi.daftar', compact('user'));
    }

    public function simpanWajah(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', 
            'face_descriptor' => 'required',
            'telegram_chat_id' => 'nullable|numeric|unique:users,telegram_chat_id,' . $request->user_id,
            'photo' => 'nullable|string'
        ]);

        $user = User::find($request->user_id);
        $user->face_descriptor = $request->face_descriptor;
        if ($request->filled('telegram_chat_id')) {
            $user->telegram_chat_id = $request->telegram_chat_id; 
        }

        if ($request->filled('photo')) {
            $imageData = $request->photo;
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'pegawai_' . $user->id . '_' . time() . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put('photos/' . $imageName, base64_decode($imageData));
            $user->photo = $imageName;
        }

        $user->save();
        return response()->json(['status' => 'success', 'message' => "Data wajah disimpan!"]);
    }

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

    public function rekap(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $search = $request->input('search'); 

        $absensi = Attendance::with('user')
            ->whereDate('date', $tanggal)
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get(); 

        return view('absensi.rekap', compact('absensi', 'tanggal'));
    }

    public function exportExcel(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        
        $absensi = Attendance::with('user')
                    ->whereDate('date', $tanggal)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $fileName = "Rekap_Absensi_Lamarema_{$tanggal}.csv";

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
                    $row->user->name ?? 'Tidak Diketahui',
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

    public function dataPegawai()
    {
        $pegawais = User::where('role', 'pegawai')->latest()->get();

        $totalPegawai = $pegawais->count();
        $totalWajah = $pegawais->whereNotNull('face_descriptor')->count();
        $totalBelumWajah = $totalPegawai - $totalWajah;

        return view('absensi.data-pegawai', compact('pegawais', 'totalPegawai', 'totalWajah', 'totalBelumWajah'));
    }

    public function tandaiTidakHadir()
    {
        $hariIni = \Carbon\Carbon::today()->format('Y-m-d');
        $pegawais = User::where('role', 'pegawai')->get();
        $jumlahAlpa = 0;

        foreach ($pegawais as $pegawai) {
            $sudahAbsen = Attendance::where('user_id', $pegawai->id)
                                    ->whereDate('date', $hariIni)
                                    ->exists();
            
            if (!$sudahAbsen) {
                Attendance::create([
                    'user_id'   => $pegawai->id,
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

    // ==========================================
    // 4. INPUT MANUAL KHUSUS ADMIN (IZIN/SAKIT)
    // ==========================================
    public function inputIzinSakitAdmin(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date'    => 'required|date',
            'status'  => 'required|in:Izin,Sakit',
        ]);

        // 2. Cari apakah pegawai sudah punya data di tanggal tersebut
        $absenExisting = Attendance::where('user_id', $request->user_id)
                                  ->whereDate('date', $request->date)
                                  ->first();

        date_default_timezone_set('Asia/Jakarta');

        if ($absenExisting) {
            // SKENARIO B: IZIN MENDADAK DI TENGAH JAM KERJA
            // Jika dia sudah absen masuk pagi, kita UPDATE datanya.
            // Jam check_in tetap, status diubah, dan check_out diisi dengan jam saat diizinkan pulang.
            
            $absenExisting->update([
                'status'    => $request->status,
                'check_out' => date('H:i:s'), 
            ]);

            return redirect()->back()->with('success', "Data diperbarui! Pegawai yang sudah masuk tadi pagi, kini ditandai pulang sebagai {$request->status}.");
            
        } else {
            // SKENARIO A: IZIN FULL SEHARIAN DARI PAGI
            // Jika belum ada data absen sama sekali, kita BUAT data baru.
            
            Attendance::create([
                'user_id'   => $request->user_id,
                'date'      => $request->date,
                'check_in'  => '00:00:00', 
                'check_out' => '00:00:00',
                'status'    => $request->status, 
            ]);

            return redirect()->back()->with('success', "Sukses! Pegawai ditandai absen {$request->status} seharian penuh.");
        }
    }
}