<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tabungan Digital Siswa') - Edubank SMKN 1 Bantul</title>

    <!-- iOS / PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Edubank">
    <meta name="theme-color" content="#EFF6FF">
    <meta name="format-detection" content="telephone=no">

    <!-- Favicon & Apple Touch Icon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-blue.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-blue.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }

        /* Ambient Canvas Aura matching Flutter Home Screen */
        .mobile-canvas {
            background: linear-gradient(180deg, #EFF6FF 0%, #F8FAFC 35%, #F1F5F9 100%);
        }

        /* Hide Scrollbars */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* iOS Safe Area Padding */
        .safe-pb {
            padding-bottom: max(1.5rem, env(safe-area-inset-bottom));
        }
        .safe-pt {
            padding-top: max(0.75rem, env(safe-area-inset-top));
        }

        /* Glassmorphism blur fallback */
        .glass-dock {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Non-rotating elegant entry for Floating QR Loket button */
        @keyframes qrFabIn {
            0% {
                opacity: 0;
                transform: scale(0.85);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-qr-fab {
            animation: qrFabIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 flex justify-center antialiased">

    <!-- Mobile Frame (Fluid 100% on phones, elegant 430px smartphone canvas on desktop) -->
    <div class="w-full max-w-[430px] min-h-screen mobile-canvas relative flex flex-col shadow-2xl md:border-x md:border-slate-200/80">

        <!-- Main Content Slot -->
        <main class="flex-1 pb-28 overflow-y-auto no-scrollbar">
            <!-- Flash Message Toast -->
            @if(session('success'))
                <div id="flashSuccess" class="fixed top-4 left-4 right-4 md:left-auto md:right-auto md:w-[398px] z-50 p-3.5 rounded-2xl bg-emerald-600 text-white text-xs font-semibold shadow-xl flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('flashSuccess').remove()" class="text-white/80 hover:text-white">&times;</button>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div id="flashError" class="fixed top-4 left-4 right-4 md:left-auto md:right-auto md:w-[398px] z-50 p-3.5 rounded-2xl bg-rose-600 text-white text-xs font-semibold shadow-xl flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ session('error') ?? $errors->first() }}</span>
                    </div>
                    <button onclick="document.getElementById('flashError').remove()" class="text-white/80 hover:text-white">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- ========================================================================= -->
        <!-- FLOATING QR LOKET BUTTON (HANYA MUNCUL DI BERANDA / DASHBOARD)            -->
        <!-- Penempatan: Di atas bottom navbar sebelah kanan, muncul elegan           -->
        <!-- ========================================================================= -->
        @if(request()->routeIs('saver.dashboard'))
            <div class="fixed bottom-[80px] right-5 md:right-auto md:left-1/2 md:translate-x-[64px] z-40 select-none animate-qr-fab">
                <button type="button" 
                        onclick="openQrSheet()" 
                        class="group flex items-center gap-2 pl-3 pr-4 py-2.5 rounded-full bg-gradient-to-r from-[#1E3A8A] to-[#2563EB] text-white border border-white/25 shadow-[0_10px_25px_-3px_rgba(37,99,235,0.45)] hover:shadow-blue-600/50 active:scale-95 transition-all cursor-pointer">
                    <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-extrabold tracking-wide text-white drop-shadow-xs">QR Loket</span>
                </button>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- FLOATING BOTTOM NAVIGATION DOCK (Identik Flutter MainNavigationScreen)      -->
        <!-- ========================================================================= -->
        <nav class="fixed bottom-3 inset-x-3 md:inset-x-auto md:left-1/2 md:-translate-x-1/2 md:w-[406px] z-40 glass-dock rounded-[28px] border border-slate-200/90 shadow-[0_12px_36px_-6px_rgba(30,58,138,0.18)] px-4 py-2 flex items-center justify-around select-none">
            
            <!-- 1. Beranda (Home) -->
            <a href="{{ route('saver.dashboard') }}" 
               class="flex-1 flex flex-col items-center gap-0.5 py-1 px-2 rounded-2xl transition-all {{ request()->routeIs('saver.dashboard') ? 'text-blue-600 font-bold' : 'text-slate-400 hover:text-slate-600 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('saver.dashboard') ? '2.4' : '1.8' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] tracking-tight">Beranda</span>
                @if(request()->routeIs('saver.dashboard'))
                    <span class="w-1 h-1 rounded-full bg-blue-600"></span>
                @endif
            </a>

            <!-- 2. Mutasi (Riwayat Transaksi) -->
            <a href="{{ route('saver.mutation') }}" 
               class="flex-1 flex flex-col items-center gap-0.5 py-1 px-2 rounded-2xl transition-all {{ request()->routeIs('saver.mutation') ? 'text-blue-600 font-bold' : 'text-slate-400 hover:text-slate-600 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('saver.mutation') ? '2.4' : '1.8' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="text-[10px] tracking-tight">Mutasi</span>
                @if(request()->routeIs('saver.mutation'))
                    <span class="w-1 h-1 rounded-full bg-blue-600"></span>
                @endif
            </a>

            <!-- 3. Profil & Keamanan -->
            <a href="{{ route('saver.profile') }}" 
               class="flex-1 flex flex-col items-center gap-0.5 py-1 px-2 rounded-2xl transition-all {{ request()->routeIs('saver.profile') ? 'text-blue-600 font-bold' : 'text-slate-400 hover:text-slate-600 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="{{ request()->routeIs('saver.profile') ? '2.4' : '1.8' }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] tracking-tight">Profil</span>
                @if(request()->routeIs('saver.profile'))
                    <span class="w-1 h-1 rounded-full bg-blue-600"></span>
                @endif
            </a>
        </nav>

        <!-- ========================================================================= -->
        <!-- BOTTOM SHEET / MODAL: QR Loket Siswa (Identik qr_modal_sheet.dart)       -->
        <!-- ========================================================================= -->
        <div id="qrModalSheet" 
             class="hidden fixed inset-0 z-50 items-end justify-center bg-slate-900/60 backdrop-blur-xs p-0 transition-opacity"
             onclick="if(event.target === this) closeQrSheet()">
            
            <div class="w-full max-w-[430px] bg-white rounded-t-[32px] p-6 shadow-2xl space-y-4 border-t border-slate-100 flex flex-col items-center text-center animate-slide-up relative">
                
                <!-- Pull Indicator Bar -->
                <div class="w-12 h-1.5 rounded-full bg-slate-200 -mt-1 mb-2"></div>

                <!-- Header Modal -->
                <div class="w-full flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">QR Rekening Loket</h3>
                    </div>
                    <button type="button" onclick="closeQrSheet()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm cursor-pointer">
                        &times;
                    </button>
                </div>

                <!-- QR Code Box (Crisp White Card with Glow) -->
                <div class="p-4 bg-white rounded-3xl border border-slate-200/90 shadow-md inline-flex items-center justify-center mt-2">
                    @if(isset($qrCodeDataUri))
                        <img src="{{ $qrCodeDataUri }}" alt="QR Rekening" class="w-56 h-56 object-contain">
                    @else
                        <div class="w-56 h-56 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 text-xs">QR Code Belum Tersedia</div>
                    @endif
                </div>

                <!-- Account Number Pill with Copy -->
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 mt-1">
                    <span class="text-xs font-semibold text-slate-500">No. Rekening:</span>
                    <span class="text-sm font-mono font-bold text-blue-700 tracking-wide">{{ $saver->account_number ?? '-' }}</span>
                    <button type="button" onclick="copyToClipboard('{{ $saver->account_number ?? '' }}')" class="text-slate-400 hover:text-blue-600 transition-colors ml-1 cursor-pointer" title="Salin">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </div>

                <!-- Guidance Note -->
                <p class="text-xs text-slate-400 max-w-xs leading-relaxed">
                    Tunjukkan kode QR ini ke kamera scanner Teller di loket bank mini untuk verifikasi rekening secara otomatis saat setor atau tarik tunai.
                </p>

                <button type="button" onclick="closeQrSheet()" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-semibold shadow-xs cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- REALTIME IN-APP PIN AUTHORIZATION MODAL (Identik pin_authorization_modal.dart) -->
        <!-- ========================================================================= -->
        <div id="pinAuthModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div class="w-full max-w-[390px] bg-white rounded-[28px] p-6 shadow-2xl space-y-4 border border-slate-200 animate-scale-up text-center relative">
                
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center border border-amber-200/80">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-800">Otorisasi Penarikan Tunai</h3>
                    <p class="text-xs text-slate-400 mt-1">Petugas Teller meminta konfirmasi penarikan kas dari rekening Anda.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5 text-xs text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Nominal Penarikan:</span>
                        <span id="pinAuthAmount" class="font-mono font-extrabold text-rose-600 text-sm">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Petugas Loket:</span>
                        <span id="pinAuthTeller" class="font-semibold text-slate-700">Teller</span>
                    </div>
                </div>

                <form id="pinAuthForm" onsubmit="submitPinAuth(event)" class="space-y-4 pt-1">
                    <input type="hidden" id="pinAuthRequestId" value="">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Masukkan 6 Digit PIN Transaksi</label>
                        <input type="password" 
                               id="pinAuthInput" 
                               maxlength="6" 
                               pattern="\d{6}" 
                               inputmode="numeric" 
                               required 
                               placeholder="••••••" 
                               class="w-full text-center tracking-[0.5em] font-mono text-lg font-bold bg-slate-50 border border-slate-200 rounded-xl py-3 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <p id="pinAuthError" class="text-xs text-rose-600 font-medium mt-1.5 hidden"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="rejectPinAuth()" class="py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-all cursor-pointer">
                            Tolak
                        </button>
                        <button type="submit" id="pinAuthSubmitBtn" class="py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-all shadow-xs cursor-pointer">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @stack('scripts')
    <script>
        function openQrSheet() {
            const sheet = document.getElementById('qrModalSheet');
            if (sheet) {
                sheet.classList.remove('hidden');
                sheet.classList.add('flex');
            }
        }

        function closeQrSheet() {
            const sheet = document.getElementById('qrModalSheet');
            if (sheet) {
                sheet.classList.add('hidden');
                sheet.classList.remove('flex');
            }
        }

        function copyToClipboard(text) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor rekening ' + text + ' berhasil disalin!');
            }).catch(() => {
                const input = document.createElement('input');
                input.value = text;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                alert('Nomor rekening ' + text + ' berhasil disalin!');
            });
        }

        // Real-Time In-App PIN Authorization Poller
        let currentAuthRequestId = null;
        let isCheckingPinAuth = false;

        async function checkPendingPinAuthorization() {
            if (isCheckingPinAuth || currentAuthRequestId) return;
            isCheckingPinAuth = true;

            try {
                const res = await fetch("{{ route('saver.pin.pending') }}", {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();

                if (data.has_pending && data.data) {
                    currentAuthRequestId = data.data.id;
                    document.getElementById('pinAuthRequestId').value = data.data.id;
                    document.getElementById('pinAuthAmount').innerText = data.data.amount_formatted;
                    document.getElementById('pinAuthTeller').innerText = data.data.teller_name;
                    document.getElementById('pinAuthInput').value = '';
                    document.getElementById('pinAuthError').classList.add('hidden');
                    
                    const modal = document.getElementById('pinAuthModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => document.getElementById('pinAuthInput').focus(), 100);
                }
            } catch (e) {
                // Background poll silent catch
            } finally {
                isCheckingPinAuth = false;
            }
        }

        async function submitPinAuth(e) {
            e.preventDefault();
            const pin = document.getElementById('pinAuthInput').value;
            const requestId = document.getElementById('pinAuthRequestId').value;
            const btn = document.getElementById('pinAuthSubmitBtn');
            const errP = document.getElementById('pinAuthError');

            if (!pin || pin.length !== 6) {
                errP.innerText = 'PIN harus berupa 6 digit angka.';
                errP.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.innerText = 'Memverifikasi...';

            try {
                const res = await fetch(`/saver/pin-authorization/${requestId}/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ pin: pin })
                });

                const json = await res.json();
                if (json.success) {
                    document.getElementById('pinAuthModal').classList.add('hidden');
                    document.getElementById('pinAuthModal').classList.remove('flex');
                    currentAuthRequestId = null;
                    alert('Penarikan tunai berhasil diotorisasi!');
                    window.location.reload();
                } else {
                    errP.innerText = json.message || 'PIN yang Anda masukkan salah.';
                    errP.classList.remove('hidden');
                }
            } catch (err) {
                errP.innerText = 'Terjadi kesalahan sistem. Coba lagi.';
                errP.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Setujui';
            }
        }

        async function rejectPinAuth() {
            const requestId = document.getElementById('pinAuthRequestId').value;
            if (!requestId) return;

            if (!confirm('Apakah Anda yakin ingin menolak transaksi penarikan ini?')) return;

            try {
                await fetch(`/saver/pin-authorization/${requestId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
            } catch(e) {}

            document.getElementById('pinAuthModal').classList.add('hidden');
            document.getElementById('pinAuthModal').classList.remove('flex');
            currentAuthRequestId = null;
        }

        // Start background polling every 2.5 seconds
        setInterval(checkPendingPinAuthorization, 2500);

        // Auto dismiss flash
        setTimeout(() => {
            const fs = document.getElementById('flashSuccess');
            if (fs) fs.remove();
        }, 5000);
    </script>
</body>
</html>
