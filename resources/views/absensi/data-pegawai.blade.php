<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Lengkap Pegawai - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Animasi haluuus */
        .anim-item { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.5s forwards; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-amber-500 selection:text-white relative">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="/" class="bg-white p-1 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-10 h-10 rounded-lg object-cover">
            </a>
            <div>
                <h1 class="text-xl font-bold tracking-wide text-slate-900">Data Pegawai</h1>
                <p class="text-xs text-amber-500 font-bold tracking-wider uppercase">Lamarema Fashion</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="/" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 text-sm font-bold text-slate-700 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-10 max-w-7xl">
        
        {{-- Alert Pesan Sukses Update --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm anim-item">
                <i class="fas fa-check-circle text-xl text-emerald-500"></i>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10 border-b border-slate-200 pb-8 anim-item">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-950 tracking-tight">Manajemen Data Karyawan</h2>
                <p class="text-slate-500 mt-1.5 text-base">Halaman pusat untuk melihat, mengelola, dan memantau status wajah seluruh pegawai.</p>
            </div>
            <div class="flex items-center gap-3 bg-white p-2 rounded-2xl border border-slate-100 shadow-inner">
                <div class="text-center px-4 py-2 border-r border-slate-100">
                    <p class="text-2xl font-bold text-slate-800">{{ $totalPegawai }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</p>
                </div>
                <div class="text-center px-4 py-2 border-r border-slate-100">
                    <p class="text-2xl font-bold text-emerald-600">{{ $totalWajah }}</p>
                    <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Wajah Aktif</p>
                </div>
                <div class="text-center px-4 py-2">
                    <p class="text-2xl font-bold text-rose-600">{{ $totalBelumWajah }}</p>
                    <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest">Belum Scan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-lg overflow-hidden anim-item delay-1">
            <div class="p-7 border-b border-slate-100 flex justify-between items-center bg-white sticky top-[81px] z-10">
                <h3 class="text-xl font-bold text-slate-900"><i class="fas fa-list-ul text-amber-500 mr-2.5"></i>Daftar Lengkap Pegawai Toko</h3>
                <a href="/tambah-pegawai" class="bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-white px-6 py-3 rounded-xl text-sm font-extrabold transition-all duration-300 shadow-md shadow-amber-500/30 flex items-center gap-2 transform hover:scale-105 active:scale-95 group">
                    <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i> Daftarkan Pegawai Baru
                </a>
            </div>
            
            <div class="overflow-x-auto p-3">
                <table class="w-full text-left text-sm text-slate-600 border-collapse">
                    <thead class="text-xs uppercase text-slate-400 border-b border-slate-100 bg-slate-50/50">
                        <tr>
                            <th class="px-7 py-5 font-bold tracking-wider">Nama Lengkap & Info Login</th>
                            <th class="px-7 py-5 font-bold tracking-wider">Jabatan / Posisi</th>
                            <th class="px-7 py-5 font-bold tracking-wider">Status Data Wajah (IoT)</th>
                            <th class="px-7 py-5 text-right font-bold tracking-wider">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pegawais as $p)
                        <tr class="hover:bg-slate-50 transition-colors duration-200 group">
                            <td class="px-7 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center text-slate-400 shadow-inner group-hover:border-amber-200 transition-colors overflow-hidden">
                                        @if($p->photo)
                                            <img src="{{ asset('storage/photos/' . $p->photo) }}" alt="Foto {{ $p->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-xl"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-base group-hover:text-amber-600 transition-colors">{{ $p->name }}</div>
                                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-1.5"><i class="fas fa-envelope text-slate-300"></i> {{ $p->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-7 py-5">
                                <span class="bg-slate-100 text-slate-700 py-2 px-4 rounded-full text-xs font-bold border border-slate-200 inline-block shadow-inner capitalize">
                                    {{ $p->jabatan->nama_jabatan ?? 'Karyawan Toko' }}
                                </span>
                            </td>

                            <td class="px-7 py-5">
                                @if($p->face_descriptor)
                                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 py-2 px-4 rounded-full text-xs font-extrabold flex items-center inline-flex gap-2 w-fit shadow-inner group-hover:bg-emerald-100 transition-colors">
                                        <i class="fas fa-check-circle text-base"></i> Terdaftar
                                    </span>
                                @else
                                    <span class="bg-rose-50 text-rose-700 border border-rose-200 py-2 px-4 rounded-full text-xs font-extrabold flex items-center inline-flex gap-2 w-fit shadow-inner group-hover:bg-rose-100 transition-colors animate__animated animate__pulse animate__infinite">
                                        <i class="fas fa-times-circle text-base"></i> Wajah Belum Ada
                                    </span>
                                @endif
                            </td>
                            <td class="px-7 py-5 text-right">
                                <div class="flex justify-end gap-3 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="/daftar-wajah/{{ $p->id }}" class="bg-slate-100 hover:bg-amber-500 text-slate-500 hover:text-white w-10 h-10 rounded-xl transition-all duration-200 flex items-center justify-center shadow-inner border border-slate-200 hover:border-amber-600 active:scale-95" title="Scan Wajah (IoT)">
                                        <i class="fas fa-camera text-base"></i>
                                    </a>

                                    {{-- 🔥 TOMBOL EDIT BARU (BERWARNA BIRU JIKA DI-HOVER) --}}
                                    <a href="{{ route('pegawai.edit', $p->id) }}" class="bg-slate-100 hover:bg-blue-600 text-slate-500 hover:text-white w-10 h-10 rounded-xl transition-all duration-200 flex items-center justify-center shadow-inner border border-slate-200 hover:border-blue-700 active:scale-95" title="Edit Profil/Akun Pegawai">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>

                                    <form action="/hapus-pegawai/{{ $p->id }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pegawai {{ $p->name }} beserta data wajahnya? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-slate-100 hover:bg-rose-500 text-slate-500 hover:text-white w-10 h-10 rounded-xl transition-all duration-200 flex items-center justify-center shadow-inner border border-slate-200 hover:border-rose-600 active:scale-95" title="Hapus Permanen">
                                            <i class="fas fa-trash text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-7 py-20 text-center text-slate-400 bg-slate-50/50 anim-item delay-2"> 
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="bg-white border border-slate-100 p-6 rounded-full shadow-lg text-slate-200">
                                        <i class="fas fa-folder-open text-6xl"></i>
                                    </div>
                                    <p class="text-base font-semibold">Belum ada data pegawai yang terdaftar.</p>
                                    <a href="/tambah-pegawai" class="text-sm font-bold text-amber-600 hover:text-amber-700 mt-1 flex items-center gap-1.5"><i class="fas fa-plus-circle"></i> Mulai Daftarkan Pegawai Sekarang</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="mt-16 pt-8 border-t border-slate-100 text-center anim-item delay-2">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-widest">© 2026 Lamarema Fashion</p>
        </footer>
    </main>

</body>
</html>
