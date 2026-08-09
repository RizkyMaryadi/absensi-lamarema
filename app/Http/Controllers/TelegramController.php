<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    // Fungsi utama untuk menangkap data dari Telegram
    public function webhook(Request $request)
    {
        $update = $request->all();
        
        // Catat ke log untuk keperluan debugging (bisa dilihat di storage/logs/laravel.log)
        Log::info('Telegram Webhook Payload:', $update);

        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];

            // 1. Jika pegawai mengetik /start
            if (isset($message['text']) && $message['text'] === '/start') {
                $this->mintaKontak($chatId);
                return response('OK', 200);
            }

            // 2. Jika pegawai mengirimkan Kontak (Nomor HP)
            if (isset($message['contact'])) {
                $phoneNumber = $message['contact']['phone_number'];
                $this->prosesKontak($chatId, $phoneNumber);
                return response('OK', 200);
            }
        }

        return response('OK', 200);
    }

    // Fungsi untuk memunculkan tombol "Bagikan Nomor HP"
    private function mintaKontak($chatId)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        $keyboard = [
            'keyboard' => [
                [
                    ['text' => '📱 Bagikan Nomor HP', 'request_contact' => true]
                ]
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ];

        Http::withoutVerifying()->post($url, [
            'chat_id' => $chatId,
            'text' => "Halo! 👋\n\nUntuk menghubungkan akun Anda dengan sistem Absensi Lamarema, silakan klik tombol *Bagikan Nomor HP* di bawah ini.",
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode($keyboard)
        ]);
    }

    // Fungsi untuk mengecek nomor HP ke Database dan mengupdate ID Telegram
    private function prosesKontak($chatId, $phoneNumber)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

        // Telegram kadang mengirim nomor dengan format 628... atau +628...
        // Kita normalkan dulu menjadi 08... agar cocok dengan inputan Admin di web
        $normalizedPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (substr($normalizedPhone, 0, 2) == '62') {
            $normalizedPhone = '0' . substr($normalizedPhone, 2);
        }

        // Cari pegawai di tabel users berdasarkan nomor HP
        $pegawai = Pegawai::where('phone_number', $normalizedPhone)
                    ->orWhere('phone_number', $phoneNumber)
                    ->first();

        if ($pegawai) {
            // JIKA KETEMU: Update telegram_chat_id yang tadinya NULL
            $pegawai->telegram_chat_id = $chatId;
            $pegawai->save();

            $pesan = "✅ *SINKRONISASI BERHASIL!*\n\nNomor HP Anda telah terverifikasi.\nAkun atas nama *{$pegawai->name}* sekarang terhubung dengan Bot Absensi Lamarema. Anda akan menerima notifikasi setiap kali melakukan absen.";
        } else {
            // JIKA TIDAK KETEMU: Tolak dan suruh lapor Admin
            $pesan = "❌ *SINKRONISASI GAGAL!*\n\nNomor HP Anda tidak ditemukan di sistem kami. Pastikan Admin sudah mendaftarkan nomor ini, lalu ketik /start untuk mencoba lagi.";
        }

        // Kirim balasan ke Telegram pegawai dan hilangkan tombol kontak
        Http::withoutVerifying()->post($url, [
            'chat_id' => $chatId,
            'text' => $pesan,
            'parse_mode' => 'Markdown',
            'reply_markup' => json_encode(['remove_keyboard' => true])
        ]);
    }
}

