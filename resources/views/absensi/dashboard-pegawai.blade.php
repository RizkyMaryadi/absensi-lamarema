<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased selection:bg-yellow-500 selection:text-white">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-10">
            <div class="flex justify-between h-16 relative">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-10 h-10 object-contain rounded-lg shadow-inner">
                    <span class="font-bold text-lg text-gray-900 tracking-tight flex items-center gap-1.5">
                        <i class="fas fa-users-cog text-yellow-600 hidden sm:inline"></i> Portal Pegawai
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize flex items-center justify-end gap-1">
                            <i class="fas fa-id-badge text-yellow-500"></i> Role: {{ auth()->user()->role }}
                        </p>
                    </div>
                    <button type="button" onclick="openLogoutModal()" class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-sm font-medium transition transform active:scale-95 shadow-inner border border-red-100 cursor-pointer">
                        <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-10 py-8">
        
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-3xl p-7 text-white shadow-lg mb-9 relative overflow-hidden transform transition hover:shadow-xl">
            <div class="absolute -right-8 -bottom-10 opacity-10 text-9xl">
                <i class="fas fa-gem"></i>
            </div>
            <p class="text-xs sm:text-sm font-semibold uppercase tracking-wider opacity-80 z-10 flex items-center gap-2"><i class="fas fa-wave-square"></i> Selamat Datang Kembali 👋</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-1.5 z-10 tracking-tight">{{ auth()->user()->name }}</h1>
            <p class="text-sm mt-1 opacity-95 z-10 font-medium">Hari ini: <span class="font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span></p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-2xl mb-7 text-sm font-medium flex items-center gap-3 shadow-md animate__animated animate__fadeIn">
                <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-7 text-sm font-medium shadow-md">
                <p class="font-bold mb-1.5 flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Gagal memperbarui keamanan akun:</p>
                <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 transform transition hover:shadow-lg hover:border-gray-200">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2"><i class="fas fa-user-clock text-yellow-600"></i> Status Presensi Hari Ini:</h3>
                    @if($absensiHariIni->count() > 0)
                        @foreach($absensiHariIni as $absen)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-5 text-center">
                                <div class="bg-green-50/50 p-4 rounded-2xl border border-green-100 transform transition hover:scale-105 active:scale-95 shadow-inner group">
                                    <p class="text-xs text-green-600 font-semibold uppercase tracking-wide flex items-center justify-center gap-1"><i class="fas fa-sign-in-alt text-gray-400 group-hover:text-green-500"></i> Jam Masuk Kantor</p>
                                    <p class="text-2xl font-extrabold text-green-700 mt-1.5 tracking-tight group-hover:text-3xl transition-all">{{ $absen->check_in ?? '--:--' }}</p>
                                </div>
                                <div class="bg-red-50/50 p-4 rounded-2xl border border-red-100 transform transition hover:scale-105 active:scale-95 shadow-inner group">
                                    <p class="text-xs text-red-600 font-semibold uppercase tracking-wide flex items-center justify-center gap-1"><i class="fas fa-sign-out-alt text-gray-400 group-hover:text-red-500"></i> Jam Pulang Toko</p>
                                    <p class="text-2xl font-extrabold text-red-700 mt-1.5 tracking-tight group-hover:text-3xl transition-all">{{ $absen->check_out ?? '--:--' }}</p>
                                </div>
                                <div class="col-span-2 sm:col-span-1 bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex flex-col justify-center items-center transform transition hover:scale-105 active:scale-95 shadow-inner group">
                                    <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide flex items-center gap-1"><i class="fas fa-star text-gray-400 group-hover:text-blue-500"></i> Keterangan</p>
                                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-600 text-white mt-2 capitalize shadow-sm">
                                        {{ $absen->status ?? 'Hadir' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5 bg-amber-50 rounded-xl border border-amber-100 text-amber-700 text-sm font-medium flex items-center justify-center gap-2 shadow-inner">
                            <i class="fas fa-exclamation-circle text-lg"></i> Anda belum melakukan scan wajah. Silakan presensi di **Mesin Absen Kantor** Lamarema.
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2.5 pl-1">
                        <i class="fas fa-history text-yellow-600"></i> Histori Rekap Absensi Bulanan Saya
                    </h2>
                    
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden transform transition hover:shadow-lg hover:border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-semibold uppercase tracking-wider">
                                    <tr>
                                        <th class="p-5 flex items-center gap-2"><i class="fas fa-calendar-day"></i> Tanggal Presensi</th>
                                        <th class="p-5"><i class="fas fa-sign-in-alt text-green-500"></i> Jam Masuk</th>
                                        <th class="p-5"><i class="fas fa-sign-out-alt text-red-500"></i> Jam Pulang</th>
                                        <th class="p-5 flex items-center gap-1.5"><i class="fas fa-tag text-blue-500"></i> Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-gray-700">
                                    @forelse($rekapAbsenPegawai as $rekap)
                                        <tr class="hover:bg-gray-50/50 transition-colors group">
                                            <td class="p-5 font-semibold text-gray-900 flex flex-col sm:flex-row sm:items-center sm:gap-1.5">
                                                <span class="sm:hidden text-xs text-gray-400 font-normal">Tgl:</span> 
                                                {{ \Carbon\Carbon::parse($rekap->created_at)->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="p-5 text-green-600 font-extrabold whitespace-nowrap text-lg sm:text-sm group-hover:scale-105 transition-transform">
                                                {{ $rekap->check_in ?? '--:--' }}
                                            </td>
                                            <td class="p-5 text-red-600 font-extrabold whitespace-nowrap text-lg sm:text-sm group-hover:scale-105 transition-transform">
                                                {{ $rekap->check_out ?? '--:--' }}
                                            </td>
                                            <td class="p-5">
                                                <span class="px-3 py-1 text-xs rounded-full font-bold uppercase capitalize bg-green-100 text-green-800 shadow-inner group-hover:shadow transition-shadow">
                                                    {{ $rekap->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-12 text-center text-gray-400 flex flex-col items-center justify-center gap-3">
                                                <i class="fas fa-folder-open text-4xl animate-pulse"></i>
                                                Belum ada riwayat absensi bulanan pada akun Anda.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2.5 pl-1">
                        <i class="fas fa-file-signature text-yellow-600"></i> Pengajuan Izin / Sakit / Cuti
                    </h2>
                    
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 transform transition hover:shadow-lg">
                        <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5"><i class="fas fa-tags text-gray-400"></i> Jenis Pengajuan</label>
                                    <select name="jenis" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-yellow-400" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Izin">Izin</option>
                                        <option value="Sakit">Sakit</option>
                                        <option value="Cuti">Cuti</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5"><i class="fas fa-camera text-gray-400"></i> Bukti Foto (Opsional)</label>
                                    <input type="file" name="bukti_foto" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-yellow-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5"><i class="fas fa-calendar-alt text-gray-400"></i> Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-yellow-400" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5"><i class="fas fa-calendar-alt text-gray-400"></i> Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-yellow-400" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5"><i class="fas fa-align-left text-gray-400"></i> Alasan Lengkap</label>
                                    <textarea name="alasan" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 outline-none focus:border-yellow-400" placeholder="Jelaskan alasan izin / cuti..." required></textarea>
                                </div>
                            </div>
                            <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                            </button>
                        </form>

                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="text-sm font-bold text-gray-800 mb-4"><i class="fas fa-list text-gray-400"></i> Riwayat Pengajuan Saya</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-sm">
                                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                        <tr>
                                            <th class="p-3">Tanggal</th>
                                            <th class="p-3">Jenis</th>
                                            <th class="p-3">Alasan</th>
                                            <th class="p-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 text-gray-700">
                                        @forelse($riwayatPengajuan as $riwayat)
                                            <tr>
                                                <td class="p-3 font-medium whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($riwayat->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($riwayat->tanggal_selesai)->format('d/m/Y') }}
                                                </td>
                                                <td class="p-3"><span class="font-bold text-blue-600">{{ $riwayat->jenis }}</span></td>
                                                <td class="p-3 text-xs">{{ $riwayat->alasan }}</td>
                                                <td class="p-3">
                                                    @if($riwayat->status === 'Disetujui')
                                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold uppercase">Disetujui</span>
                                                    @elseif($riwayat->status === 'Ditolak')
                                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-bold uppercase">Ditolak</span>
                                                    @else
                                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[10px] font-bold uppercase">Menunggu</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="p-6 text-center text-gray-400 italic text-xs">Belum ada riwayat pengajuan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border-2 border-gray-100 shadow-xl p-7 space-y-5 sticky top-24 transform transition hover:shadow-2xl hover:border-yellow-200 group">
                <div class="border-b border-gray-100 pb-5 relative">
                    
                    @if(Hash::needsRehash(auth()->user()->password))
                        <span class="absolute -top-1 -right-1 flex h-4 w-4">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[9px] text-white font-bold items-center justify-center shadow-inner">!</span>
                        </span>
                    @endif

                    <h3 class="text-xl font-extrabold text-gray-800 flex items-center gap-3">
                        <i class="fas fa-user-shield text-yellow-600 text-2xl group-hover:scale-110 transition-transform"></i> Manajemen Keamanan Akun
                    </h3>
                    <p class="text-xs text-gray-400 mt-1.5">Disarankan ganti password bawaan demi privasi rekap data Anda.</p>
                </div>

                <form action="{{ route('profil.update') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5"><i class="fas fa-id-card text-gray-400"></i> Nama Pegawai</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 outline-none focus:ring-1 focus:ring-yellow-500 shadow-inner group-focus-within:border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5"><i class="fas fa-envelope text-gray-400"></i> Email Portal Akun</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 outline-none focus:ring-1 focus:ring-yellow-500 shadow-inner group-focus-within:border-gray-300" required>
                    </div>

                    <div class="border-t border-dashed border-gray-100 my-5 pt-5 relative">
                        <span class="absolute -top-3.5 left-6 bg-white px-3 text-xs font-medium text-gray-400 uppercase tracking-wide flex items-center gap-1"><i class="fas fa-key text-yellow-500"></i> Ganti Password</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center justify-between gap-1.5">
                            <span><i class="fas fa-lock text-gray-400"></i> Password Baru</span>
                            @if(Hash::needsRehash(auth()->user()->password))
                                <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2.5 py-1 rounded-full shadow-inner animate-pulse flex items-center gap-1"><i class="fas fa-sign-in-alt animate-ping"></i> Wajib Ganti!</span>
                            @else
                                <span class="text-[10px] text-gray-400 font-medium">Opsional</span>
                            @endif
                        </label>
                        <input type="password" name="password" placeholder="Buat password baru Anda" class="w-full bg-gray-50 border border-gray-200 focus:border-yellow-300 rounded-xl px-4 py-3 text-sm outline-none focus:ring-1 focus:ring-yellow-500 transition shadow-sm" @if(Hash::needsRehash(auth()->user()->password)) required @endif>
                        <p class="text-[10px] text-gray-400 mt-1.5 flex items-center gap-1"><i class="fas fa-info-circle"></i> *Minimal 8 karakter unik (Huruf & Angka)</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5 flex items-center gap-1.5"><i class="fas fa-lock text-gray-400"></i> Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ketik ulang password baru Anda" class="w-full bg-gray-50 border border-gray-200 focus:border-yellow-300 rounded-xl px-4 py-3 text-sm outline-none focus:ring-1 focus:ring-yellow-500 transition shadow-sm" @if(Hash::needsRehash(auth()->user()->password)) required @endif>
                    </div>

                    <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-extrabold py-4 rounded-xl text-sm transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2.5 transform active:scale-95 group border-2 border-yellow-700/50">
                        <i class="fas fa-save group-hover:scale-110 transition-transform text-lg text-yellow-100"></i> SIMPAN PERUBAHAN KEAMANAN
                    </button>
                </form>
            </div>

        </div>
    </main>

    <div id="logoutModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/50 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-gray-100 transform transition-all scale-100 duration-300">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 text-red-600 mb-4 animate-bounce">
                    <i class="fas fa-sign-out-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 mb-2">Konfirmasi Keluar</h3>
                <p class="text-sm text-gray-500 mb-6 font-medium">Apakah Anda yakin ingin keluar dari Portal Pegawai Lamarema Fashion?</p>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeLogoutModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors duration-200 cursor-pointer">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1 m-0">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-600/20 transition-colors duration-200 cursor-pointer">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
        }
        
        // Menutup pop-up secara otomatis jika pengguna mengklik area luar kotak putih
        window.onclick = function(event) {
            const modal = document.getElementById('logoutModal');
            if (event.target == modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
