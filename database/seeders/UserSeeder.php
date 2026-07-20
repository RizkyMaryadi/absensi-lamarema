<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Panggil model User
use Illuminate\Support\Facades\Hash; // Panggil Hash untuk password

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin
        User::create([
            'name' => 'Admin Absensi',
            'email' => 'admin@lamarema.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Pegawai (Untuk Test Telegram)
        User::create([
            'name' => 'Joko Test',
            'email' => 'joko@test.com',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            // PENTING: Ganti tulisan di bawah ini dengan angka Chat ID Telegram Anda
            'telegram_chat_id' => '1151187316' 
        ]);
    }
}