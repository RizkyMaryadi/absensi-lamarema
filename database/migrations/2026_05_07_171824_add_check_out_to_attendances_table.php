<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckOutToAttendancesTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        // Jika kolom 'check_out' BELUM ADA, baru kita buat
        if (!Schema::hasColumn('attendances', 'check_out')) {
            Schema::table('attendances', function (Blueprint $colom) {
                // Menambahkan kolom check_out tipe TIME, boleh kosong (nullable),
                // diletakkan setelah kolom check_in.
                $colom->time('check_out')->nullable()->after('check_in');
            });
        }
    }

    /**
     * Batalkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        //  Jika kolom 'check_out' ADA, baru kita hapus
        if (Schema::hasColumn('attendances', 'check_out')) {
            Schema::table('attendances', function (Blueprint $colom) {
                // Menghapus kolom check_out jika migrasi dibatalkan.
                $colom->dropColumn('check_out');
            });
        }
    }
}