<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Absens Wajah - Lamarema Fashion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* --- Definisi Tampilan & Animasi --- */
        .glass-lite {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        video { object-fit: cover; border-radius: 2rem; background: #000; }
        canvas { position: absolute; top: 0; left: 0; pointer-events: none; z-index: 20; }
        
        /* Animasi garis scan kuning */
        @keyframes scan {
            0% { top: 0; opacity: 0.2; }
            50% { opacity: 1; }
            100% { top: 100%; opacity: 0.2; }
        }
        .animate-scan {
            animation: scan 3s linear infinite;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col items-center h-screen relative font-sans overflow-hidden">
    
    <nav class="relative z-50 w-full p-6 flex justify-between items-center bg-white border-b border-slate-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                <img src="{{ asset('images/logo-lamarema.png') }}" alt="Logo" class="w-12 h-12 rounded-xl object-cover">
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-none uppercase">Kiosk Absensi</h1>
                <p id="liveClock" class="text-amber-600 font-bold tracking-widest uppercase text-[10px] mt-1"></p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative hidden md:block">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-amber-500">
                    <i class="fas fa-camera text-sm"></i>
                </div>
                <select id="cameraSelect" class="bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl focus:ring-amber-500 focus:border-amber-500 block w-64 pl-10 p-2.5 outline-none shadow-sm cursor-pointer transition-all">
                    <option value="">Memuat kamera...</option>
                </select>
            </div>
            
            <a href="/" class="bg-white hover:bg-slate-50 text-slate-600 px-5 py-2.5 rounded-xl transition-all border border-slate-200 flex items-center gap-2 font-bold text-sm shadow-sm active:scale-95">
                <i class="fas fa-arrow-left text-amber-500"></i> Dashboard
            </a>
        </div>
    </nav>

    <main class="relative z-10 flex-grow flex flex-col items-center justify-center p-4 w-full gap-8">
        
        <div class="relative w-full max-w-2xl">
            <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-[6px] border-white bg-black aspect-[4/3]">
                <video id="video" width="720" height="540" autoplay muted playsinline class="w-full h-full"></video>
                <canvas id="overlay" class="absolute top-0 left-0 w-full h-full"></canvas>
                
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-400 to-transparent shadow-[0_0_20px_rgba(251,191,36,0.8)] z-30 animate-scan"></div>

                <div id="statusLabel" class="absolute bottom-8 left-1/2 -translate-x-1/2 glass-lite border border-slate-100 px-8 py-3 rounded-2xl text-slate-800 font-bold shadow-xl flex items-center gap-3 whitespace-nowrap z-40">
                    <div class="w-2 h-2 bg-amber-500 rounded-full animate-ping" id="statusDot"></div>
                    <span id="statusText">Mencari Wajah...</span>
                </div>

                <div id="loading" class="absolute inset-0 flex items-center justify-center bg-white z-50 transition-opacity duration-500">
                    <div class="text-center">
                        <div class="relative w-20 h-20 mx-auto mb-4">
                            <div class="absolute inset-0 border-4 border-amber-100 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <p class="text-lg font-bold text-slate-800">Sinkronisasi Wajah...</p>
                        <p class="text-sm text-slate-500 uppercase tracking-widest font-medium">Mohon Tunggu</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-lite max-w-md w-full p-5 rounded-3xl shadow-lg border border-slate-100 flex items-center gap-5">
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 shadow-inner flex-shrink-0">
                <i class="fas fa-face-smile text-2xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 leading-tight">Hadapkan Wajah Anda</h2>
                <p class="text-slate-500 text-xs mt-1 font-medium">Posisikan wajah di tengah kamera untuk verifikasi otomatis (Masuk/Pulang).</p>
            </div>
        </div>
    </main>

    <footer class="relative z-10 p-6 text-center">
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em]">
            &copy; 2026 Lamarema Fashion
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    
    <script>
        const video = document.getElementById('video');
        const overlay = document.getElementById('overlay');
        const cameraSelect = document.getElementById('cameraSelect');
        const loadingDiv = document.getElementById('loading');
        const statusText = document.getElementById('statusText');
        const statusDot = document.getElementById('statusDot');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let faceMatcher = null;
        let isProcessing = false; // Kunci utama scan wajah
        let detectionInterval = null;

        // --- VARIABEL UNTUK MENCEGAH SPAM WAJAH YANG SAMA ---
        let lastSuccessfulUserId = null;  
        let lastSuccessTimestamp = 0;    
        const NOTIFICATION_COOLDOWN_MS = 6000; // Waktu abaikan wajah yang sama (6 detik)

        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('liveClock').innerText = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateTime, 1000);
        updateTime();

        Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri("{{ asset('models') }}"),
            faceapi.nets.faceLandmark68Net.loadFromUri("{{ asset('models') }}"),
            faceapi.nets.faceRecognitionNet.loadFromUri("{{ asset('models') }}"),
        ]).then(startSystem).catch(err => Swal.fire('Error', 'Gagal memuat model AI.', 'error'));

        async function startSystem() {
            statusText.innerText = "Memuat data pegawai...";
            const labeledDescriptors = await loadLabeledImages(); 
            
            if (labeledDescriptors.length > 0) {
                faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);
                statusText.innerText = "Mencari Wajah...";
            } else {
                statusText.innerText = "Data pegawai kosong.";
                Swal.fire('Peringatan', 'Tidak ada data wajah pegawai terdaftar.', 'warning');
            }
            
            await initCameraList(); 
            loadingDiv.style.opacity = '0';
            setTimeout(() => loadingDiv.classList.add('hidden'), 500);
        }

        async function loadLabeledImages() {
            try {
                const response = await fetch('/get-pegawai');
                const users = await response.json();
                if(!users || users.length === 0) return [];
                return Promise.all(
                    users.map(async user => {
                        if(!user.face_descriptor) return null;
                        try {
                            const descriptors = [new Float32Array(JSON.parse(user.face_descriptor))];
                            return new faceapi.LabeledFaceDescriptors(user.name + "|" + user.id, descriptors);
                        } catch(e) { return null; }
                    })
                ).then(results => results.filter(el => el !== null));
            } catch (error) { return []; }
        }

        async function initCameraList() {
            try {
                await navigator.mediaDevices.getUserMedia({ video: true });
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                
                cameraSelect.innerHTML = '';
                if (videoDevices.length === 0) {
                    const option = document.createElement('option');
                    option.text = "Kamera tidak ditemukan";
                    cameraSelect.appendChild(option);
                    return;
                }
                videoDevices.forEach(device => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Kamera ${cameraSelect.length + 1}`;
                    cameraSelect.appendChild(option);
                });
                startCamera(videoDevices[0].deviceId);
            } catch (err) { Swal.fire('Error', 'Izin kamera ditolak.', 'error'); }
        }

        function startCamera(deviceId) {
            const constraints = { video: { deviceId: { exact: deviceId }, width: { ideal: 960 }, height: { ideal: 720 } } };
            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => { video.srcObject = stream; })
                .catch(err => { Swal.fire('Error', 'Gagal mengakses kamera.', 'error'); });
        }

        cameraSelect.addEventListener('change', (e) => startCamera(e.target.value));

        video.addEventListener('play', () => {
            if(detectionInterval) clearInterval(detectionInterval);
            
            const displaySize = { width: video.width, height: video.height };
            faceapi.matchDimensions(overlay, displaySize);

            detectionInterval = setInterval(async () => {
                if (isProcessing || !faceMatcher || video.paused || video.ended) return;

                const detection = await faceapi.detectSingleFace(video).withFaceLandmarks().withFaceDescriptor();
                
                const ctx = overlay.getContext('2d');
                ctx.clearRect(0, 0, overlay.width, overlay.height);

                if (detection) {
                    const resizedDetections = faceapi.resizeResults(detection, displaySize);
                    const bestMatch = faceMatcher.findBestMatch(detection.descriptor);
                    const box = resizedDetections.detection.box;
                    
                    if (bestMatch.label !== 'unknown') {
                        const [name, id] = bestMatch.label.split('|');
                        const similarity = Math.round((1 - bestMatch.distance) * 100);
                        const text = `${name} (${similarity}%)`;
                        
                        const drawBox = new faceapi.draw.DrawBox(box, { label: text, boxColor: '#10b981' });
                        drawBox.draw(overlay);

                        catatKehadiran(id, name);
                    } else {
                        const drawBox = new faceapi.draw.DrawBox(box, { label: 'Tidak Dikenal', boxColor: '#ef4444' });
                        drawBox.draw(overlay);

                        statusText.innerText = "Wajah Tidak Dikenal";
                        statusText.classList.add('text-red-600');
                        setTimeout(() => {
                            if(!isProcessing) {
                                statusText.innerText = "Mencari Wajah...";
                                statusText.classList.remove('text-red-600');
                            }
                        }, 1000);
                    }
                }
            }, 250); 
        });

        video.addEventListener('pause', () => clearInterval(detectionInterval));
        video.addEventListener('ended', () => clearInterval(detectionInterval));

        // FUNGSI JEDA (COOLDOWN) SETIAP SELESAI NOTIFIKASI
        function berikanJedaScan(detik) {
            let sisaDetik = detik;
            statusText.classList.add('text-amber-600');
            statusDot.classList.remove('bg-amber-500');
            statusDot.classList.add('bg-slate-400'); // Matikan lampu hijau animasi
            
            // Tampilkan hitung mundur di layar agar user tahu sedang jeda
            statusText.innerText = `Kamera jeda ${sisaDetik} detik...`;

            const hitungMundur = setInterval(() => {
                sisaDetik--;
                if (sisaDetik > 0) {
                    statusText.innerText = `Kamera jeda ${sisaDetik} detik...`;
                } else {
                    // Waktu jeda habis, BUKA KUNCI SCAN
                    clearInterval(hitungMundur);
                    const ctx = overlay.getContext('2d');
                    ctx.clearRect(0, 0, overlay.width, overlay.height);
                    isProcessing = false; 
                    statusText.innerText = "Mencari Wajah...";
                    statusText.classList.remove('text-amber-600');
                    statusDot.classList.remove('bg-slate-400');
                    statusDot.classList.add('bg-amber-500'); // Nyalakan lagi lampu animasi
                }
            }, 1000);
        }
        // -----------------------------------------------------------

        async function catatKehadiran(userId, name) {
            isProcessing = true; // KUNCI SCAN KETIKA MULAI VERIFIKASI
            statusText.innerText = "Memverifikasi: " + name;
            statusText.classList.remove('text-red-600');

            Swal.fire({
                title: 'Memverifikasi...',
                text: `Halo ${name}, mohon tunggu.`,
                imageUrl: "{{ asset('images/loading-face.gif') }}",
                imageWidth: 80, imageHeight: 80,
                showConfirmButton: false,
                allowOutsideClick: false,
                background: '#ffffff',
                color: '#1f2937',
                willOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch("{{ url('/catat-kehadiran') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ pegawai_id: userId })
                });
                
                const data = await response.json();
                Swal.close();

                // 1. JIKA SUKSES
                if (data.status === 'success') {
                    lastSuccessfulUserId = userId; 
                    lastSuccessTimestamp = Date.now(); 

                    Swal.fire({
                        icon: 'success',
                        title: 'Absensi Berhasil',
                        text: data.message,
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        // SETELAH POP-UP SUKSES DITUTUP -> BERI JEDA 3 DETIK
                        didClose: () => { berikanJedaScan(3); } 
                    });
                } 
                // 2. JIKA WARNING (Sudah Absen / Belum Jam Pulang)
                else if (data.status === 'warning') {
                    const now = Date.now();
                    const rejectedUserId = data.pegawai_id || userId; 
                    const isSameUser = parseInt(rejectedUserId) === parseInt(lastSuccessfulUserId);
                    const isWithinCooldown = (now - lastSuccessTimestamp) < NOTIFICATION_COOLDOWN_MS;

                    // Cegah spam pop-up kalau orang yang baru absen nge-freeze di depan kamera
                    if (isSameUser && isWithinCooldown) {
                        berikanJedaScan(2); // Langsung kasih jeda hitung mundur 2 detik tanpa pop-up Swal
                        return; 
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: data.message,
                        timer: 4000,
                        timerProgressBar: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Oke',
                        confirmButtonColor: '#f59e0b',
                        // SETELAH POP-UP WARNING DITUTUP -> BERI JEDA 4 DETIK BIAR ORANGNYA PERGI
                        didClose: () => { berikanJedaScan(4); } 
                    });
                } 
                // 3. JIKA ERROR SERVER
                else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        // SETELAH POP-UP ERROR DITUTUP -> BERI JEDA 3 DETIK
                        didClose: () => { berikanJedaScan(3); }
                    });
                }
            } catch (error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal koneksi ke server.',
                    didClose: () => { berikanJedaScan(4); } // Jeda jika internet putus
                });
            }
        }
    </script>
</body>
</html>
