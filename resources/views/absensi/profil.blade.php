<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - Lamarema Fashion</title>
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
            <a href="/scan" class="bg-white hover:bg-amber-50 border border-amber-200 px-5 py-2.5 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm font-bold text-amber-600 shadow-sm hover:shadow-md">
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
            <a href="/" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-home w-6 text-amber-500"></i> Dashboard Utama
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

            <a href="/profil" class="flex items-center gap-3 text-amber-600 bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-amber-100 font-bold shadow-sm">
                <i class="fas fa-user-shield w-6"></i> Profil Admin
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
    </div>

    <div class="container mx-auto px-6 py-8 max-w-3xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800"><i class="fas fa-user-shield text-amber-500 mr-3"></i>Profil Admin</h1>
            <p class="text-slate-500 mt-2">Kelola informasi akun dan kata sandi Anda.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-3 font-bold mb-2">
                <i class="fas fa-exclamation-circle text-lg"></i> Terjadi Kesalahan:
            </div>
            <ul class="list-disc list-inside text-sm pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form action="{{ route('profil.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700 font-medium" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700 font-medium" required>
                    </div>
                </div>

                <hr class="border-slate-100 my-6">

                <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-100/50 mb-6">
                    <p class="text-sm text-slate-500 font-medium"><i class="fas fa-info-circle text-amber-500 mr-2"></i>Biarkan kolom kata sandi di bawah ini kosong jika Anda tidak ingin mengubahnya.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" placeholder="Masukkan kata sandi baru..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-check-circle text-slate-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" placeholder="Ketik ulang kata sandi baru..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all text-slate-700 font-medium">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-white px-8 py-3 rounded-xl font-bold transition-all duration-300 shadow-md shadow-amber-500/30 flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

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