<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Exception;
use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramPoll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menarik pembaruan (updates) dari Telegram menggunakan metode Long Polling';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) {
            $this->error("TELEGRAM_BOT_TOKEN belum diatur di file .env!");
            return Command::FAILURE;
        }

        $this->info("Menghapus Webhook lama jika ada...");
        Http::withoutVerifying()->get("https://api.telegram.org/bot{$botToken}/deleteWebhook");

        $this->info("✓ Memulai Telegram Polling...");
        $this->info("Tekan Ctrl+C untuk berhenti.");
        
        $offset = 0;
        
        while (true) {
            try {
                // Gunakan timeout 25 detik agar koneksi tetap terbuka (Long Polling)
                $response = Http::withoutVerifying()->timeout(30)->get("https://api.telegram.org/bot{$botToken}/getUpdates", [
                    'offset' => $offset,
                    'timeout' => 25 
                ]);
                
                if ($response->successful()) {
                    $updates = $response->json('result');
                    
                    foreach ($updates as $update) {
                        $this->info("[" . date('Y-m-d H:i:s') . "] Menerima pesan baru dari ID: " . ($update['message']['chat']['id'] ?? 'Unknown'));
                        
                        // Teruskan payload ke TelegramController (seolah-olah dari Webhook)
                        $request = new Request();
                        $request->replace($update);
                        
                        try {
                            app(TelegramController::class)->webhook($request);
                        } catch (Exception $e) {
                            $this->error("Error saat memproses pesan: " . $e->getMessage());
                            Log::error("TelegramPoll Error: " . $e->getMessage());
                        }
                        
                        // Perbarui offset agar pesan yang sama tidak ditarik lagi
                        $offset = $update['update_id'] + 1;
                    }
                }
            } catch (Exception $e) {
                // Abaikan error timeout (wajar dalam long polling), selain itu cetak error
                if (!str_contains($e->getMessage(), 'cURL error 28')) {
                    $this->error("[" . date('Y-m-d H:i:s') . "] Error Koneksi: " . $e->getMessage());
                    sleep(2);
                }
            }
        }
    }
}
