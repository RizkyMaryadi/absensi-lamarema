<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pegawai - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 flex items-center justify-center h-screen font-sans relative">

    <div class="absolute top-5 right-5">
        <a href="#admin-login-section" onclick="toggleAdminMode()" id="btn-mode-admin" class="flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-yellow-600 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 transition-all">
            <i class="fas fa-user-shield"></i> Login Admin
        </a>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-20 h-20 mx-auto mb-4 object-contain">
            <h2 id="portal-title" class="text-2xl font-bold text-gray-800">Login Pegawai</h2>
            <p id="portal-subtitle" class="text-sm text-gray-500 mt-1">Silakan masuk untuk mengecek rekap absensi Anda</p>
        </div>

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 text-sm p-3 rounded-lg border border-red-200">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <!-- Ditambahkan autocomplete="email" -->
                    <input type="email" name="email" id="email" autocomplete="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5 outline-none" placeholder="Masukkan email Anda" required>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <!-- Ditambahkan autocomplete="current-password" -->
                    <input type="password" name="password" id="password" autocomplete="current-password" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5 outline-none" placeholder="••••••••" required>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1 pb-2">

                <a href="{{ route('password.request') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 hover:underline transition-all">
                    Lupa Password?
                </a>
            </div>
            <button type="submit" id="btn-submit" class="w-full text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all shadow-md hover:shadow-lg">
                Masuk ke Dashboard Pegawai
            </button>
        </form>

    </div> 
    
    <script>
        let isAdminMode = false;

        function toggleAdminMode() {
            isAdminMode = !isAdminMode;
            
            const title = document.getElementById('portal-title');
            const subtitle = document.getElementById('portal-subtitle');
            const btnSubmit = document.getElementById('btn-submit');
            const btnModeAdmin = document.getElementById('btn-mode-admin');
            const kioskSection = document.getElementById('kiosk-section');
            const emailInput = document.getElementById('email');

            if (isAdminMode) {
                // Switch ke Mode Admin
                title.innerText = "Login Admin";
                subtitle.innerText = "Panel login khusus pemilik & pengelola sistem";
                btnSubmit.innerText = "Masuk Sebagai Admin";
                btnSubmit.className = "w-full text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all shadow-md hover:shadow-lg";
                btnModeAdmin.innerHTML = '<i class="fas fa-users"></i> Login Pegawai';
                emailInput.placeholder = "admin@lamarema.com";
                
                // Sembunyikan tombol scan wajah kalau lagi mode login admin
                if (kioskSection) kioskSection.style.display = 'none';
            } else {
                // Kembali ke Mode Pegawai
                title.innerText = "Login Pegawai";
                subtitle.innerText = "Silakan masuk untuk mengecek rekap absensi Anda";
                btnSubmit.innerText = "Masuk ke Dashboard Pegawai";
                btnSubmit.className = "w-full text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition-all shadow-md hover:shadow-lg";
                btnModeAdmin.innerHTML = '<i class="fas fa-user-shield"></i> Login Admin';
                emailInput.placeholder = "Masukkan email Anda";
                
                // Munculkan kembali tombol scan jika di desktop
                if (kioskSection && window.innerWidth >= 768) {
                    kioskSection.style.display = 'block';
                }
            }
        }
    </script>

</body>
</html>
