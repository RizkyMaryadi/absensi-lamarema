<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // WAJIB: Untuk enkripsi password

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin (Untuk login Dashboard)
        Pegawai::create([
            'name' => 'Admin Absensi',
            'email' => 'admin@lamarema.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Pegawai (Untuk Test Scan Wajah & Telegram)
        Pegawai::create([
            'name' => 'Joko Test',
            'email' => 'joko@test.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            // PENTING: Ganti tulisan di bawah ini dengan angka Chat ID Telegram Anda
            'telegram_chat_id' => '1151187316' 
        ]);
    }
}