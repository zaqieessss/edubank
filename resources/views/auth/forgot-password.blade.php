<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Edubank SMKN 1 Bantul</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-blue.png') }}">

    <!-- Google Fonts: Nunito (Friendly Rounded) & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F0F6FF;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }

        .font-mono {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
            font-feature-settings: "tnum" 1, "zero" 1;
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex items-center justify-center bg-[#F0F6FF] selection:bg-blue-600 selection:text-white p-4 sm:p-6 lg:p-8 relative">

    <!-- ========================================================================= -->
    <!-- BRIGHT ARCHITECTURAL ISOMETRIC TECH BACKDROP (Cerah, Bersih & Statis)     -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        <!-- Base Gradient Canvas -->
        <div class="absolute inset-0 bg-gradient-to-tr from-[#EDF5FD] via-[#F4F8FD] to-[#E8F2FC]"></div>

        <!-- Radiant Ambient Light Centers -->
        <div class="absolute -top-32 left-1/3 w-[600px] h-[600px] rounded-full bg-blue-400/20 blur-[130px]"></div>
        <div class="absolute -bottom-32 right-1/4 w-[650px] h-[650px] rounded-full bg-sky-300/22 blur-[140px]"></div>
        <div class="absolute top-1/2 -left-20 w-[450px] h-[450px] rounded-full bg-indigo-200/18 blur-[120px]"></div>

        <!-- Architectural Isometric Grid Pattern with Center Fade -->
        <svg class="absolute inset-0 w-full h-full text-blue-600/15" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="forgotIsoGrid" width="60" height="103.92" patternUnits="userSpaceOnUse">
                    <path d="M 30 0 L 60 17.32 L 60 51.96 L 30 69.28 L 0 51.96 L 0 17.32 Z M 30 103.92 L 60 86.6 L 60 51.96 L 30 69.28 L 0 51.96 L 0 86.6 Z" 
                          fill="none" 
                          stroke="currentColor" 
                          stroke-width="0.8" 
                          stroke-opacity="0.3" />
                </pattern>
                <radialGradient id="forgotGridFade" cx="50%" cy="50%" r="55%">
                    <stop offset="0%" stop-color="black" stop-opacity="0.05" />
                    <stop offset="65%" stop-color="black" stop-opacity="0.5" />
                    <stop offset="100%" stop-color="black" stop-opacity="0.85" />
                </radialGradient>
                <mask id="forgotMask">
                    <rect width="100%" height="100%" fill="url(#forgotGridFade)" />
                </mask>
            </defs>
            <rect width="100%" height="100%" fill="url(#forgotIsoGrid)" mask="url(#forgotMask)" />
        </svg>

        <!-- Top-Right Prismatic Facet Accent -->
        <div class="absolute -top-16 -right-16 w-80 h-80 sm:w-96 sm:h-96 opacity-60">
            <svg class="w-full h-full" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="forgotPrism1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#38BDF8" />
                        <stop offset="100%" stop-color="#1D4ED8" />
                    </linearGradient>
                    <linearGradient id="forgotPrism2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#93C5FD" />
                        <stop offset="100%" stop-color="#2563EB" />
                    </linearGradient>
                    <linearGradient id="forgotPrism3" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="100%" stop-color="#60A5FA" />
                    </linearGradient>
                </defs>
                <polygon points="150,20 280,95 280,245 150,170" fill="url(#forgotPrism1)" opacity="0.4" />
                <polygon points="150,20 20,95 20,245 150,170" fill="url(#forgotPrism2)" opacity="0.6" />
                <polygon points="20,95 150,20 280,95 150,170" fill="url(#forgotPrism3)" opacity="0.8" />
            </svg>
        </div>

        <!-- Bottom-Left Prismatic Facet Accent -->
        <div class="absolute -bottom-16 -left-16 w-72 h-72 sm:w-88 sm:h-88 opacity-50">
            <svg class="w-full h-full" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon points="150,30 270,100 270,240 150,170" fill="url(#forgotPrism2)" opacity="0.5" />
                <polygon points="150,30 30,100 30,240 150,170" fill="url(#forgotPrism1)" opacity="0.7" />
            </svg>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MAIN AUTH CARD (Static, Perfectly Balanced, High Whitespace)              -->
    <!-- ========================================================================= -->
    <div class="relative w-full max-w-4xl max-h-[92vh] sm:max-h-[560px] bg-white rounded-3xl border border-blue-100/90 shadow-[0_20px_60px_-15px_rgba(37,99,235,0.12)] overflow-hidden grid grid-cols-1 md:grid-cols-12 z-10">

        <!-- ===================================================================== -->
        <!-- LEFT HERO (5 Cols): Clean, Elegan, Banyak Whitespace & Ilustrasi Grafis-->
        <!-- ===================================================================== -->
        <div class="hidden md:flex md:col-span-5 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-8 sm:p-10 lg:p-12 text-white flex-col justify-center relative overflow-hidden select-none">
            
            <!-- Soft Internal Ambient Light -->
            <div class="absolute -top-16 -left-16 w-56 h-56 bg-sky-300/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Content Area with Generous Whitespace -->
            <div class="relative z-10 space-y-5">
                
                <!-- Top Brand Capsule -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 p-2.5 shadow-sm inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-base tracking-wide text-white">Edubank</span>
                            <span class="px-2 py-0.5 rounded-full bg-white/20 border border-white/30 text-[9px] font-semibold text-white">Bank Mini</span>
                        </div>
                        <p class="text-xs text-blue-100 font-medium mt-0.5">SMKN 1 Bantul</p>
                    </div>
                </div>

                <!-- Clean Typography Heading & Description -->
                <div class="pt-2">
                    <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-white leading-snug">
                        Kelola Layanan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-200 via-white to-blue-200">Bank Mini</span> Lebih Cepat & Terintegrasi
                    </h1>
                    
                    <p class="text-xs sm:text-sm text-blue-100/90 mt-3 leading-relaxed font-normal">
                        Satu portal cerdas untuk layanan transaksi loket Teller, pengawasan kas harian Administrator, dan kemudahan pemantauan saldo tabungan siswa.
                    </p>
                </div>

                <!-- Ilustrasi Grafis (Fintech Growth & Secure Banking) -->
                <div class="pt-3">
                    <div class="w-full max-w-[240px] h-[85px] relative">
                        <svg class="w-full h-full" viewBox="0 0 280 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="chartLineGradF" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.3" />
                                    <stop offset="60%" stop-color="#38BDF8" />
                                    <stop offset="100%" stop-color="#FFFFFF" />
                                </linearGradient>
                                <linearGradient id="barGrad1F" x1="0%" y1="100%" x2="0%" y2="0%">
                                    <stop offset="0%" stop-color="#1E40AF" stop-opacity="0.2" />
                                    <stop offset="100%" stop-color="#60A5FA" stop-opacity="0.6" />
                                </linearGradient>
                                <linearGradient id="barGrad2F" x1="0%" y1="100%" x2="0%" y2="0%">
                                    <stop offset="0%" stop-color="#1E40AF" stop-opacity="0.3" />
                                    <stop offset="100%" stop-color="#38BDF8" stop-opacity="0.8" />
                                </linearGradient>
                                <linearGradient id="shieldGradF" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.25" />
                                    <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0.05" />
                                </linearGradient>
                            </defs>

                            <line x1="15" y1="20" x2="265" y2="20" stroke="white" stroke-opacity="0.08" stroke-dasharray="3 3" />
                            <line x1="15" y1="50" x2="265" y2="50" stroke="white" stroke-opacity="0.08" stroke-dasharray="3 3" />
                            <line x1="15" y1="80" x2="265" y2="80" stroke="white" stroke-opacity="0.12" />

                            <rect x="35" y="55" width="16" height="25" rx="3.5" fill="url(#barGrad1F)" />
                            <rect x="65" y="40" width="16" height="40" rx="3.5" fill="url(#barGrad1F)" />
                            <rect x="95" y="28" width="16" height="52" rx="3.5" fill="url(#barGrad2F)" />
                            <rect x="125" y="16" width="16" height="64" rx="3.5" fill="url(#barGrad2F)" />

                            <path d="M 25 68 Q 70 55, 105 35 T 150 12" stroke="url(#chartLineGradF)" stroke-width="2.2" stroke-linecap="round" />
                            <circle cx="150" cy="12" r="4.5" fill="#38BDF8" />
                            <circle cx="150" cy="12" r="2" fill="#FFFFFF" />

                            <g transform="translate(185, 12)">
                                <path d="M 28 4 C 44 4, 52 10, 52 24 C 52 40, 34 52, 28 56 C 22 52, 4 40, 4 24 C 4 10, 12 4, 28 4 Z" 
                                      fill="url(#shieldGradF)" 
                                      stroke="rgba(255,255,255,0.3)" 
                                      stroke-width="1.2" />
                                <path d="M 20 28 L 26 34 L 36 21" 
                                      stroke="#FFFFFF" 
                                      stroke-width="2.2" 
                                      stroke-linecap="round" 
                                      stroke-linejoin="round" />
                                <circle cx="44" cy="10" r="3" fill="#10B981" />
                            </g>
                        </svg>
                    </div>
                </div>

            </div>

        </div>

        <!-- ===================================================================== -->
        <!-- RIGHT FORM (7 Cols): Form Lupa Kata Sandi                             -->
        <!-- ===================================================================== -->
        <div class="col-span-1 md:col-span-7 p-6 sm:p-8 lg:p-12 flex flex-col justify-center bg-white relative overflow-y-auto md:overflow-hidden">
            
            <div class="w-full max-w-sm mx-auto">
                
                <!-- Mobile Header Brand (Visible only on small screens) -->
                <div class="flex md:hidden items-center gap-2.5 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-blue-600/10 p-1.5 flex items-center justify-center">
                        <img src="{{ asset('images/logo-blue.png') }}" alt="Edubank Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h2 class="font-bold text-sm text-slate-900 leading-none">Edubank</h2>
                        <span class="text-[10px] text-slate-500">SMKN 1 Bantul</span>
                    </div>
                </div>

                <!-- Form Heading -->
                <div class="mb-6">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Masukkan email akun Anda. Kami akan mengirimkan 6 digit kode OTP verifikasi.</p>
                </div>

                <!-- Session Flash Messages -->
                @if(session('error'))
                    <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Alamat Email Terdaftar
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   class="w-full bg-slate-50/80 border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 sm:py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-500/15 transition-all"
                                   placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs sm:text-sm py-2.5 sm:py-3 rounded-xl shadow-sm transition-all hover:shadow active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <span>Kirim Kode OTP</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Back to Login Navigation -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Kembali ke Halaman Login</span>
                    </a>
                </div>

            </div>
        </div>

    </div>

</body>
</html>