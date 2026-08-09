<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen font-sans">
    
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg border border-slate-200 m-4">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold text-slate-800 mb-2">Buat Password Baru 🔑</h1>
            <p class="text-sm text-slate-500">
                Silakan ketik password baru untuk akun Anda.
            </p>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-5 rounded-md text-sm">
                <p class="font-bold">Gagal!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email Akun</label>
                <input type="email" name="email" value="{{ $email }}" readonly
                    class="w-full px-4 py-2 bg-slate-100 border border-slate-300 rounded-lg text-slate-500 cursor-not-allowed outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all outline-none">
                @error('password')
                    <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Ulangi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ketik ulang password baru"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all outline-none">
            </div>

            <button type="submit" 
                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-lg transition-colors duration-200 shadow-md mt-4">
                Simpan Password Baru
            </button>
        </form>

    </div>

</body>
</html>
