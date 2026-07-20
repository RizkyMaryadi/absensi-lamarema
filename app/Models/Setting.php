<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // --- TAMBAHKAN BARIS INI SECARA LENGKAP ---
    protected $fillable = ['key', 'value'];
}