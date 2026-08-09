<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:jabatans,nama_jabatan'
        ]);

        $jabatan = Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan
        ]);

        // If request expects JSON (like from a modal fetch API)
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'jabatan' => $jabatan,
                'message' => 'Jabatan berhasil ditambahkan'
            ]);
        }

        return back()->with('success', 'Jabatan berhasil ditambahkan');
    }
}
