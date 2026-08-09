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
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5 flex justify-between items-center">
                                Posisi / Jabatan
                                <button type="button" onclick="openJabatanModal()" class="text-xs text-amber-600 hover:text-amber-700 font-bold flex items-center gap-1">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </label>
                            <select name="jabatan_id" id="jabatan_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition bg-white" required>
                                <option value="" disabled selected>-- Pilih Jabatan --</option>
                                @foreach($jabatans as $jab)
                                    <option value="{{ $jab->id }}" {{ old('jabatan_id') == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                                @endforeach
                            </select>
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
    <!-- Modal Tambah Jabatan -->
    <div id="jabatanModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="jabatanModalContent">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800"><i class="fas fa-briefcase text-amber-500 mr-2"></i> Tambah Jabatan Baru</h3>
                <button type="button" onclick="closeJabatanModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Jabatan</label>
                    <input type="text" id="new_jabatan_name" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-amber-200 focus:border-amber-400 outline-none transition" placeholder="Misal: Manager HRD">
                    <p id="jabatanError" class="text-xs text-rose-500 mt-1 hidden"></p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeJabatanModal()" class="px-4 py-2 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="button" onclick="simpanJabatan()" class="px-4 py-2 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl transition shadow-md">Simpan Jabatan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openJabatanModal() {
            const modal = document.getElementById('jabatanModal');
            const content = document.getElementById('jabatanModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.getElementById('new_jabatan_name').focus();
        }

        function closeJabatanModal() {
            const modal = document.getElementById('jabatanModal');
            const content = document.getElementById('jabatanModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('new_jabatan_name').value = '';
                document.getElementById('jabatanError').classList.add('hidden');
            }, 300);
        }

        async function simpanJabatan() {
            const nama = document.getElementById('new_jabatan_name').value;
            const errorEl = document.getElementById('jabatanError');
            
            if(!nama.trim()) {
                errorEl.textContent = 'Nama jabatan tidak boleh kosong';
                errorEl.classList.remove('hidden');
                return;
            }

            try {
                const response = await fetch('{{ route("jabatan.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nama_jabatan: nama })
                });
                
                const data = await response.json();
                
                if(response.ok) {
                    // Tambahkan opsi baru ke dropdown
                    const select = document.getElementById('jabatan_id');
                    const option = document.createElement('option');
                    option.value = data.jabatan.id;
                    option.textContent = data.jabatan.nama_jabatan;
                    option.selected = true;
                    select.appendChild(option);
                    
                    closeJabatanModal();
                } else {
                    errorEl.textContent = data.message || 'Gagal menyimpan jabatan';
                    errorEl.classList.remove('hidden');
                }
            } catch (error) {
                errorEl.textContent = 'Terjadi kesalahan jaringan';
                errorEl.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
