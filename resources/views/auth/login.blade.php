<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Edubank SMKN 1 Bantul</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-blue.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Subtle ambient pulse animation for background illustration rings */
        @keyframes float-pulse {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.85;
            }
            50% {
                transform: scale(1.04) rotate(3deg);
                opacity: 1;
            }
        }
        @keyframes float-pulse-reverse {
            0%, 100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.05) rotate(-3deg);
                opacity: 1;
            }
        }
        .anim-ring-tl {
            animation: float-pulse 14s ease-in-out infinite;
            transform-origin: center;
        }
        .anim-ring-br {
            animation: float-pulse-reverse 16s ease-in-out infinite;
            transform-origin: center;
        }
    </style>
</head>
<body class="min-h-screen p-4 sm:p-6 md:p-10 lg:p-12 flex items-center justify-center bg-[#F8FAFC] relative selection:bg-blue-600 selection:text-white">

    <!-- ========================================================================= -->
    <!-- BACKGROUND ILLUSTRATION LAYER (Di Belakang Card Sesuai Referensi)         -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        <!-- Delicate dot matrix canvas background -->
        <svg class="absolute inset-0 w-full h-full text-slate-300/40 opacity-60" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="bgDots" x="0" y="0" width="32" height="32" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.1" fill="currentColor" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#bgDots)" />
        </svg>

        <!-- TOP-LEFT ILLUSTRATION: Multi-ring concentric circles with glowing aura -->
        <div class="absolute -top-36 -left-36 md:-top-44 md:-left-44 w-[480px] h-[480px] md:w-[680px] md:h-[680px] anim-ring-tl">
            <!-- Ambient Radial Glow -->
            <div class="absolute inset-16 rounded-full bg-gradient-to-tr from-blue-500/18 via-sky-400/22 to-emerald-400/15 blur-3xl"></div>

            <svg class="w-full h-full text-blue-500/20" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Outer Concentric Rings -->
                <circle cx="300" cy="300" r="280" stroke="currentColor" stroke-width="1.2" stroke-dasharray="6 8" class="text-blue-400/30" />
                <circle cx="300" cy="300" r="235" stroke="currentColor" stroke-width="1.5" class="text-blue-500/25" />
                <circle cx="300" cy="300" r="190" stroke="currentColor" stroke-width="1" stroke-dasharray="3 5" class="text-emerald-500/30" />
                <circle cx="300" cy="300" r="150" stroke="currentColor" stroke-width="2" class="text-blue-600/35" />
                <circle cx="300" cy="300" r="110" stroke="currentColor" stroke-width="1.2" class="text-sky-400/35" />
                <circle cx="300" cy="300" r="70" fill="url(#tlCoreGradient)" opacity="0.65" />

                <!-- Crosshairs and Data Nodes -->
                <line x1="300" y1="20" x2="300" y2="580" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 4" class="text-blue-300/30" />
                <line x1="20" y1="300" x2="580" y2="300" stroke="currentColor" stroke-width="0.8" stroke-dasharray="4 4" class="text-blue-300/30" />
                <circle cx="300" cy="65" r="4" fill="#3B82F6" />
                <circle cx="535" cy="300" r="3.5" fill="#10B981" />
                <circle cx="300" cy="490" r="4" fill="#0EA5E9" />
                <circle cx="150" cy="300" r="3.5" fill="#6366F1" />

                <!-- Gradient Defs -->
                <defs>
                    <radialGradient id="tlCoreGradient" cx="0.5" cy="0.5" r="0.5" fx="0.5" fy="0.5">
                        <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.45" />
                        <stop offset="70%" stop-color="#2563EB" stop-opacity="0.2" />
                        <stop offset="100%" stop-color="#1D4ED8" stop-opacity="0" />
                    </radialGradient>
                </defs>
            </svg>
        </div>

        <!-- BOTTOM-RIGHT ILLUSTRATION: Concentric orbital rings with emerald & sapphire aura -->
        <div class="absolute -bottom-40 -right-40 md:-bottom-52 md:-right-52 w-[520px] h-[520px] md:w-[720px] md:h-[720px] anim-ring-br">
            <!-- Ambient Radial Glow -->
            <div class="absolute inset-16 rounded-full bg-gradient-to-br from-emerald-500/20 via-teal-400/22 to-blue-500/18 blur-3xl"></div>

            <svg class="w-full h-full text-emerald-500/25" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Concentric Wave Rings -->
                <circle cx="300" cy="300" r="285" stroke="currentColor" stroke-width="1.2" class="text-emerald-400/25" />
                <circle cx="300" cy="300" r="240" stroke="currentColor" stroke-width="1.5" stroke-dasharray="5 7" class="text-teal-500/30" />
                <circle cx="300" cy="300" r="195" stroke="currentColor" stroke-width="1" class="text-sky-500/25" />
                <circle cx="300" cy="300" r="145" stroke="currentColor" stroke-width="2" class="text-emerald-600/35" />
                <circle cx="300" cy="300" r="95" stroke="currentColor" stroke-width="1.2" stroke-dasharray="3 4" class="text-teal-400/35" />
                <circle cx="300" cy="300" r="55" fill="url(#brCoreGradient)" opacity="0.7" />

                <!-- Orbital Nodes -->
                <circle cx="300" cy="105" r="4.5" fill="#10B981" />
                <circle cx="495" cy="300" r="3.5" fill="#0EA5E9" />
                <circle cx="105" cy="300" r="4" fill="#14B8A6" />
                <circle cx="300" cy="540" r="3.5" fill="#3B82F6" />

                <defs>
                    <radialGradient id="brCoreGradient" cx="0.5" cy="0.5" r="0.5">
                        <stop offset="0%" stop-color="#10B981" stop-opacity="0.4" />
                        <stop offset="70%" stop-color="#059669" stop-opacity="0.18" />
                        <stop offset="100%" stop-color="#047857" stop-opacity="0" />
                    </radialGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MAIN LOGIN CARD CONTAINER (Floating, Rounded 36px, High Elevation)         -->
    <!-- ========================================================================= -->
    <div class="w-full max-w-5xl min-h-[620px] bg-white rounded-[36px] border border-slate-200/80 shadow-[0_25px_70px_-15px_rgba(15,23,42,0.14),0_0_1px_1px_rgba(15,23,42,0.04)] overflow-hidden grid grid-cols-1 md:grid-cols-12 relative z-10">
        
        <!-- ===================================================================== -->
        <!-- LEFT HERO (5 Cols): Sapphire & Emerald Fintech Branding               -->
        <!-- ===================================================================== -->
        <div class="md:col-span-5 bg-gradient-to-br from-[#061224] via-[#0B2349] to-[#0E356A] p-8 sm:p-10 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            
            <!-- Internal Ambient Mesh & Graphic Accents -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-blue-500/25 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-600/15 rounded-full blur-3xl"></div>

                <!-- Subtle Guilloche / Security Pattern Rings Inside Left Card -->
                <svg class="absolute -right-12 top-10 w-72 h-72 text-white/10" viewBox="0 0 300 300" fill="none">
                    <circle cx="150" cy="150" r="140" stroke="currentColor" stroke-width="1" stroke-dasharray="4 6" />
                    <circle cx="150" cy="150" r="105" stroke="currentColor" stroke-width="1" />
                    <circle cx="150" cy="150" r="70" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3" />
                </svg>

                <!-- Watermark Text / Emblem at bottom right of left hero -->
                <div class="absolute -bottom-6 -right-6 text-[120px] font-black text-white/[0.04] leading-none select-none pointer-events-none font-mono">
                    EDU
                </div>
            </div>

            <!-- Content Area -->
            <div class="relative z-10">
                <!-- Top Brand Capsule -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-13 h-13 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-2.5 shadow-lg inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-full h-full object-contain drop-shadow">
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-400/30 text-[10px] font-bold text-emerald-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>E-Teller & Mini Bank</span>
                        </div>
                        <h2 class="text-xs font-semibold text-slate-300 tracking-wide mt-1">SMKN 1 Bantul</h2>
                    </div>
                </div>

                <!-- Big Main Typography (Distinctive & Inspiring) -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-[1.2] text-white">
                    Kelola Layanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-sky-200 to-emerald-300">Bank Mini</span> Lebih Cepat & Terintegrasi.
                </h1>

                <p class="text-xs sm:text-sm text-slate-300/85 mt-4 leading-relaxed font-normal">
                    Satu portal cerdas untuk layanan transaksi loket Teller, pengawasan kas harian Administrator, dan kemudahan pemantauan saldo tabungan siswa.
                </p>

                <!-- Feature High-Trust Pills -->
                <div class="mt-7 space-y-2.5 pt-5 border-t border-white/10 text-xs text-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-lg bg-blue-500/20 border border-blue-400/30 text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="font-medium text-slate-200/90">Pencatatan Setor, Tarik & Closing Real-Time</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="font-medium text-slate-200/90">Proteksi PIN Otorisasi & Jurnal Akuntansi</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-lg bg-sky-500/20 border border-sky-400/30 text-sky-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="font-medium text-slate-200/90">Akses Fleksibel: Web Responsif & Aplikasi Mobile</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Left Info -->
            <div class="relative z-10 pt-6 text-[11px] text-slate-400 flex items-center justify-between border-t border-white/10 mt-6">
                <span>Teller &bull; Administrator &bull; Siswa</span>
                <span class="text-slate-400/80 font-medium">&copy; {{ date('Y') }}</span>
            </div>
        </div>

        <!-- ===================================================================== -->
        <!-- RIGHT FORM (7 Cols): Clean, Spacious & Professional Form             -->
        <!-- ===================================================================== -->
        <div class="md:col-span-7 p-8 sm:p-10 md:p-12 lg:p-14 flex flex-col justify-center bg-white relative">
            
            <div class="mb-7">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100 inline-block mb-2">
                    Portal Masuk Sistem
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5">Masuk ke dashboard operasional Edubank SMKN 1 Bantul.</p>
            </div>

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs sm:text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs sm:text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4 sm:space-y-5">
                @csrf

                <!-- Email / Account Number Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Username, Email, atau No. Rekening
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="w-full bg-slate-50 border border-slate-200/90 rounded-xl pl-11 pr-4 py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-blue-500/15 focus:border-blue-600 transition-all shadow-xs"
                               placeholder="nama@email.com atau RPL-29-0001">
                    </div>
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input with Eye Toggle -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi (Password)</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Lupa password?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full bg-slate-50 border border-slate-200/90 rounded-xl pl-11 pr-11 py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-3 focus:ring-blue-500/15 focus:border-blue-600 transition-all shadow-xs"
                               placeholder="••••••••">
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none"
                                title="Lihat/Sembunyikan Kata Sandi">
                            <svg id="eyeIconOpen" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eyeIconClosed" class="w-4.5 h-4.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer">
                        <span class="text-xs text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Action Button -->
                <button type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm py-3.5 rounded-xl shadow-sm transition-all hover:shadow-md active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer mt-2">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Bottom Disclaimer -->
            <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Sesi Terenkripsi & Aman
                </span>
                <span>&copy; {{ date('Y') }} SMKN 1 Bantul</span>
            </div>
        </div>

    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeIconOpen');
            const eyeClosed = document.getElementById('eyeIconClosed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
