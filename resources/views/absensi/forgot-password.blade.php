<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen font-sans">
    
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold text-slate-800 mb-2">Lupa Password? 🔐</h1>
            <p class="text-sm text-slate-500">
                Masukkan email Anda yang terdaftar untuk membuat password baru.
            </p>
        </div>

        @if (session('reset_link'))
            <div class="bg-emerald-50 border border-emerald-200 p-5 mb-5 rounded-xl text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="font-bold text-emerald-800 mb-1">Email Ditemukan!</p>
                <p class="text-sm text-emerald-600 mb-4">Silakan klik tombol di bawah ini untuk melanjutkan.</p>
                <a href="{{ session('reset_link') }}" class="block w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-lg transition-colors shadow-md">
                    Ganti Password Sekarang
                </a>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-5 rounded-md text-sm">
                <p class="font-bold">Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if (!session('reset_link'))
            <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" required placeholder="contoh@gmail.com"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all outline-none"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-lg transition-colors duration-200 shadow-md">
                    Cari Akun Saya
                </button>
            </form>
        @endif

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-amber-600 transition-colors">
                &larr; Kembali ke halaman Login
            </a>
        </div>

    </div>

</body>
</html>
