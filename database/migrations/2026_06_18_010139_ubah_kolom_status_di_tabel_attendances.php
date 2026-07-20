<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Kita gunakan Schema bawaan Laravel untuk MENAMBAHKAN kolom enum baru
            $table->enum('status', ['Tepat Waktu', 'Terlambat', 'Izin', 'Sakit', 'Tidak Hadir'])
                  ->nullable()
                  ->default('Tidak Hadir');
        });
    }

    /**
     * Balikkan migrasi (jika terjadi kesalahan).
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Jika di-rollback, kolom status akan dihapus
            $table->dropColumn('status');
        });
    }
};