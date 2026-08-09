<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function profil()
    {
        $pegawai = Auth::user();
        return view('absensi.profil', compact('pegawai'));
    }

    public function profilUpdate(Request $request)
    {
        $pegawai = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pegawai,email,' . $pegawai->id,
            'password' => 'nullable|string|min:8|confirmed', 
        ]);

        $pegawai->name = $request->name;
        $pegawai->email = $request->email;

        if ($request->filled('password')) {
            $pegawai->password = $request->password;
        }

        $pegawai->save();
        
        return redirect()->back()->with('success', 'Profil dan Password Anda berhasil diperbarui!');
    }
}
