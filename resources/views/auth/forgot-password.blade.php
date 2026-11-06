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
            background-color: #F8FAFC;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }

        .font-mono {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
            font-feature-settings: "tnum" 1, "zero" 1;
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex items-center justify-center bg-[#F8FAFC] selection:bg-blue-600 selection:text-white p-4 sm:p-6 lg:p-8 relative">

    <!-- ========================================================================= -->
    <!-- CANVAS BACKDROP (Delicate Dot Matrix & Ambient Wash)                      -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        <!-- Subtle Ambient Light Wash on Canvas -->
        <div class="absolute inset-0 bg-radial-at-c from-blue-50/50 via-transparent to-transparent"></div>

        <!-- Delicate dot matrix canvas background -->
        <svg class="absolute inset-0 w-full h-full text-slate-300/35 opacity-40" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="bgDotsF" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.1" fill="currentColor" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#bgDotsF)" />
        </svg>
    </div>

    <!-- ========================================================================= -->
    <!-- MAIN AUTH CARD WRAPPER WITH 2 CIRCLES TUCKED HALFWAY BEHIND (~50%)        -->
    <!-- ========================================================================= -->
    <div class="relative w-full max-w-4xl max-h-[92vh] sm:max-h-[580px] my-auto z-10">

        <!-- 3D TORUS ILLUSTRATION LAYER (Tucked Directly Behind Card, ~50% Disembunyikan Setengah) -->
        <div class="absolute inset-0 -z-10 pointer-events-none select-none">
            <!-- TOP-LEFT 3D TORUS & HALO (~50% setengah lingkaran terlihat di luar kartu) -->
            <div class="absolute -top-32 -left-32 md:-top-44 md:-left-44 w-[480px] h-[480px] md:w-[600px] md:h-[600px] opacity-75">
                <!-- Symmetrical Ambient Glow Halo -->
                <div class="absolute inset-20 rounded-full bg-gradient-to-tr from-blue-500/22 via-sky-400/20 to-teal-400/14 blur-[85px]"></div>

                <svg class="w-full h-full" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="shadowTLF" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="20" stdDeviation="24" flood-color="#1E3A8A" flood-opacity="0.14" />
                        </filter>
                        <filter id="softGlowTLF" x="-30%" y="-30%" width="160%" height="160%">
                            <feGaussianBlur stdDeviation="6" />
                        </filter>
                        <radialGradient id="torusGradTLF" cx="42%" cy="34%" r="66%">
                            <stop offset="0%" stop-color="#7DD3FC" />
                            <stop offset="25%" stop-color="#38BDF8" />
                            <stop offset="55%" stop-color="#2563EB" />
                            <stop offset="85%" stop-color="#1D4ED8" />
                            <stop offset="100%" stop-color="#0B2349" />
                        </radialGradient>
                        <linearGradient id="specularGradTLF" x1="10%" y1="0%" x2="90%" y2="80%">
                            <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.9" />
                            <stop offset="40%" stop-color="#BAE6FD" stop-opacity="0.4" />
                            <stop offset="100%" stop-color="#38BDF8" stop-opacity="0" />
                        </linearGradient>
                    </defs>

                    <circle cx="400" cy="400" r="370" stroke="#38BDF8" stroke-opacity="0.22" stroke-width="1.2" />
                    <circle cx="400" cy="400" r="310" stroke="#60A5FA" stroke-opacity="0.18" stroke-width="1" stroke-dasharray="8 12" />
                    <circle cx="400" cy="400" r="250" stroke="#93C5FD" stroke-opacity="0.2" stroke-width="1.2" />

                    <path d="M 400,150 A 250,250 0 1,0 400,650 A 250,250 0 1,0 400,150 Z M 400,235 A 165,165 0 1,1 400,565 A 165,165 0 1,1 400,235 Z" 
                          fill="url(#torusGradTLF)" 
                          filter="url(#shadowTLF)" />

                    <path d="M 230,280 A 210,210 0 0,1 540,240" 
                          stroke="url(#specularGradTLF)" 
                          stroke-width="11" 
                          stroke-linecap="round" 
                          filter="url(#softGlowTLF)" />
                    <path d="M 265,270 A 210,210 0 0,1 505,245" 
                          stroke="#FFFFFF" 
                          stroke-width="3.5" 
                          stroke-linecap="round" 
                          opacity="0.85" />
                </svg>
            </div>

            <!-- BOTTOM-RIGHT 3D TORUS & HALO (~50% setengah lingkaran terlihat di luar kartu) -->
            <div class="absolute -bottom-32 -right-32 md:-bottom-44 md:-right-44 w-[480px] h-[480px] md:w-[600px] md:h-[600px] opacity-75">
                <div class="absolute inset-20 rounded-full bg-gradient-to-br from-emerald-500/22 via-teal-400/20 to-blue-500/16 blur-[85px]"></div>

                <svg class="w-full h-full" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="shadowBRF" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="-18" stdDeviation="24" flood-color="#047857" flood-opacity="0.14" />
                        </filter>
                        <filter id="softGlowBRF" x="-30%" y="-30%" width="160%" height="160%">
                            <feGaussianBlur stdDeviation="6" />
                        </filter>
                        <radialGradient id="torusGradBRF" cx="58%" cy="66%" r="66%">
                            <stop offset="0%" stop-color="#6EE7B7" />
                            <stop offset="25%" stop-color="#10B981" />
                            <stop offset="55%" stop-color="#0D9488" />
                            <stop offset="85%" stop-color="#2563EB" />
                            <stop offset="100%" stop-color="#0B2349" />
                        </radialGradient>
                        <linearGradient id="specularGradBRF" x1="10%" y1="100%" x2="90%" y2="20%">
                            <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.9" />
                            <stop offset="40%" stop-color="#A7F3D0" stop-opacity="0.4" />
                            <stop offset="100%" stop-color="#10B981" stop-opacity="0" />
                        </linearGradient>
                    </defs>

                    <circle cx="400" cy="400" r="370" stroke="#10B981" stroke-opacity="0.22" stroke-width="1.2" />
                    <circle cx="400" cy="400" r="310" stroke="#14B8A6" stroke-opacity="0.18" stroke-width="1" stroke-dasharray="8 12" />
                    <circle cx="400" cy="400" r="250" stroke="#38BDF8" stroke-opacity="0.2" stroke-width="1.2" />

                    <path d="M 400,150 A 250,250 0 1,0 400,650 A 250,250 0 1,0 400,150 Z M 400,235 A 165,165 0 1,1 400,565 A 165,165 0 1,1 400,235 Z" 
                          fill="url(#torusGradBRF)" 
                          filter="url(#shadowBRF)" />

                    <path d="M 260,560 A 210,210 0 0,0 570,520" 
                          stroke="url(#specularGradBRF)" 
                          stroke-width="11" 
                          stroke-linecap="round" 
                          filter="url(#softGlowBRF)" />
                    <path d="M 295,555 A 210,210 0 0,0 535,530" 
                          stroke="#FFFFFF" 
                          stroke-width="3.5" 
                          stroke-linecap="round" 
                          opacity="0.85" />
                </svg>
            </div>
        </div>

        <!-- ===================================================================== -->
        <!-- MAIN AUTH CARD (Static, High Elevation, Rounded 32px)                  -->
        <!-- ========================================================================= -->
        <div class="w-full bg-white rounded-3xl border border-slate-200/90 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.1)] overflow-hidden grid grid-cols-1 md:grid-cols-12 relative z-10">

            <!-- ===================================================================== -->
            <!-- LEFT HERO (5 Cols): Clean, Elegan, Banyak Whitespace & Grafis Fintech -->
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

                    <!-- Ilustrasi Grafis (Fintech Growth Bars & Trendline - Tanpa Icon Checklist) -->
                    <div class="pt-3">
                        <div class="w-full max-w-[240px] h-[80px] relative">
                            <svg class="w-full h-full" viewBox="0 0 260 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="chartLineGradF" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.3" />
                                        <stop offset="50%" stop-color="#38BDF8" />
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
                                </defs>

                                <line x1="15" y1="20" x2="245" y2="20" stroke="white" stroke-opacity="0.08" stroke-dasharray="3 3" />
                                <line x1="15" y1="48" x2="245" y2="48" stroke="white" stroke-opacity="0.08" stroke-dasharray="3 3" />
                                <line x1="15" y1="76" x2="245" y2="76" stroke="white" stroke-opacity="0.12" />

                                <rect x="35" y="52" width="18" height="24" rx="4" fill="url(#barGrad1F)" />
                                <rect x="75" y="38" width="18" height="38" rx="4" fill="url(#barGrad1F)" />
                                <rect x="115" y="26" width="18" height="50" rx="4" fill="url(#barGrad2F)" />
                                <rect x="155" y="14" width="18" height="62" rx="4" fill="url(#barGrad2F)" />
                                <rect x="195" y="6" width="18" height="70" rx="4" fill="url(#barGrad2F)" />

                                <path d="M 25 65 Q 85 52, 135 30 T 215 10" stroke="url(#chartLineGradF)" stroke-width="2.5" stroke-linecap="round" />
                                <circle cx="215" cy="10" r="5" fill="#38BDF8" />
                                <circle cx="215" cy="10" r="2.5" fill="#FFFFFF" />
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

    </div>

</body>
</html>