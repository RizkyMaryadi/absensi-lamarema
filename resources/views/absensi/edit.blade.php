<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pegawai - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .anim-item { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.5s forwards; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-amber-500 selection:text-white">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-100">
                <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-10 h-10 rounded-lg object-cover">
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-wide text-slate-900">Edit Pegawai</h1>
                <p class="text-xs text-amber-500 font-bold tracking-wider uppercase">Lamarema Fashion</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="/data-pegawai" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 text-sm font-bold text-slate-700 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pegawai
            </a>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-10 max-w-2xl">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden anim-item">
            <div class="p-7 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900"><i class="fas fa-user-edit text-blue-600 mr-2.5"></i>Ubah Profil Akun</h3>
                <p class="text-xs text-slate-400 mt-1">Perbarui data profil karyawan untuk login aplikasi.</p>
            </div>

            <div class="p-7">
                {{-- Alert Error Validasi --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl">
                        <ul class="list-disc list-inside text-sm font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $pegawai->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" placeholder="nama@email.com">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Jabatan / Posisi</label>
                            <input type="text" name="position" value="{{ old('position', $pegawai->position) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" placeholder="Contoh: Admin Kasir">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nomor HP</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $pegawai->phone_number) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Ganti Password Baru</label>
                        <input type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" placeholder="Isi hanya jika ingin diganti">
                        <p class="text-[11px] text-rose-500 font-medium italic mt-1.5">*Kosongkan kotak ini jika pegawai tidak ingin mengubah password lamanya.</p>
                    </div>

                    <div class="border-t border-slate-100 pt-6 flex justify-end gap-3">
                        <a href="/data-pegawai" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-extrabold transition-all duration-200 shadow-md shadow-blue-500/20 transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>