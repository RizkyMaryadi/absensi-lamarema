<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class KioskController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'pegawai') {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Anda hanya boleh melakukan absen menggunakan mesin kamera yang ada di kantor.');
        }

        return view('absensi.scan');
    }

    public function getPegawai()
    {
        $pegawais = Pegawai::whereNotNull('face_descriptor')
                     ->select('id', 'name', 'face_descriptor')
                     ->get();
        return response()->json($pegawais);
    }

    public function catatKehadiran(Request $request)
    {
        $request->validate(['pegawai_id' => 'required|exists:pegawai,id']);

        try {
            $pegawai = Pegawai::findOrFail($request->pegawai_id);
            date_default_timezone_set('Asia/Jakarta');
            
            $today = date('Y-m-d');
            $jamSekarangFull = date('H:i:s');
            $jamMenitSekarang = date('H:i'); 

            $absencesHariIni = Attendance::where('pegawai_id', $pegawai->id)
                                      ->whereDate('date', $today)
                                      ->first();

            // -------------------------------------------------------------------------
            // LANGKAH A: PENGUNCI MESIN ABSEN]
            // Jika Admin sudah input Izin/Sakit/Tidak Hadir seharian penuh, tolak scan!
            // -------------------------------------------------------------------------
            if ($absencesHariIni && in_array($absencesHariIni->status, ['Izin', 'Sakit', 'Tidak Hadir'])) {
                return response()->json([
                    'status' => 'error', 
                    'message' => "❌ DITOLAK! {$pegawai->name} tidak bisa scan. Status hari ini sudah dikunci sebagai: *{$absencesHariIni->status}*."
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
                    'pegawai_id' => $pegawai->id,
                    'date' => $today,
                    'check_in' => $jamSekarangFull,
                    'status' => $statusKehadiran,
                ]);

                $this->sendTelegramNotification($pegawai, $attendance, 'masuk');
                return response()->json(['status' => 'success', 'message' => "Sukses MASUK! {$pegawai->name} tercatat {$statusKehadiran}."]);
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

                $this->sendTelegramNotification($pegawai, $absencesHariIni, 'keluar');
                return response()->json(['status' => 'success', 'message' => "Sukses KELUAR! Selamat istirahat {$pegawai->name}."]);
            }

            return response()->json(['status' => 'error', 'message' => "Anda sudah absen lengkap hari ini!"]);

        } catch (Exception $e) {
            Log::error("Sistem Absensi Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => "Sistem Error!"], 500);
        }
    }

    private function sendTelegramNotification(Pegawai $pegawai, Attendance $attendance, string $tipe)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken || !$pegawai->telegram_chat_id) return;

        date_default_timezone_set('Asia/Jakarta');
        $tanggalStr = Carbon::parse($attendance->date)->translatedFormat('l, d F Y');
        
        if ($tipe === 'masuk') {
            $iconStatus = $attendance->status === 'Terlambat' ? '🔴' : '🟢';
            $txt = "🔔 *LAPORAN ABSENSI MASUK*\n\n"
                 . "👤 *Pegawai:* {$pegawai->name}\n"
                 . "📅 *Tanggal:* {$tanggalStr}\n"
                 . "🕒 *Jam:* " . date('H:i:s', strtotime($attendance->check_in)) . " WIB\n"
                 . "📊 *Status:* {$iconStatus} *{$attendance->status}*\n\n"
                 . "Selamat bekerja dan tetap semangat! 💪";
        } else {
            $txt = "🔕 *LAPORAN ABSENSI KELUAR*\n\n"
                 . "👤 *Pegawai:* {$pegawai->name}\n"
                 . "📅 *Tanggal:* {$tanggalStr}\n"
                 . "🕒 *Jam:* " . date('H:i:s', strtotime($attendance->check_out)) . " WIB\n\n"
                 . "Terima kasih atas kerja keras Anda hari ini. Selamat istirahat! 👋";
        }

        try {
            Http::withoutVerifying()->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $pegawai->telegram_chat_id,
                'text' => $txt,
                'parse_mode' => 'Markdown',
            ]);
        } catch (Exception $e) {
            Log::error("Telegram Error: " . $e->getMessage());
        }
    }
}
