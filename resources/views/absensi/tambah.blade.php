<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai Baru - Lamarema Fashion</title>
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
            <h1 class="text-xl font-bold text-slate-900 tracking-wide">Admin Panel - Tambah Pegawai</h1>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-2xl">
        <div class="flex items-center gap-3 mb-8 anim-item">
            <a href="/" class="text-slate-400 hover:text-amber-500 transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-amber-50">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Formulir Tambah Pegawai Baru</h2>
        </div>

        @if($errors->any())
            <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded mb-4 shadow">
                <strong>Terjadi Kesalahan!</strong>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-xl shadow-inner">
            <form action="{{ url('/tambah-pegawai') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap Pegawai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full pl-10 px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition placeholder:text-slate-300" placeholder="Contoh: Rizky Maryadi" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition placeholder:text-slate-300" placeholder="Contoh: rizky@lamarema.id">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Posisi / Jabatan</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition placeholder:text-slate-300" placeholder="Contoh: Staff Gudang">
                        </div>
                    </div>

                    <div class="bg-amber-50 p-6 rounded-xl border border-amber-100 shadow-inner">
                        <label class="block text-sm font-bold text-amber-950 mb-2.5">
                            <i class="fas fa-phone-alt text-emerald-600 mr-1.5"></i> Nomor HP (Aktif)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-500/70">
                                <i class="fas fa-mobile-alt text-xs"></i>
                            </div>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full pl-10 px-4 py-3 border border-slate-300 rounded-xl focus:ring-emerald-200 focus:border-emerald-400 outline-none transition placeholder:text-slate-400 bg-white" placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="flex items-start gap-2.5 mt-3 text-xs text-amber-700 leading-relaxed bg-white border border-amber-100 p-4 rounded-lg">
                            <i class="fas fa-info-circle mt-0.5 text-amber-500"></i>
                            <p>Masukkan <strong>Nomor HP</strong> pegawai. Nomor ini akan dicocokkan otomatis saat pegawai mengirimkan kontak mereka ke Bot Telegram Lamarema.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-6 border-t border-slate-100 mt-6">
                        <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-bold transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 active:scale-[0.98]">
                            Simpan Profil & Lanjut Scan Wajah
                        </button>
                        <a href="/" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-bold transition border border-slate-200 active:scale-[0.98]">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>