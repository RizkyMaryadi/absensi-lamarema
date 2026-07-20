<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@lamarema.com',
            'password' => Hash::make('admin123'), // Jangan lupa passwordnya 'admin123'
            'role' => 'admin',
            'phone_number' => '',
        ]);
    }
}