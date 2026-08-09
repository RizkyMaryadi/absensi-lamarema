<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Wajah - Lamarema Fashion</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/face-api.js/dist/face-api.min.js"></script>

    <style>
        /* Desain Scrollbar Kecil */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Animasi transisi ganti kamera */
        video {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans relative selection:bg-amber-500 selection:text-white shadow-inner">

    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-100">
                <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo Lamarema" class="w-10 h-10 rounded-lg object-cover">
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-wide">Registrasi Wajah</h1>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-8 max-w-5xl">
        <div class="flex items-center gap-3 mb-8 anim-item">
            <a href="/" class="text-slate-400 hover:text-amber-500 transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-amber-50">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Daftarkan Wajah</h2>
        </div>

        @if(session('passwordBawaan'))
            <div class="bg-yellow-50 border-2 border-yellow-200 text-yellow-900 p-6 rounded-2xl mb-8 shadow-md">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0 border border-yellow-200">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Profil Pegawai Berhasil Dibuat!</h3>
                        <p class="text-sm mt-1.5 text-yellow-800 opacity-95">Sistem telah otomatis membuatkan password **acak (random)** demi keamanan. Berikut adalah akses login sementara untuk pegawai bernama <span class="font-extrabold text-gray-900">{{ $pegawai->name }}</span>. Silakan **CATAT SEKARANG**, karena informasi ini hanya akan muncul sekali!</p>
                        
                        <div class="mt-4 bg-white border border-yellow-100 p-4 rounded-xl flex items-center justify-between shadow-inner">
                            <div>
                                <p class="text-xs text-gray-400">Email Login:</p>
                                <p class="font-bold text-gray-900 text-sm">{{ $pegawai->email }}</p>
                            </div>
                            <div class="border-l border-gray-100 h-10"></div>
                            <div>
                                <p class="text-xs text-gray-400">Password Sementara:</p>
                                <p class="font-mono font-extrabold text-gray-900 text-lg bg-yellow-100 px-3 py-0.5 rounded shadow-inner">{{ session('passwordBawaan') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden flex flex-col md:flex-row shadow-inner">
            
            <div class="p-8 md:w-2/5 bg-slate-50 border-b md:border-b-0 md:border-r border-slate-100 flex flex-col">
                <div class="text-center mb-6 pb-6 border-b border-slate-100">
                    <div class="w-20 h-20 mx-auto rounded-full bg-amber-100 flex items-center justify-center text-amber-500 text-3xl mb-4 shadow-inner border border-amber-200 overflow-hidden relative">
                        <i class="fas fa-user-tag"></i>
                        <span class="absolute bottom-0 right-0 bg-blue-600 text-white w-6 h-6 flex items-center justify-center rounded-full text-[10px] border-2 border-white shadow-md"><i class="fas fa-camera"></i></span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">{{ $pegawai->name }}</h3>
                    <p class="text-sm font-semibold text-slate-500 mt-1 bg-white inline-block px-3 py-1 rounded-lg border border-slate-200 shadow-inner">{{ $pegawai->jabatan->nama_jabatan ?? 'Pegawai' }}</p>
                </div>

                <div class="mb-6 flex-grow">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-2.5 pl-1">Instruksi Registrasi Wajah:</h4>
                    <ul class="text-xs text-slate-600 space-y-2.5 bg-white p-4 rounded-xl border border-slate-100 shadow-inner">
                        <li class="flex items-start gap-2"><i class="fas fa-check text-emerald-500 mt-0.5"></i> Pastikan wajah terlihat jelas di tengah.</li>
                        <li class="flex items-start gap-2"><i class="fas fa-lightbulb text-amber-500 mt-0.5"></i> Pencahayaan cukup (tidak gelap).</li>
                    </ul>
                </div>

                <div id="statusAlert" class="mt-auto bg-blue-50 border border-blue-200 text-blue-600 px-4 py-3 rounded-xl text-sm font-medium flex items-start gap-2 shadow-sm animate__animated animate__fadeIn shadow-inner">
                    <i id="statusIcon" class="fas fa-spinner fa-spin mt-0.5"></i>
                    <span id="statusText">Menyiapkan sistem biometrik...</span>
                </div>
            </div>

            <div class="p-8 md:w-3/5 flex flex-col items-center justify-center bg-white relative">
                
                <div class="w-full max-w-md mb-5 z-20 sticky top-20">
                    <label for="cameraSelect" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 pl-1 flex items-center justify-between">
                        <span><i class="fas fa-video mr-1 text-slate-400"></i> Pilih Sumber Kamera Kantor</span>
                        <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded shadow-inner">Kios-Mode</span>
                    </label>
                    <div class="relative group">
                        <select id="cameraSelect" class="w-full pl-4 pr-10 py-3 border border-slate-300 rounded-xl bg-white text-sm focus:ring-1 focus:ring-amber-300 focus:border-amber-400 outline-none transition cursor-pointer shadow-sm active:scale-[0.98]">
                            <option value="" disabled selected>Memuat daftar kamera...</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 group-hover:text-amber-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                <div class="relative rounded-xl overflow-hidden bg-black shadow-lg w-full max-w-md aspect-[4/3] flex items-center justify-center border-4 border-slate-100 shadow-inner group">
                    <video id="video" autoplay muted playsinline class="w-full h-full object-cover transform scale-x-[-1] opacity-0"></video>
                    
                    <div class="absolute inset-0 flex items-center justify-center bg-black/70 text-gray-500 text-xl" id="video-overlay">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>

                <div class="mt-8 flex gap-4 w-full max-w-md border-t border-slate-100 pt-8">
                    <button id="btnCapture" disabled class="flex-1 bg-amber-500 hover:bg-amber-600 disabled:bg-slate-300 disabled:cursor-not-allowed text-white px-5 py-3.5 rounded-xl font-extrabold transition duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2.5 text-lg active:scale-95 group">
                        <i class="fas fa-camera-retro group-hover:scale-110 transition-transform"></i> Simpan Data Wajah
                    </button>
                    <a href="/" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3.5 rounded-xl font-bold transition duration-300 border active:scale-95">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const videoOverlay = document.getElementById('video-overlay'); // Tambahan: Overlay
        const btnCapture = document.getElementById('btnCapture');
        const cameraSelect = document.getElementById('cameraSelect'); 
        const statusAlert = document.getElementById('statusAlert');
        const statusText = document.getElementById('statusText');
        const statusIcon = document.getElementById('statusIcon');

        let currentStream = null;

        // ==========================================
        // 1. FUNGSI UNTUK MENDAPATKAN DAFTAR KAMERA
        // ==========================================
        async function getDevices() {
            try {
                // Meminta izin kamera pertama kali
                await navigator.mediaDevices.getUserMedia({ video: true });
                
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                cameraSelect.innerHTML = ''; 

                if (videoDevices.length === 0) {
                    cameraSelect.innerHTML = '<option value="">Tidak ada kamera ditemukan</option>';
                    return null;
                }

                videoDevices.forEach((device, index) => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Kamera ${index + 1}`; 
                    cameraSelect.appendChild(option);
                });

                return videoDevices[0].deviceId;

            } catch (err) {
                console.error("Gagal mendapat devices:", err);
                cameraSelect.innerHTML = '<option value="">Gagal memuat (Izin diblokir?)</option>';
                return null;
            }
        }

        // ==========================================
        // 2. FUNGSI MENYALAKAN KAMERA (BISA PILIH ID)
        // ==========================================
        async function startCamera(deviceId = null) {
            // Matikan stream sebelumnya
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            video.style.opacity = '0'; 
            videoOverlay.style.display = 'flex'; // Tambahan: Tampilkan overlay

            const constraints = {
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    ...(deviceId && { deviceId: { exact: deviceId } })
                }
            };

            try {
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                currentStream = stream;
                video.srcObject = stream;
                
                video.onloadedmetadata = () => {
                    videoOverlay.style.display = 'none'; // Tambahan: Sembunyikan overlay
                    video.style.opacity = '1'; 
                    updateStatus("Kamera Aktif. Silakan Posisikan Wajah.", "emerald");
                    btnCapture.disabled = false;
                };
            } catch (err) {
                console.error("Gagal buka kamera:", err);
                updateStatus("Error Kamera: " + err.message, "red");
            }
        }

        // ==========================================
        // 3. LOGIKA INUTIALISASI (Local Weights)
        // ==========================================
        async function init() {
            try {
                updateStatus("Memuat AI Models lokal (Weights)...", "blue")
                const modelPath = '/models'; 

                await Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath),
                    faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
                    faceapi.nets.faceRecognitionNet.loadFromUri(modelPath)
                ]);

                updateStatus("Mencari sumber kamera...", "blue");
                const defaultDeviceId = await getDevices();
                
                if (defaultDeviceId) {
                    await startCamera(defaultDeviceId);
                } else {
                    updateStatus("Error: Kamera tidak ditemukan.", "red");
                }

            } catch (err) {
                console.error("Init Error Lokal: ", err);
                updateStatus("Error Fatal AI: Pastikan weights ada di public/models.", "red");
            }
        }

        init();

        // ==========================================
        // 4. LOGIKA EVENT: GANTI KAMERA VIA DROPDOWN
        // ==========================================
        cameraSelect.addEventListener('change', (e) => {
            const selectedDeviceId = e.target.value;
            if (selectedDeviceId) {
                updateStatus("Beralih kamera...", "blue");
                btnCapture.disabled = true;
                startCamera(selectedDeviceId);
            }
        });

        // ==========================================
        // 5. LOGIKA KLIK TOMBOL SIMPAN WAJAH
        // ==========================================
        btnCapture.addEventListener('click', async () => {

            btnCapture.disabled = true;
            btnCapture.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            updateStatus('Menganalisa biometrik wajah...', 'blue');

            try {
                // Deteksi biometrik
                const detection = await faceapi.detectSingleFace(video)
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!detection) {
                    updateStatus('Wajah tidak terdeteksi! Pastikan wajah dekat & jelas.', 'red');
                    btnCapture.disabled = false;
                    btnCapture.innerHTML = '<i class="fas fa-camera-retro"></i> Simpan Data Wajah';
                    return;
                }

                updateStatus('Menyimpan data biometrik ke server...', 'blue');
                updateStatus('Menyimpan data biometrik ke server...', 'blue');
                
                // Tambahan: Capture foto mentah
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const photoBase64 = canvas.toDataURL('image/jpeg', 0.8);

                simpanKeDatabase(Array.from(detection.descriptor), photoBase64);

            } catch (error) {
                console.error("Proses Error:", error);
                updateStatus('Error AI saat memproses wajah.', 'red');
                btnCapture.disabled = false;
                btnCapture.innerHTML = '<i class="fas fa-camera-retro"></i> Simpan Data Wajah';
            }
        });

        // ==========================================
        // 6. LOGIKA SIMPAN KE DATABASE (FETCH)
        // ==========================================
        function simpanKeDatabase(descriptorArray, photoBase64) {
            fetch("{{ url('/simpan-wajah') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    pegawai_id: "{{ $pegawai->id }}",
                    face_descriptor: JSON.stringify(descriptorArray),
                    photo: photoBase64
                })
            }).then(res => res.json()).then(data => {
                if (data.status === 'success') {
                    updateStatus('Wajah Berhasil Didaftarkan! Mengalihkan...', 'emerald');
                    // Matikan kamera
                    if (currentStream) {
                        currentStream.getTracks().forEach(track => track.stop());
                    }
                    // Redirect ke dashboard admin setelah sukses
                    setTimeout(() => window.location.href = '/', 1500);
                } else {
                    updateStatus(data.message || 'Gagal menyimpan wajah ke server.', 'red');
                    btnCapture.disabled = false;
                    btnCapture.innerHTML = '<i class="fas fa-camera-retro"></i> Simpan Data Wajah';
                }
            }).catch(err => {
                console.error("Error Fetch:", err);
                updateStatus('Error jaringan/server.', 'red');
                btnCapture.disabled = false;
                btnCapture.innerHTML = '<i class="fas fa-camera-retro"></i> Simpan Data Wajah';
            });
        }

        // ==========================================
        // FUNGSI HELPER ALERT STATUS
        // ==========================================
        function updateStatus(message, color) {
            if (!statusAlert || !statusText) return;
            statusText.innerText = message;
            // Gunakan interpolation agar warna kelas Tailwind beradaptasi
            statusAlert.className = `mt-auto px-4 py-3 rounded-xl text-sm font-medium flex items-start gap-2 animate__animated animate__fadeIn bg-${color}-50 border border-${color}-200 text-${color}-600 shadow-inner`;
            statusIcon.className = color === 'blue' 
                ? 'fas fa-spinner fa-spin mt-0.5'
                : (color === 'emerald' ? 'fas fa-check-circle mt-0.5' : 'fas fa-exclamation-circle mt-0.5');
        }
    </script>
</body>
</html>
