<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // KUNCI: Ini membuka izin agar kita bisa menyimpan data pakai perintah ::create()
    protected $guarded = []; 
    
    // Atau jika mau lebih spesifik:
    // protected $fillable = ['user_id', 'date', 'check_in', 'check_out', 'status', 'note'];
    
    // Relasi ke User (Pegawai)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}