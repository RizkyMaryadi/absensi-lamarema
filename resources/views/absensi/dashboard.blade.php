<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-amber-500 selection:text-white relative">

    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-4">
            <button id="hamburgerBtn" class="text-slate-500 hover:text-amber-500 focus:outline-none transition-colors">
                <i class="fas fa-bars text-2xl"></i>
            </button>
            
            <div class="flex items-center gap-3">
                <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-100">
                    <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-10 h-10 rounded-lg object-cover">
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide text-slate-900">Admin Panel</h1>
                    <p class="text-xs text-amber-500 font-bold tracking-wider uppercase">Lamarema Fashion</p>
                </div>
            </div>
        </div>
        
        <div class="flex gap-4">
            <form action="{{ route('generate.alpa') }}" method="POST" onsubmit="return confirm('Yakin ingin menutup absen hari ini? Pegawai yang belum absen akan otomatis ditandai Tidak Hadir.')">
                @csrf
                <button type="submit" class="bg-white hover:bg-rose-50 border border-rose-200 px-5 py-2.5 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm font-bold text-rose-600 shadow-sm hover:shadow-md cursor-pointer">
                    <i class="fas fa-calendar-times"></i> Tutup Absen Hari Ini
                </button>
            </form>

            <a href="/scan" class="bg-white hover:bg-amber-50 border border-amber-200 px-5 py-2.5 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm font-bold text-amber-600 shadow-sm hover:shadow-md">
                <i class="fas fa-expand"></i> Absen Wajah (Scan)
            </a>
        </div>
    </nav>

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden transition-opacity duration-300 opacity-0"></div>

    <div id="sidebar" class="fixed top-0 left-0 h-full w-72 bg-white border-r border-slate-200 z-[60] transform -translate-x-full transition-transform duration-300 ease-in-out shadow-2xl flex flex-col">
        <div class="p-6 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
            <div class="flex flex-col gap-3">
                <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-16 h-16 rounded-2xl shadow-md border border-amber-200">
                <h2 class="text-xl font-bold text-slate-800 tracking-wide">Menu Admin</h2>
            </div>
            <button id="closeSidebarBtn" class="text-slate-400 hover:text-rose-500 transition-colors mt-1">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <div class="p-4 flex flex-col gap-2 overflow-y-auto flex-grow">
            <a href="/" class="flex items-center gap-3 text-amber-600 bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-amber-100 font-bold shadow-sm">
                <i class="fas fa-home w-6"></i> Dashboard Utama
            </a>

            <a href="/data-pegawai" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-address-book w-6 text-amber-500"></i> Data Pegawai
            </a>
            
            <a href="/data-absen" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-calendar-check w-6 text-amber-500"></i> Rekap Data Absensi
            </a>
            
            <a href="/tambah-pegawai" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-user-plus w-6 text-amber-500"></i> Tambah Pegawai
            </a>

            <div class="my-4 border-t border-slate-100"></div>
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-2">Konfigurasi</p>

            <a href="/pengaturan" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-sliders-h w-6 text-amber-500"></i> Pengaturan Sistem
            </a>

            <a href="/profil" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-user-shield w-6 text-amber-500"></i> Profil Admin
            </a>

            <div class="mt-auto pt-6">
                <button type="button" onclick="openLogoutModal()" class="w-full flex items-center gap-3 text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-rose-100 shadow-sm font-medium cursor-pointer">
                    <i class="fas fa-power-off w-6"></i> Keluar (Logout)
                </button>
            </div>
        </div>

        <div class="p-4 border-t border-slate-100">
            <p class="text-xs text-center text-slate-400">© 2026 Lamarema Fashion</p>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 max-w-7xl">

        @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center gap-3 animate-pulse">
            <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Total Pegawai</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalPegawai }}</h3>
                </div>
                <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl text-amber-500 shadow-sm">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Hadir Hari Ini</p>
                    <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ $totalHadir }}</h3>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl text-emerald-500 shadow-sm">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:-translate-y-1 transition-transform duration-300">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider">Belum Absen</p>
                    <h3 class="text-3xl font-bold text-rose-600 mt-1">{{ $totalBelumHadir }}</h3>
                </div>
                <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl text-rose-500 shadow-sm">
                    <i class="fas fa-user-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                        <h2 class="text-lg font-bold text-slate-800"><i class="fas fa-address-book text-amber-500 mr-2"></i>Data Pegawai</h2>
                        <a href="/tambah-pegawai" class="bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 shadow-md shadow-amber-500/30">
                            <i class="fas fa-plus mr-1"></i> Tambah Pegawai
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto p-2">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="text-xs uppercase text-slate-400 border-b border-slate-100 bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Nama & Info</th>
                                    <th class="px-6 py-4 font-semibold">Jabatan</th> 
                                    <th class="px-6 py-4 font-semibold">Data Wajah</th>
                                    <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($pegawais as $p)
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-base">{{ $p->name }}</div>
                                                <div class="text-xs text-slate-500 mt-0.5">{{ $p->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="bg-slate-100 text-slate-600 py-1.5 px-3 rounded-lg text-xs font-semibold border border-slate-200 inline-block">
                                            {{ $p->position ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($p->face_descriptor)
                                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 py-1.5 px-3 rounded-full text-xs font-bold flex items-center inline-flex gap-1.5 w-fit">
                                                <i class="fas fa-check-circle"></i> Terdaftar
                                            </span>
                                        @else
                                            <span class="bg-rose-50 text-rose-600 border border-rose-200 py-1.5 px-3 rounded-full text-xs font-bold flex items-center inline-flex gap-1.5 w-fit">
                                                <i class="fas fa-times-circle"></i> Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="/daftar-wajah/{{ $p->id }}" class="bg-slate-100 hover:bg-amber-500 text-slate-500 hover:text-white p-2 rounded-lg transition-colors duration-200" title="Scan Wajah">
                                                <i class="fas fa-camera"></i>
                                            </a>
                                            <form action="/hapus-pegawai/{{ $p->id }}" method="POST" class="inline" onsubmit="return confirm('Hapus pegawai ini beserta data wajahnya?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-slate-100 hover:bg-rose-500 text-slate-500 hover:text-white p-2 rounded-lg transition-colors duration-200" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400"> 
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-slate-200"></i>
                                            <p>Belum ada data pegawai.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-end bg-white">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800"><i class="fas fa-history text-emerald-500 mr-2"></i>Hadir Hari Ini</h2>
                        </div>
                        <div class="bg-slate-100 border border-slate-200 px-3 py-1 rounded-md text-xs font-bold text-slate-600 shadow-sm">
                            {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                        </div>
                    </div>
                    
                    <div class="overflow-y-auto p-4 max-h-[500px]">
                        <ul class="space-y-3">
                            @forelse($absensiHariIni as $absen)
                            <li class="bg-white border border-slate-100 shadow-sm rounded-xl p-4 flex items-center justify-between hover:border-emerald-200 transition-colors duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                                        <i class="fas fa-user-check text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">{{ $absen->user->name }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $absen->user->position ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="bg-slate-50 text-emerald-600 py-1 px-2.5 rounded-lg text-xs font-mono font-bold border border-slate-200">
                                        {{ \Carbon\Carbon::parse($absen->check_in)->format('H:i') }} WIB
                                    </span>
                                </div>
                            </li>
                            @empty
                            <div class="flex flex-col items-center justify-center py-10 text-slate-400 text-center">
                                <div class="bg-slate-50 border border-slate-100 p-4 rounded-full mb-3">
                                    <i class="fas fa-coffee text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Belum ada pegawai yang absen<br>hari ini.</p>
                            </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="logoutModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-slate-900/50 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-slate-100 transform transition-all scale-100 duration-300">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-50 text-rose-500 mb-4 animate-bounce">
                    <i class="fas fa-power-off text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Konfirmasi Keluar</h3>
                <p class="text-sm text-slate-500 mb-6 font-medium">Apakah Anda yakin ingin keluar dari Admin Panel Lamarema Fashion?</p>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeLogoutModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-colors duration-200 cursor-pointer">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1 m-0">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-rose-500 text-white text-sm font-bold rounded-xl hover:bg-rose-600 shadow-lg shadow-rose-500/20 transition-colors duration-200 cursor-pointer">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Logika Sidebar Bawaan
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (sidebarOverlay.classList.contains('hidden')) {
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => sidebarOverlay.classList.remove('opacity-0'), 10);
            } else {
                sidebarOverlay.classList.add('opacity-0');
                setTimeout(() => sidebarOverlay.classList.add('hidden'), 300);
            }
            sidebar.classList.toggle('-translate-x-full');
        }

        hamburgerBtn.addEventListener('click', toggleSidebar);
        closeSidebarBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Logika Modal Logout Baru
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
        }
        
        // Menutup pop-up jika menekan area kosong
        window.onclick = function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target == modal) {
                closeLogoutModal();
            }
        }
    </script>
</body>
</html>