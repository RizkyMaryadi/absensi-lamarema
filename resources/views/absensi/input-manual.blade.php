<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Absen Manual - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans relativa selection:bg-amber-500 selection:text-white">
    
    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-10 h-10 rounded-lg object-cover">
            <h1 class="text-xl font-bold text-slate-900 tracking-wide">Admin Panel - Input Absen</h1>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-2xl">
        <div class="flex items-center gap-3 mb-8 anim-item">
            <a href="/data-absen" class="text-slate-400 hover:text-amber-500 transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-amber-50">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Formulir Input Absen Manual</h2>
        </div>

        @if($errors->any())
            <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded mb-4 shadow">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xl shadow-inner">
            <form action="{{ route('admin.store_manual') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Pegawai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-user-tie text-xs"></i>
                            </div>
                            <select name="pegawai_id" class="w-full pl-10 px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition bg-white" required>
                                <option value="" disabled selected>-- Pilih Pegawai --</option>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Absensi</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Kehadiran</label>
                            <select name="status" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition bg-white" required>
                                <option value="Tepat Waktu" {{ old('status') == 'Tepat Waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                                <option value="Terlambat" {{ old('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                                <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="Tidak Hadir" {{ old('status') == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Masuk (Check-in)</label>
                            <input type="time" name="check_in" value="{{ old('check_in') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jam Keluar (Check-out)</label>
                            <input type="time" name="check_out" value="{{ old('check_out') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition">
                            <span class="text-[10px] text-slate-500 mt-1 block">Boleh dikosongkan jika belum pulang.</span>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6 border-t border-slate-100 mt-6">
                        <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-bold transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 active:scale-[0.98]">
                            Simpan Absensi
                        </button>
                        <a href="/data-absen" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold transition border border-slate-200 active:scale-[0.98]">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
