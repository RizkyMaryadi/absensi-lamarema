<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem - Lamarema Fashion</title>
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
            <a href="{{ route('scan') }}" class="bg-white hover:bg-amber-50 border border-amber-200 px-5 py-2.5 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm font-bold text-amber-600 shadow-sm hover:shadow-md">
                <i class="fas fa-expand"></i> Mode Kiosk (Scan)
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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-home w-6 text-amber-500"></i> Dashboard Utama
            </a>
            
            <a href="{{ route('rekap') }}" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-calendar-check w-6 text-amber-500"></i> Rekap Data Absensi
            </a>

            <a href="{{ route('pegawai.tambah') }}" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-user-plus w-6 text-amber-500"></i> Tambah Pegawai
            </a>

            <div class="my-4 border-t border-slate-100"></div>
            <p class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-2">Konfigurasi</p>

            <a href="{{ route('pengaturan') }}" class="flex items-center gap-3 text-amber-600 bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-amber-100 font-bold shadow-sm">
                <i class="fas fa-sliders-h w-6"></i> Pengaturan Sistem
            </a>

            <a href="{{ route('profil') }}" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-user-shield w-6 text-amber-500"></i> Profil Admin
            </a>

            <div class="mt-auto pt-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-rose-100 shadow-sm font-medium" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                        <i class="fas fa-power-off w-6"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>

        <div class="p-4 border-t border-slate-100">
            <p class="text-xs text-center text-slate-400">© 2026 Lamarema Fashion</p>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 max-w-6xl">
        
        <div class="flex items-center gap-4 mb-8">
            <div class="bg-amber-100 p-3 rounded-2xl text-amber-600 shadow-sm">
                <i class="fas fa-sliders-h text-3xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-wide">Pengaturan Sistem</h2>
                <p class="text-slate-500 text-sm mt-1">Konfigurasi preferensi jam kerja, batas waktu, dan toleransi absensi.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
        @endif

        <form action="{{ route('pengaturan.simpan') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- Kartu Pengaturan MASUK --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800">
                            <i class="fas fa-sign-in-alt text-emerald-500 mr-2"></i>Konfigurasi Waktu Masuk
                        </h3>
                    </div>
                    <div class="p-6 flex-grow space-y-6">
                        <div>
                            <label for="batas_waktu" class="block text-sm font-bold text-slate-700 mb-2">
                                Jam Masuk Normal (WIB)
                            </label>
                            <p class="text-slate-500 text-sm mb-4">
                                Tentukan jam masuk utama. Jika melewati jam ini, status tercatat <span class="text-rose-600 font-bold">Terlambat 🔴</span>.
                            </p>
                            <div class="relative w-48">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-amber-500">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <input type="time" name="batas_waktu" id="batas_waktu" value="{{ $batas_waktu }}" 
                                    class="bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-xl focus:ring-0 focus:border-amber-400 block w-full pl-11 p-3 transition-colors duration-200 outline-none shadow-sm" required>
                            </div>
                        </div>

                        <div>
                            <label for="toleransi" class="block text-sm font-bold text-slate-700 mb-2">
                                Toleransi Terlambat (Menit)
                            </label>
                            <p class="text-slate-500 text-sm mb-4">
                                Pegawai dianggap <span class="text-emerald-600 font-bold">Tepat Waktu 🟢</span> selama dalam rentang ini.
                            </p>
                            <div class="relative w-48">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-amber-500">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <input type="number" name="toleransi" id="toleransi" value="{{ $toleransi }}" min="0"
                                    class="bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-xl focus:ring-0 focus:border-amber-400 block w-full pl-11 p-3 transition-colors duration-200 outline-none shadow-sm" placeholder="0" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400 text-xs font-bold uppercase">
                                    Menit
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kartu Pengaturan KELUAR (PULANG) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800">
                            <i class="fas fa-sign-out-alt text-rose-500 mr-2"></i>Konfigurasi Waktu Keluar (Pulang)
                        </h3>
                    </div>
                    <div class="p-6 flex-grow space-y-6">
                        <div>
                            <label for="jam_pulang" class="block text-sm font-bold text-slate-700 mb-2">
                                Jam Pulang Normal (WIB)
                            </label>
                            <p class="text-slate-500 text-sm mb-4">
                                <span class="text-rose-500 font-bold">Proteksi:</span> Pegawai DILARANG absen keluar sebelum jam ini.
                            </p>
                            <div class="relative w-48">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-emerald-500">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <input type="time" name="jam_pulang" id="jam_pulang" value="{{ $jam_pulang }}" 
                                    class="bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-xl focus:ring-0 focus:border-amber-400 block w-full pl-11 p-3 transition-colors duration-200 outline-none shadow-sm" required>
                            </div>
                        </div>

                        <div>
                            <label for="batas_akhir_pulang" class="block text-sm font-bold text-slate-700 mb-2">
                                Batas Akhir Absensi Keluar (WIB)
                            </label>
                            <p class="text-slate-500 text-sm mb-4">
                                Batas waktu maksimal mesin scan melayani absen keluar hari ini.
                            </p>
                            <div class="relative w-48">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-rose-500">
                                    <i class="fas fa-history"></i>
                                </div>
                                <input type="time" name="batas_akhir_pulang" id="batas_akhir_pulang" value="{{ $batas_akhir_pulang }}" 
                                    class="bg-white border-2 border-slate-200 text-slate-800 font-bold text-base rounded-xl focus:ring-0 focus:border-amber-400 block w-full pl-11 p-3 transition-colors duration-200 outline-none shadow-sm" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mb-8">
                <button type="submit" class="bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-white px-8 py-3 rounded-xl text-base font-bold transition-all duration-300 shadow-md shadow-amber-500/30 flex items-center gap-2 active:scale-95">
                    <i class="fas fa-save"></i> Simpan Semua Pengaturan
                </button>
            </div>

        </form>

    </div>

    <script>
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
    </script>
</body>
</html>