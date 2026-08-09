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
                            <label class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                Jabatan / Posisi
                                <button type="button" onclick="openJabatanModal()" class="text-xs text-amber-600 hover:text-amber-700 font-bold flex items-center gap-1 normal-case">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </label>
                            <select name="jabatan_id" id="jabatan_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" required>
                                <option value="" disabled>-- Pilih Jabatan --</option>
                                @foreach($jabatans as $jab)
                                    <option value="{{ $jab->id }}" {{ old('jabatan_id', $pegawai->jabatan_id) == $jab->id ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                                @endforeach
                            </select>
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
