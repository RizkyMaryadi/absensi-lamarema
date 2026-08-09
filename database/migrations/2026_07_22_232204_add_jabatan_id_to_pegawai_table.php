<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->foreignId('jabatan_id')->nullable()->after('password')->constrained('jabatans')->onDelete('set null');
        });

        // Migrate data
        $pegawais = DB::table('pegawai')->whereNotNull('position')->get();
        foreach($pegawais as $p) {
            if (trim($p->position) !== '') {
                $jabatan = DB::table('jabatans')->where('nama_jabatan', $p->position)->first();
                if (!$jabatan) {
                    $jabatanId = DB::table('jabatans')->insertGetId([
                        'nama_jabatan' => $p->position, 
                        'created_at' => now(), 
                        'updated_at' => now()
                    ]);
                } else {
                    $jabatanId = $jabatan->id;
                }
                DB::table('pegawai')->where('id', $p->id)->update(['jabatan_id' => $jabatanId]);
            }
        }

        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->string('position')->nullable();
        });

        $pegawais = DB::table('pegawai')->whereNotNull('jabatan_id')->get();
        foreach($pegawais as $p) {
            $jabatan = DB::table('jabatans')->where('id', $p->jabatan_id)->first();
            if ($jabatan) {
                DB::table('pegawai')->where('id', $p->id)->update(['position' => $jabatan->nama_jabatan]);
            }
        }

        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropForeign(['jabatan_id']);
            $table->dropColumn('jabatan_id');
        });
    }
};
