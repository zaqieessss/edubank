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

        /* 3D Floating Animations */
        @keyframes float3d-slow {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-12px) rotate(1.2deg);
            }
        }
        @keyframes float3d-reverse {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(14px) rotate(-1.5deg);
            }
        }
        @keyframes orb-float-1 {
            0%, 100% {
                transform: translate(0px, 0px) scale(1);
            }
            50% {
                transform: translate(8px, -14px) scale(1.04);
            }
        }
        @keyframes orb-float-2 {
            0%, 100% {
                transform: translate(0px, 0px) scale(1);
            }
            50% {
                transform: translate(-10px, 12px) scale(0.97);
            }
        }
        .anim-3d-top {
            animation: float3d-slow 16s ease-in-out infinite;
            transform-origin: center;
        }
        .anim-3d-bottom {
            animation: float3d-reverse 18s ease-in-out infinite;
            transform-origin: center;
        }
        .anim-orb-1 {
            animation: orb-float-1 12s ease-in-out infinite;
        }
        .anim-orb-2 {
            animation: orb-float-2 15s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen p-4 sm:p-6 md:p-10 lg:p-12 flex items-center justify-center bg-[#F8FAFC] relative selection:bg-blue-600 selection:text-white">

    <!-- ========================================================================= -->
    <!-- 3D BACKGROUND SCENE LAYER (Sleek Volumetric Torus & 3D Glass Orbs)        -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        
        <!-- Subtle Ambient Light Wash on Canvas -->
        <div class="absolute inset-0 bg-radial-at-c from-blue-50/40 via-transparent to-transparent"></div>

        <!-- Delicate dot matrix canvas background -->
        <svg class="absolute inset-0 w-full h-full text-slate-300/35 opacity-40" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="bgDots" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.1" fill="currentColor" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#bgDots)" />
        </svg>

        <!-- TOP 3D TORUS & ORBITAL RINGS (Emerging behind card) -->
        <div class="absolute -top-44 left-1/2 -translate-x-1/2 md:-top-56 md:left-[35%] w-[680px] h-[680px] md:w-[860px] md:h-[860px] anim-3d-top">
            <!-- Ambient Radial Glow Halo -->
            <div class="absolute inset-20 rounded-full bg-gradient-to-tr from-blue-500/22 via-sky-400/25 to-teal-400/18 blur-[90px]"></div>

            <svg class="w-full h-full" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <!-- 3D Drop Shadow -->
                    <filter id="torusShadowTop" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="26" stdDeviation="30" flood-color="#1E3A8A" flood-opacity="0.18" />
                    </filter>
                    <filter id="softGlow" x="-30%" y="-30%" width="160%" height="160%">
                        <feGaussianBlur stdDeviation="6" />
                    </filter>

                    <!-- Torus Volumetric Shading (Cylindrical gradient simulating curved surface) -->
                    <radialGradient id="torus3DTop" cx="36%" cy="30%" r="68%">
                        <stop offset="0%" stop-color="#7DD3FC" />
                        <stop offset="22%" stop-color="#38BDF8" />
                        <stop offset="55%" stop-color="#2563EB" />
                        <stop offset="85%" stop-color="#1D4ED8" />
                        <stop offset="100%" stop-color="#0F2454" />
                    </radialGradient>

                    <!-- Specular Rim Highlight Gradient -->
                    <linearGradient id="specularRimTop" x1="20%" y1="0%" x2="80%" y2="60%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.85" />
                        <stop offset="40%" stop-color="#BAE6FD" stop-opacity="0.45" />
                        <stop offset="100%" stop-color="#38BDF8" stop-opacity="0" />
                    </linearGradient>

                    <!-- Secondary Reflected Light (Bottom bounce) -->
                    <linearGradient id="bounceRimTop" x1="80%" y1="100%" x2="20%" y2="50%">
                        <stop offset="0%" stop-color="#34D399" stop-opacity="0.4" />
                        <stop offset="60%" stop-color="#38BDF8" stop-opacity="0.15" />
                        <stop offset="100%" stop-color="#1E40AF" stop-opacity="0" />
                    </linearGradient>
                </defs>

                <!-- Thin Concentric Perspective Orbital Rings -->
                <ellipse cx="400" cy="400" rx="385" ry="370" stroke="#38BDF8" stroke-opacity="0.22" stroke-width="1.2" transform="rotate(-12 400 400)" />
                <ellipse cx="400" cy="400" rx="340" ry="325" stroke="#60A5FA" stroke-opacity="0.18" stroke-width="1" stroke-dasharray="8 12" transform="rotate(-12 400 400)" />
                <ellipse cx="400" cy="400" rx="290" ry="275" stroke="#93C5FD" stroke-opacity="0.25" stroke-width="1.4" transform="rotate(-12 400 400)" />

                <!-- The 3D Volumetric Torus Body (Donut) -->
                <path d="M 400,145 A 255,255 0 1,0 400,655 A 255,255 0 1,0 400,145 Z M 400,230 A 170,170 0 1,1 400,570 A 170,170 0 1,1 400,230 Z" 
                      fill="url(#torus3DTop)" 
                      filter="url(#torusShadowTop)" />

                <!-- 3D Specular Highlight Ridge (Creates realistic curved light reflection) -->
                <path d="M 210,235 A 235,235 0 0,1 555,195" 
                      stroke="url(#specularRimTop)" 
                      stroke-width="12" 
                      stroke-linecap="round" 
                      filter="url(#softGlow)" />
                <path d="M 245,245 A 235,235 0 0,1 520,210" 
                      stroke="#FFFFFF" 
                      stroke-width="3.5" 
                      stroke-linecap="round" 
                      opacity="0.85" />

                <!-- Subtle Inner Rim Specular Arc -->
                <path d="M 400,235 A 165,165 0 0,1 555,365" 
                      stroke="#BAE6FD" 
                      stroke-width="3" 
                      stroke-linecap="round" 
                      opacity="0.5" />

                <!-- Bottom Ambient Bounce Light Arc -->
                <path d="M 240,580 A 245,245 0 0,0 550,560" 
                      stroke="url(#bounceRimTop)" 
                      stroke-width="7" 
                      stroke-linecap="round" />
            </svg>
        </div>

        <!-- BOTTOM-RIGHT 3D TORUS SECTION & ORBITAL HALO -->
        <div class="absolute -bottom-48 -right-40 md:-bottom-64 md:-right-52 w-[640px] h-[640px] md:w-[820px] md:h-[820px] anim-3d-bottom">
            <!-- Ambient Radial Glow Halo -->
            <div class="absolute inset-20 rounded-full bg-gradient-to-br from-emerald-500/22 via-teal-400/24 to-blue-500/20 blur-[95px]"></div>

            <svg class="w-full h-full" viewBox="0 0 800 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <filter id="torusShadowBR" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="24" stdDeviation="28" flood-color="#047857" flood-opacity="0.16" />
                    </filter>
                    
                    <!-- 3D Emerald to Sapphire Torus Gradient -->
                    <radialGradient id="torus3DBR" cx="38%" cy="32%" r="66%">
                        <stop offset="0%" stop-color="#6EE7B7" />
                        <stop offset="25%" stop-color="#10B981" />
                        <stop offset="58%" stop-color="#0D9488" />
                        <stop offset="85%" stop-color="#0284C7" />
                        <stop offset="100%" stop-color="#0F172A" />
                    </radialGradient>

                    <linearGradient id="specularRimBR" x1="15%" y1="10%" x2="85%" y2="70%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.85" />
                        <stop offset="45%" stop-color="#A7F3D0" stop-opacity="0.5" />
                        <stop offset="100%" stop-color="#10B981" stop-opacity="0" />
                    </linearGradient>
                </defs>

                <!-- Concentric Perspective Orbital Rings -->
                <ellipse cx="400" cy="400" rx="380" ry="365" stroke="#10B981" stroke-opacity="0.2" stroke-width="1.2" transform="rotate(18 400 400)" />
                <ellipse cx="400" cy="400" rx="330" ry="315" stroke="#14B8A6" stroke-opacity="0.18" stroke-width="1" stroke-dasharray="6 10" transform="rotate(18 400 400)" />
                <ellipse cx="400" cy="400" rx="280" ry="265" stroke="#38BDF8" stroke-opacity="0.22" stroke-width="1.4" transform="rotate(18 400 400)" />

                <!-- The 3D Volumetric Torus Body -->
                <path d="M 400,160 A 240,240 0 1,0 400,640 A 240,240 0 1,0 400,160 Z M 400,245 A 155,155 0 1,1 400,555 A 155,155 0 1,1 400,245 Z" 
                      fill="url(#torus3DBR)" 
                      filter="url(#torusShadowBR)" />

                <!-- Specular Highlight Ridge -->
                <path d="M 220,250 A 220,220 0 0,1 550,210" 
                      stroke="url(#specularRimBR)" 
                      stroke-width="10" 
                      stroke-linecap="round" 
                      filter="url(#softGlow)" />
                <path d="M 255,260 A 220,220 0 0,1 510,225" 
                      stroke="#FFFFFF" 
                      stroke-width="3" 
                      stroke-linecap="round" 
                      opacity="0.85" />
            </svg>
        </div>

        <!-- FLOATING 3D GLASS ORB 1 (Top Right - Depth of Field) -->
        <div class="absolute top-16 right-12 md:top-24 md:right-28 w-20 h-20 md:w-24 md:h-24 anim-orb-1 hidden sm:block">
            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="glassSphere1" cx="32%" cy="28%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.95" />
                        <stop offset="18%" stop-color="#BAE6FD" stop-opacity="0.9" />
                        <stop offset="50%" stop-color="#38BDF8" stop-opacity="0.7" />
                        <stop offset="80%" stop-color="#2563EB" stop-opacity="0.85" />
                        <stop offset="100%" stop-color="#0F172A" stop-opacity="0.9" />
                    </radialGradient>
                    <filter id="orbShadow1" x="-40%" y="-40%" width="180%" height="180%">
                        <feDropShadow dx="0" dy="12" stdDeviation="14" flood-color="#0284C7" flood-opacity="0.25" />
                    </filter>
                </defs>
                <circle cx="50" cy="50" r="42" fill="url(#glassSphere1)" filter="url(#orbShadow1)" />
                <ellipse cx="36" cy="30" rx="10" ry="6" fill="#FFFFFF" opacity="0.8" transform="rotate(-25 36 30)" />
            </svg>
        </div>

        <!-- FLOATING 3D GLASS ORB 2 (Bottom Left - Depth of Field) -->
        <div class="absolute bottom-20 left-10 md:bottom-28 md:left-24 w-14 h-14 md:w-16 md:h-16 anim-orb-2 hidden sm:block">
            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="glassSphere2" cx="30%" cy="28%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.95" />
                        <stop offset="22%" stop-color="#A7F3D0" stop-opacity="0.85" />
                        <stop offset="55%" stop-color="#10B981" stop-opacity="0.75" />
                        <stop offset="85%" stop-color="#0284C7" stop-opacity="0.85" />
                        <stop offset="100%" stop-color="#064E3B" stop-opacity="0.95" />
                    </radialGradient>
                    <filter id="orbShadow2" x="-40%" y="-40%" width="180%" height="180%">
                        <feDropShadow dx="0" dy="10" stdDeviation="12" flood-color="#047857" flood-opacity="0.22" />
                    </filter>
                </defs>
                <circle cx="50" cy="50" r="40" fill="url(#glassSphere2)" filter="url(#orbShadow2)" />
                <ellipse cx="38" cy="32" rx="8" ry="5" fill="#FFFFFF" opacity="0.75" transform="rotate(-25 38 32)" />
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
        <div class="md:col-span-5 bg-gradient-to-br from-[#061224] via-[#0B2349] to-[#0E356A] p-8 sm:p-10 md:p-12 text-white flex flex-col justify-center relative overflow-hidden">
            
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
            </div>
        </div>

        <!-- ===================================================================== -->
        <!-- RIGHT FORM (7 Cols): Clean, Spacious & Professional Form             -->
        <!-- ===================================================================== -->
        <div class="md:col-span-7 p-8 sm:p-10 md:p-12 lg:p-14 flex flex-col justify-center bg-white relative">
            
            <div class="mb-7">
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
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm py-3.5 rounded-xl shadow-sm transition-all hover:shadow-md active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer mt-2">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
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
