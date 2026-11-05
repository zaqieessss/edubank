@extends('layouts.app')

@section('title', 'Scan QR Kartu Tabungan Siswa')
@section('page_title', 'Scanner Kartu Siswa')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Konsisten & Rapi Bento Style) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('teller.dashboard') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Dashboard Loket">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pemindai Kartu Siswa</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    Scan QR Tabungan Siswa
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span id="camera-badge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-slate-400" id="camera-dot"></span>
                <span id="camera-badge-text">Menyiapkan Kamera...</span>
            </span>
        </div>
    </div>

    <!-- Scanner Bento Box (Sejajar & Fluid dengan Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[460px]">
        
        <div class="space-y-4 max-w-xl mx-auto w-full">
            <div class="text-center space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                    Pemindai Kartu Tabungan Digital
                </div>
                <h2 class="text-lg font-extrabold text-slate-900">Arahkan Kamera ke QR Code Kartu Siswa</h2>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Posisikan QR Code di kartu tabungan atau layar smartphone siswa di dalam area pandang kamera.
                </p>
            </div>

            <!-- Insecure context alert (hidden by default) -->
            <div id="insecure-context-alert" class="hidden p-3.5 bg-amber-50 border border-amber-200/80 rounded-2xl text-xs text-amber-800 space-y-1">
                <div class="font-bold flex items-center gap-1.5 text-amber-900">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Akses Kamera Membutuhkan Koneksi Aman
                </div>
                <p class="leading-relaxed">
                    Browser modern (Chrome/Edge) hanya mengizinkan akses webcam pada alamat <strong>localhost</strong> atau <strong>HTTPS</strong>. Jika Anda membuka via virtual host HTTP (misal: <code>http://edubank.test</code>), buka website ini melalui: <a id="localhost-link" href="#" class="font-bold underline text-blue-700">http://localhost:8000</a> atau gunakan form manual di bawah.
                </p>
            </div>

            <!-- Camera Viewport Container (Terkunci Sempurna di dalam Card, Anti Offside) -->
            <div class="relative w-full max-w-sm h-60 sm:h-64 mx-auto bg-slate-950 rounded-2xl overflow-hidden border-2 border-slate-800 shadow-inner flex items-center justify-center shrink-0">
                <!-- Video target element -->
                <div id="reader" class="w-full h-full overflow-hidden flex items-center justify-center"></div>

                <!-- Overlay Placeholder / Loading State -->
                <div id="camera-loading" class="p-6 text-center text-slate-400 space-y-2 absolute inset-0 flex flex-col items-center justify-center bg-slate-950 z-20">
                    <svg class="w-8 h-8 mx-auto animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <p class="text-xs font-medium">Mengaktifkan sensor kamera...</p>
                    <p class="text-[11px] text-slate-500">Klik "Izinkan/Allow" pada notifikasi browser jika muncul</p>
                </div>
                
                <!-- Scan Overlay Frame -->
                <div id="scan-frame" class="hidden pointer-events-none absolute inset-0 flex items-center justify-center z-10">
                    <div class="w-40 h-40 border-2 border-blue-400/90 rounded-2xl relative shadow-[0_0_0_9999px_rgba(15,23,42,0.45)]">
                        <div class="absolute -top-1 -left-1 w-5 h-5 border-t-4 border-l-4 border-blue-400 rounded-tl-lg"></div>
                        <div class="absolute -top-1 -right-1 w-5 h-5 border-t-4 border-r-4 border-blue-400 rounded-tr-lg"></div>
                        <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-4 border-l-4 border-blue-400 rounded-bl-lg"></div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-4 border-r-4 border-blue-400 rounded-br-lg"></div>
                        <div class="w-full h-0.5 bg-blue-400/80 shadow-[0_0_8px_rgba(96,165,250,0.8)] absolute top-0 animate-[scanLaser_2.2s_ease-in-out_infinite]"></div>
                    </div>
                </div>
            </div>

            <!-- Controls & Camera Selector (Tertata Rapi & Jauh dari Border Card) -->
            <div class="flex flex-col items-center justify-center gap-2.5 pt-2 max-w-sm mx-auto w-full">
                <select id="camera-select" class="hidden text-xs bg-slate-50 border border-slate-200 text-slate-700 rounded-xl px-3 py-2 w-full focus:ring-2 focus:ring-blue-500/20 text-center">
                    <option value="">Pilih Kamera...</option>
                </select>

                <div class="flex flex-wrap items-center justify-center gap-2.5 w-full">
                    <button type="button" id="btn-toggle-camera" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Ulangi Kamera</span>
                    </button>
                    
                    <!-- Scan from File Fallback -->
                    <label for="qr-input-file" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold rounded-xl transition-colors cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Scan dari Berkas QR</span>
                    </label>
                    <input type="file" id="qr-input-file" accept="image/*" class="hidden">
                </div>
            </div>

            <!-- Scan Status Feedback -->
            <div id="scan-status" class="text-center text-xs text-slate-500 font-medium min-h-[20px] px-2">
                Menghubungkan ke modul kamera...
            </div>
        </div>

        <!-- Fallback Manual Input -->
        <div class="pt-4 border-t border-slate-100 max-w-xl mx-auto w-full">
            <div class="text-center mb-2.5">
                <p class="text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                    Atau Masukkan Nomor Rekening / NIS Siswa Secara Manual
                </p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Format No. Rekening: <span class="font-mono font-bold text-slate-600">RPL-29-0001</span> atau <span class="font-mono font-bold text-slate-600">AKL-27-0001</span>
                </p>
            </div>
            <form id="manualForm" method="POST" action="{{ route('teller.transactions.search') }}" class="flex items-center gap-2 max-w-md mx-auto">
                @csrf
                <div class="relative flex-1">
                    <input type="text" 
                           id="queryInput" 
                           name="query" 
                           placeholder="Contoh: RPL-29-0001 atau NIS: 18910" 
                           required 
                           class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-mono font-bold focus:bg-white focus:ring-2 focus:ring-blue-500/20 uppercase tracking-wide">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow shrink-0">
                    Buka Loket
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes scanLaser {
    0%, 100% { top: 4%; }
    50% { top: 94%; }
}
#reader {
    width: 100% !important;
    height: 100% !important;
    max-height: 256px !important;
    overflow: hidden !important;
    border: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 1rem !important;
    position: relative !important;
    padding: 0 !important;
    margin: 0 !important;
}
#reader__scan_region {
    width: 100% !important;
    height: 100% !important;
    max-height: 256px !important;
    overflow: hidden !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #020617 !important;
    border-radius: 1rem !important;
    min-height: unset !important;
}
#reader video,
#reader__scan_region video {
    width: 100% !important;
    height: 100% !important;
    max-height: 256px !important;
    object-fit: cover !important;
    border-radius: 1rem !important;
    margin: 0 !important;
    display: block !important;
}
#reader canvas {
    display: none !important;
}
#reader__header_message,
#reader__dashboard_section_csr,
#reader__dashboard_section_swaplink {
    display: none !important;
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let isProcessing = false;
        let html5QrCode = null;
        let isScanning = false;

        const statusEl = document.getElementById('scan-status');
        const badgeEl = document.getElementById('camera-badge');
        const dotEl = document.getElementById('camera-dot');
        const badgeText = document.getElementById('camera-badge-text');
        const loadingEl = document.getElementById('camera-loading');
        const scanFrameEl = document.getElementById('scan-frame');
        const cameraSelect = document.getElementById('camera-select');
        const btnToggleCamera = document.getElementById('btn-toggle-camera');
        const insecureAlert = document.getElementById('insecure-context-alert');
        const localhostLink = document.getElementById('localhost-link');

        // Check secure context
        const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
        const isHttps = window.location.protocol === 'https:';
        if (!isLocalhost && !isHttps) {
            insecureAlert.classList.remove('hidden');
            if (localhostLink) {
                localhostLink.href = 'http://localhost:' + (window.location.port || '8000') + window.location.pathname;
                localhostLink.textContent = localhostLink.href;
            }
        }

        function setBadge(state, text) {
            badgeText.textContent = text;
            if (state === 'active') {
                badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold';
                dotEl.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
            } else if (state === 'warning') {
                badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold';
                dotEl.className = 'w-2 h-2 rounded-full bg-amber-500';
            } else if (state === 'error') {
                badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-semibold';
                dotEl.className = 'w-2 h-2 rounded-full bg-rose-500';
            } else {
                badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold';
                dotEl.className = 'w-2 h-2 rounded-full bg-slate-400';
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            setBadge('active', 'QR Berhasil!');
            statusEl.innerHTML = `<span class="text-emerald-600 font-bold">QR Terdeteksi: <span class="font-mono">${decodedText}</span>. Membuka data siswa...</span>`;

            if (html5QrCode && isScanning) {
                html5QrCode.stop().catch(() => {}).finally(() => {
                    document.getElementById('queryInput').value = decodedText;
                    document.getElementById('manualForm').submit();
                });
            } else {
                document.getElementById('queryInput').value = decodedText;
                document.getElementById('manualForm').submit();
            }
        }

        function onScanFailure(error) {
            // Frame scanning pass
        }

        async function initCamera() {
            // Ensure Html5Qrcode is available
            const Html5QrcodeClass = window.Html5Qrcode;
            if (!Html5QrcodeClass) {
                setBadge('error', 'Modul Tidak Tersedia');
                statusEl.innerHTML = '<span class="text-rose-600 font-medium">Modul scanner belum siap. Silakan muat ulang halaman atau gunakan input manual.</span>';
                loadingEl.innerHTML = '<p class="text-xs text-rose-400">Library scanner gagal dimuat.</p>';
                return;
            }

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setBadge('warning', 'Kamera Diblokir');
                statusEl.innerHTML = '<span class="text-amber-700 font-medium">Browser membatasi kamera pada koneksi tidak aman (HTTP). Silakan gunakan input manual atau buka lewat localhost.</span>';
                loadingEl.innerHTML = '<p class="text-xs text-amber-400">Kamera tidak diizinkan di protokol HTTP non-localhost.</p>';
                return;
            }

            try {
                if (!html5QrCode) {
                    html5QrCode = new Html5QrcodeClass("reader");
                }

                // Check available cameras
                const cameras = await Html5QrcodeClass.getCameras();
                if (!cameras || cameras.length === 0) {
                    setBadge('warning', 'Tidak Ada Kamera');
                    statusEl.innerHTML = '<span class="text-amber-600 font-medium">Tidak ada perangkat kamera / webcam terdeteksi. Gunakan input manual atau scan file gambar.</span>';
                    loadingEl.innerHTML = '<p class="text-xs text-slate-400">Kamera tidak ditemukan pada perangkat ini.</p>';
                    return;
                }

                // Populate camera selector if multiple cameras exist
                if (cameras.length > 1) {
                    cameraSelect.innerHTML = '';
                    cameras.forEach((cam, index) => {
                        const opt = document.createElement('option');
                        opt.value = cam.id;
                        opt.textContent = cam.label || `Kamera ${index + 1}`;
                        cameraSelect.appendChild(opt);
                    });
                    cameraSelect.classList.remove('hidden');
                }

                // Select rear/environment camera by default if available, or first
                let selectedCameraId = cameras[0].id;
                const backCamera = cameras.find(c => /back|rear|environment/i.test(c.label));
                if (backCamera) {
                    selectedCameraId = backCamera.id;
                    if (cameraSelect) cameraSelect.value = backCamera.id;
                }

                await startScanning(selectedCameraId);
            } catch (err) {
                console.error("Camera Init Error:", err);
                handleCameraError(err);
            }
        }

        async function startScanning(cameraIdOrConfig) {
            try {
                setBadge('warning', 'Menghubungkan...');
                statusEl.innerHTML = 'Menghubungkan ke kamera...';

                const config = {
                    fps: 15,
                    qrbox: function(viewfinderWidth, viewfinderHeight) {
                        const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        const qrboxSize = Math.floor(minEdge * 0.7);
                        return {
                            width: Math.max(150, Math.min(qrboxSize, 180)),
                            height: Math.max(150, Math.min(qrboxSize, 180))
                        };
                    },
                    aspectRatio: 1.333333
                };

                // Use camera id or facingMode fallback
                const cameraParam = cameraIdOrConfig || { facingMode: "environment" };

                await html5QrCode.start(
                    cameraParam,
                    config,
                    onScanSuccess,
                    onScanFailure
                );

                isScanning = true;
                loadingEl.classList.add('hidden');
                scanFrameEl.classList.remove('hidden');
                setBadge('active', 'Kamera Siap');
                statusEl.innerHTML = '<span class="text-slate-600">Arahkan QR Siswa ke dalam kotak target kamera.</span>';
            } catch (err) {
                console.error("Camera Start Error:", err);
                handleCameraError(err);
            }
        }

        function handleCameraError(err) {
            loadingEl.classList.remove('hidden');
            scanFrameEl.classList.add('hidden');
            setBadge('error', 'Kamera Gagal');

            let msg = 'Gagal mengakses kamera.';
            if (err.name === 'NotAllowedError' || err.toString().includes('NotAllowedError') || err.toString().includes('Permission denied')) {
                msg = 'Izin kamera ditolak. Harap klik ikon kamera/gembok di address bar browser dan pilih <strong>"Allow/Izinkan"</strong> lalu klik "Ulangi Kamera".';
            } else if (err.name === 'NotFoundError' || err.toString().includes('NotFoundError')) {
                msg = 'Perangkat kamera tidak ditemukan atau sedang digunakan aplikasi lain (misal Zoom/Meet).';
            } else if (err.name === 'NotReadableError' || err.toString().includes('NotReadableError')) {
                msg = 'Kamera sedang sibuk digunakan oleh aplikasi lain. Tutup aplikasi yang menggunakan webcam lalu ulangi.';
            } else {
                msg = `Kamera tidak dapat dimulai: ${err.message || err}. Anda tetap dapat memasukkan NIS/Rekening secara manual.`;
            }

            statusEl.innerHTML = `<span class="text-rose-600">${msg}</span>`;
            loadingEl.innerHTML = `<div class="p-4 text-center space-y-2">
                <svg class="w-8 h-8 mx-auto text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-xs text-slate-300 font-medium">${msg}</p>
            </div>`;
        }

        // Camera select change handler
        if (cameraSelect) {
            cameraSelect.addEventListener('change', async function () {
                if (html5QrCode && isScanning) {
                    await html5QrCode.stop().catch(() => {});
                    isScanning = false;
                }
                if (this.value) {
                    await startScanning(this.value);
                }
            });
        }

        // Button toggle / restart camera
        if (btnToggleCamera) {
            btnToggleCamera.addEventListener('click', async function () {
                if (html5QrCode && isScanning) {
                    await html5QrCode.stop().catch(() => {});
                    isScanning = false;
                }
                const selected = cameraSelect.value || null;
                await initCamera(selected);
            });
        }

        // File scan handler (fallback image upload)
        const fileInput = document.getElementById('qr-input-file');
        if (fileInput) {
            fileInput.addEventListener('change', async function (e) {
                if (e.target.files.length === 0) return;
                const imageFile = e.target.files[0];
                try {
                    setBadge('warning', 'Membaca Gambar...');
                    statusEl.innerHTML = 'Memproses scan QR dari berkas gambar...';
                    
                    const Html5QrcodeClass = window.Html5Qrcode;
                    if (!Html5QrcodeClass) throw new Error("Modul Html5Qrcode belum siap");

                    const fileQr = new Html5QrcodeClass("reader");
                    const decodedText = await fileQr.scanFile(imageFile, true);
                    onScanSuccess(decodedText, null);
                } catch (err) {
                    setBadge('error', 'Gagal Membaca File');
                    statusEl.innerHTML = `<span class="text-rose-600">Tidak dapat mendeteksi kode QR di gambar ini: ${err.message || err}</span>`;
                }
            });
        }

        // Kick off camera init on page load
        setTimeout(() => {
            initCamera();
        }, 300);
    });
</script>
@endpush
@endsection
