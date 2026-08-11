<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Attendance;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function dataPegawai()
    {
        $pegawais = Pegawai::where('role', 'pegawai')->latest()->get();

        $totalPegawai = $pegawais->count();
        $totalWajah = $pegawais->whereNotNull('face_descriptor')->count();
        $totalBelumWajah = $totalPegawai - $totalWajah;

        return view('absensi.data-pegawai', compact('pegawais', 'totalPegawai', 'totalWajah', 'totalBelumWajah'));
    }

    public function tambahPegawai()
    {
        $jabatans = \App\Models\Jabatan::all();
        return view('absensi.tambah', compact('jabatans'));
    }

    public function simpanPegawai(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai',
            'jabatan_id' => 'required|exists:jabatans,id',
            'phone_number' => 'required|string|max:20|unique:pegawai,phone_number', 
        ]);

        $passwordAcak = Str::random(10); 

        $pegawai = Pegawai::create([
            'name' => $request->name,
            'email' => $request->email,
            'jabatan_id' => $request->jabatan_id, 
            'role' => 'pegawai',
            'password' => $passwordAcak,
            'face_descriptor' => null,
            'phone_number' => $request->phone_number, 
            'telegram_chat_id' => null, 
        ]);

        return redirect()->route('pegawai.daftar', $pegawai->id)
                         ->with('success', 'Profil pegawai berhasil dibuat!')
                         ->with('passwordBawaan', $passwordAcak); 
    }
          
    public function hapusPegawai($id)
    {
        $pegawai = Pegawai::find($id);
        if ($pegawai) {
            Attendance::where('pegawai_id', $id)->delete();
            $pegawai->delete();
            return redirect()->back()->with('success', 'Pegawai berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Pegawai tidak ditemukan.');
    }

    public function infoPegawai($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('absensi.detail-info', compact('pegawai'));
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatans = \App\Models\Jabatan::all();
        return view('absensi.edit', compact('pegawai', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:pegawai,email,' . $id,
            'jabatan_id'   => 'required|exists:jabatans,id',
            'phone_number' => 'required|string|max:20|unique:pegawai,phone_number,' . $id,
            'password'     => 'nullable|string|min:6', 
        ]);

        $pegawai = Pegawai::findOrFail($id);
        $pegawai->name = $request->name;
        $pegawai->email = $request->email;
        $pegawai->jabatan_id = $request->jabatan_id;
        $pegawai->phone_number = $request->phone_number;

        if ($request->filled('password')) {
            $pegawai->password = $request->password; 
        }

        $pegawai->save();

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function daftar($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('absensi.daftar', compact('pegawai'));
    }

    public function simpanWajah(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id', 
            'face_descriptor' => 'required',
            'telegram_chat_id' => 'nullable|numeric|unique:pegawai,telegram_chat_id,' . $request->pegawai_id,
            'photos' => 'nullable|array'
        ]);

        $pegawai = Pegawai::find($request->pegawai_id);
        $pegawai->face_descriptor = $request->face_descriptor;
        if ($request->filled('telegram_chat_id')) {
            $pegawai->telegram_chat_id = $request->telegram_chat_id; 
        }

        if ($request->filled('photos') && is_array($request->photos)) {
            foreach ($request->photos as $index => $photoBase64) {
                $imageData = str_replace('data:image/jpeg;base64,', '', $photoBase64);
                $imageData = str_replace(' ', '+', $imageData);
                $imageName = 'pegawai_' . $pegawai->id . '_' . time() . '_' . ($index + 1) . '.jpg';
                Storage::disk('public')->put('photos/' . $imageName, base64_decode($imageData));
                
                // Simpan nama file foto pertama saja ke database (jika ada kolom photo)
                if ($index === 0) {
                    $pegawai->photo = $imageName;
                }
            }
        }

        $pegawai->save();
        return response()->json(['status' => 'success', 'message' => "Data wajah disimpan!"]);
    }
}
