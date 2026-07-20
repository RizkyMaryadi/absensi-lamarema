<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .appearance-none {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
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
                    <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover">
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-wide text-slate-900">Admin Panel</h1>
                    <p class="text-xs text-amber-500 font-bold tracking-wider uppercase">Lamarema Fashion</p>
                </div>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="/" class="bg-slate-100 hover:bg-slate-200 border border-slate-200 px-5 py-2.5 rounded-xl transition-all duration-300 flex items-center gap-2 text-sm font-bold text-slate-700 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden transition-opacity duration-300 opacity-0"></div>

    <div id="sidebar" class="fixed top-0 left-0 h-full w-72 bg-white border-r border-slate-200 z-[60] transform -translate-x-full transition-transform duration-300 ease-in-out shadow-2xl flex flex-col">
        <div class="p-6 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
            <div class="flex flex-col gap-3">
                <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo" class="w-16 h-16 rounded-2xl shadow-md border border-amber-200">
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
            <a href="/data-absen" class="flex items-center gap-3 text-amber-600 bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-amber-100 font-bold shadow-sm">
                <i class="fas fa-calendar-check w-6"></i> Rekap Data Absensi
            </a>
            <a href="/tambah-pegawai" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-user-plus w-6 text-amber-500"></i> Tambah Pegawai
            </a>
            <div class="my-4 border-t border-slate-100"></div>
            <a href="/pengaturan" class="flex items-center gap-3 text-slate-600 hover:text-amber-600 hover:bg-amber-50 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:border-amber-100 font-medium">
                <i class="fas fa-sliders-h w-6 text-amber-500"></i> Pengaturan Sistem
            </a>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8 max-w-7xl">
        
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="/" class="text-slate-400 hover:text-amber-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-amber-50">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Rekap Data Absensi</h2>
                </div>
                <p class="text-sm text-slate-500 ml-11">Laporan kehadiran harian pegawai Lamarema Fashion</p>
            </div>

            <div class="bg-white p-2.5 rounded-2xl border border-slate-200 shadow-sm w-full lg:w-auto">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative flex items-center bg-slate-50 rounded-xl border border-slate-200 w-full md:w-auto">
                        <div class="pl-3.5 pr-2 text-slate-400"><i class="fas fa-search"></i></div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="bg-transparent py-2.5 pr-4 text-sm font-medium outline-none">
                    </div>

                    <div class="relative flex items-center bg-slate-50 rounded-xl border border-slate-200">
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="bg-transparent py-2.5 px-4 text-sm font-bold text-slate-700 outline-none cursor-pointer w-full">
                    </div>

                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm">
                        Terapkan
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-6 py-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-600 px-6 py-4 rounded-2xl mb-6 shadow-sm flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="p-4 border-b border-slate-100 bg-amber-50">
                <h3 class="text-md font-bold text-amber-800"><i class="fas fa-user-edit mr-2"></i> Input Manual Izin / Sakit Pegawai</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.input_izin_sakit') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="user_id" class="block text-sm font-bold text-slate-700 mb-2">Pilih Pegawai</label>
                            <select name="user_id" id="user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-amber-500 transition-colors" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach(\App\Models\User::where('role', 'pegawai')->get() as $peg)
                                    <option value="{{ $peg->id }}">{{ $peg->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-bold text-slate-700 mb-2">Tanggal</label>
                            <input type="date" name="date" id="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-amber-500 transition-colors" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                            <select name="status" id="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-amber-500 transition-colors" required>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white px-5 py-3 rounded-xl font-bold transition-all shadow-sm">
                        <i class="fas fa-save mr-2"></i> Simpan Status Kehadiran
                    </button>
                </form>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- TABEL DATA ABSENSI --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-white">
                <h3 class="text-lg font-bold text-slate-800">
                    <i class="fas fa-list-ul text-amber-500 mr-2"></i>Data Kehadiran: 
                    <span class="text-amber-600">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                </h3>

                <a href="{{ route('export.excel', ['tanggal' => $tanggal, 'search' => request('search')]) }}" 
                   class="flex justify-center items-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 border border-emerald-200 px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300">
                    <i class="fas fa-file-excel"></i> Export ke Excel
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="text-xs uppercase text-slate-400 border-b border-slate-100 bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-center">No</th>
                            <th class="px-6 py-4 font-semibold">Tanggal</th>
                            <th class="px-6 py-4 font-semibold">Nama Pegawai</th>
                            <th class="px-6 py-4 font-semibold">Jabatan</th>
                            <th class="px-6 py-4 font-semibold">Jam Masuk</th>
                            <th class="px-6 py-4 font-semibold">Jam Keluar</th> 
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($absensi as $index => $absen)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($absen->date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $absen->user->name }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-600 py-1 px-3 rounded-lg text-xs font-semibold">
                                    {{ $absen->user->position ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($absen->check_in)
                                    <span class="text-slate-600 font-bold">
                                        {{ \Carbon\Carbon::parse($absen->check_in)->format('H:i') }} WIB
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            {{-- Tambah Data Jam Keluar --}}
                            <td class="px-6 py-4">
                                @if($absen->check_out)
                                    <span class="text-slate-600 font-bold">
                                        {{ \Carbon\Carbon::parse($absen->check_out)->format('H:i') }} WIB
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    // Ubah teks ke huruf kecil semua dan hapus spasi berlebih agar pengecekan lebih kebal
                                    $statusCek = strtolower(trim($absen->status));
                                @endphp

                                @if(str_contains($statusCek, 'tepat waktu'))
                                    <span class="bg-emerald-50 text-emerald-600 py-1.5 px-3 rounded-full text-xs font-bold border border-emerald-200">
                                        {{ $absen->status }}
                                    </span>
                                @elseif(str_contains($statusCek, 'terlambat'))
                                    <span class="bg-rose-50 text-rose-600 py-1.5 px-3 rounded-full text-xs font-bold border border-rose-200">
                                        {{ $absen->status }}
                                    </span>
                                @elseif(str_contains($statusCek, 'izin') || str_contains($statusCek, 'sakit'))
                                    <span class="bg-amber-50 text-amber-600 py-1.5 px-3 rounded-full text-xs font-bold border border-amber-200">
                                        {{ $absen->status }}
                                    </span>
                                @else
                                    <span class="bg-slate-100 text-slate-500 py-1.5 px-3 rounded-full text-xs font-bold border border-slate-200">
                                        {{ $absen->status ?? 'Tidak Hadir' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('absen.hapus', $absen->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absen ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition-colors border border-rose-100" title="Hapus Absen">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-slate-400 italic">Belum ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebarOverlay.classList.toggle('hidden');
            setTimeout(() => sidebarOverlay.classList.toggle('opacity-0'), 10);
            sidebar.classList.toggle('-translate-x-full');
        }

        hamburgerBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
        document.getElementById('closeSidebarBtn').addEventListener('click', toggleSidebar);
    </script>
</body>
</html>