<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Edubank SMKN 1 Bantul</title>

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

        /* Subtle smooth float for realistic 3D elements */
        @keyframes float-gentle {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes float-gentle-reverse {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(8px);
            }
        }

        .anim-3d-float {
            animation: float-gentle 10s ease-in-out infinite;
        }

        .anim-3d-float-rev {
            animation: float-gentle-reverse 12s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex items-center justify-center bg-[#F8FAFC] selection:bg-blue-600 selection:text-white p-4 sm:p-6 lg:p-8 relative">

    <!-- ========================================================================= -->
    <!-- 3D BACKGROUND (Studio Lighting, Frosted Pearl & Sapphire 3D Geometry)      -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        
        <!-- Subtle studio backdrop vignette -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-50/70 via-slate-100/40 to-slate-200/30"></div>

        <!-- TOP-RIGHT: 3D Frosted Sapphire & Pearl Sphere (Elegan & Soft) -->
        <div class="absolute -top-20 -right-20 sm:-top-28 sm:-right-28 w-[380px] h-[380px] sm:w-[500px] sm:h-[500px] opacity-75 anim-3d-float">
            <!-- Ambient Glow Halo -->
            <div class="absolute inset-16 rounded-full bg-blue-400/20 blur-[80px]"></div>

            <svg class="w-full h-full" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <!-- Realistic Volumetric 3D Sphere Gradient -->
                    <radialGradient id="sphere3DGrad" cx="32%" cy="28%" r="68%">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="20%" stop-color="#E0F2FE" />
                        <stop offset="48%" stop-color="#BAE6FD" />
                        <stop offset="72%" stop-color="#60A5FA" />
                        <stop offset="92%" stop-color="#2563EB" />
                        <stop offset="100%" stop-color="#1E3A8A" />
                    </radialGradient>

                    <!-- Studio Contact Drop Shadow -->
                    <filter id="studioShadow" x="-25%" y="-25%" width="150%" height="150%">
                        <feDropShadow dx="-15" dy="25" stdDeviation="30" flood-color="#0F172A" flood-opacity="0.10" />
                    </filter>

                    <!-- Specular Light Reflection -->
                    <linearGradient id="specular3D" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.85" />
                        <stop offset="40%" stop-color="#FFFFFF" stop-opacity="0.25" />
                        <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0" />
                    </linearGradient>
                </defs>

                <!-- The 3D Sphere Body -->
                <circle cx="300" cy="300" r="220" fill="url(#sphere3DGrad)" filter="url(#studioShadow)" />

                <!-- Curved Specular Key Light -->
                <ellipse cx="230" cy="200" rx="90" ry="55" transform="rotate(-30 230 200)" fill="url(#specular3D)" />
            </svg>
        </div>

        <!-- BOTTOM-LEFT: 3D Smooth Ceramic Torus / Disc (Clean & Calming) -->
        <div class="absolute -bottom-24 -left-24 sm:-bottom-32 sm:-left-32 w-[380px] h-[380px] sm:w-[480px] sm:h-[480px] opacity-65 anim-3d-float-rev">
            <!-- Ambient Glow Halo -->
            <div class="absolute inset-16 rounded-full bg-slate-400/20 blur-[80px]"></div>

            <svg class="w-full h-full" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="ring3DGrad" cx="38%" cy="32%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="25%" stop-color="#F1F5F9" />
                        <stop offset="55%" stop-color="#CBD5E1" />
                        <stop offset="85%" stop-color="#64748B" />
                        <stop offset="100%" stop-color="#334155" />
                    </radialGradient>
                    <filter id="ring3DShadow" x="-25%" y="-25%" width="150%" height="150%">
                        <feDropShadow dx="15" dy="25" stdDeviation="28" flood-color="#0F172A" flood-opacity="0.08" />
                    </filter>
                </defs>

                <!-- Smooth 3D Torus Ring -->
                <path d="M 300,100 A 200,200 0 1,0 300,500 A 200,200 0 1,0 300,100 Z M 300,190 A 110,110 0 1,1 300,410 A 110,110 0 1,1 300,190 Z" 
                      fill="url(#ring3DGrad)" 
                      filter="url(#ring3DShadow)" />
            </svg>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MAIN LOGIN CARD (Static, Perfectly Balanced, High Whitespace)             -->
    <!-- ========================================================================= -->
    <div class="relative w-full max-w-4xl max-h-[92vh] sm:max-h-[580px] bg-white rounded-3xl border border-slate-200/90 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.08)] overflow-hidden grid grid-cols-1 md:grid-cols-12 z-10">

        <!-- ===================================================================== -->
        <!-- LEFT HERO (5 Cols): Clean, Elegan & Minimalist Card                    -->
        <!-- ===================================================================== -->
        <div class="hidden md:flex md:col-span-5 bg-gradient-to-b from-[#0B1528] via-[#0F1E38] to-[#0A1222] p-8 lg:p-10 text-white flex-col justify-between relative overflow-hidden select-none">
            
            <!-- Soft localized ambient glow -->
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <!-- TOP BRAND HEADER -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 p-2 shadow-sm inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-sm tracking-wide text-white">Edubank</span>
                            <span class="px-1.5 py-0.2 rounded bg-blue-500/20 border border-blue-400/30 text-[9px] font-semibold text-blue-300">Bank Mini</span>
                        </div>
                        <p class="text-[11px] text-slate-300 font-medium">SMKN 1 Bantul</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h1 class="text-xl lg:text-2xl font-bold tracking-tight text-white leading-snug">
                        Layanan Bank Mini <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-sky-200">Siswa & Sekolah</span>
                    </h1>
                    <p class="text-xs text-slate-300/80 mt-2 leading-relaxed">
                        Satu portal cerdas untuk transaksi loket teller, rekapitulasi kas harian, dan tabungan siswa.
                    </p>
                </div>
            </div>

            <!-- CENTER: SEDERHANA, CLEAN & ELEGAN SMART CARD -->
            <div class="relative z-10 my-auto py-2">
                <div class="relative w-full max-w-[260px] mx-auto">
                    
                    <!-- Sleek Minimalist Metal/Glass Card -->
                    <div class="relative rounded-2xl p-5 bg-gradient-to-tr from-white/10 via-white/5 to-transparent border border-white/20 shadow-xl shadow-black/30 text-white backdrop-blur-xl">
                        <!-- Diagonal Glass Sheen -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white/15 via-transparent to-transparent pointer-events-none rounded-2xl"></div>

                        <!-- Card Top: Microchip & Contactless Wave -->
                        <div class="flex items-center justify-between mb-6 relative z-10">
                            <!-- Clean Platinum/Gold Chip -->
                            <div class="w-7 h-5.5 rounded bg-gradient-to-br from-amber-100 via-amber-300 to-amber-500 p-[1px] shadow-xs border border-amber-200/50">
                                <div class="w-full h-full border border-amber-700/20 rounded-[1px] flex items-center justify-center">
                                    <div class="w-2 h-full border-x border-amber-700/25"></div>
                                </div>
                            </div>

                            <!-- Contactless symbol -->
                            <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 16.5a5 5 0 010-9M12 19a8.5 8.5 0 000-14m3.5 16.5a12 12 0 000-19" />
                            </svg>
                        </div>

                        <!-- Card Middle: Masked Account Number -->
                        <div class="mb-5 relative z-10">
                            <span class="text-xs font-mono tracking-widest text-slate-100">
                                •••• •••• •••• 2026
                            </span>
                        </div>

                        <!-- Card Bottom: Clean Metadata -->
                        <div class="flex items-end justify-between relative z-10 text-[9px]">
                            <div>
                                <span class="text-[8px] uppercase tracking-wider text-slate-400 block font-medium">Nasabah</span>
                                <span class="font-semibold text-white tracking-wide">EDUBANK SISWA</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[8px] uppercase tracking-wider text-slate-400 block font-medium">Status</span>
                                <span class="font-semibold text-emerald-300 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span> Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- BOTTOM FOOTER NOTE -->
            <div class="relative z-10 text-[11px] text-slate-400 flex items-center justify-between">
                <span>© 2026 Edubank</span>
                <span class="flex items-center gap-1.5 text-[10px] text-slate-400">
                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Terkoneksi Aman
                </span>
            </div>

        </div>

        <!-- ===================================================================== -->
        <!-- RIGHT FORM (7 Cols): Clear, Clean, Static & High Readability          -->
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
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">Masukkan kredensial Anda untuk mengakses dashboard.</p>
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

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    <!-- Identifier Field (Email / Username / No. Rekening) -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Username, Email, atau No. Rekening
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus
                                   class="w-full bg-slate-50/80 border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 sm:py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-500/15 transition-all"
                                   placeholder="nama@email.com atau RPL-29-0001">
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700">Kata Sandi</label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-medium text-blue-600 hover:text-blue-700 hover:underline">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required
                                   class="w-full bg-slate-50/80 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 sm:py-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-3 focus:ring-blue-500/15 transition-all"
                                   placeholder="••••••••">
                            <button type="button" 
                                    onclick="togglePasswordVisibility()" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none"
                                    title="Lihat / Sembunyikan Sandi">
                                <svg id="eyeIconOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg id="eyeIconClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer">
                            <span class="text-xs text-slate-600">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs sm:text-sm py-2.5 sm:py-3 rounded-xl shadow-sm transition-all hover:shadow active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Help / Support Note -->
                <div class="mt-6 text-center">
                    <p class="text-[11px] text-slate-400">
                        Butuh bantuan login? Hubungi Administrator Bank Mini.
                    </p>
                </div>

            </div>
        </div>

    </div>

    <!-- Password visibility toggle script -->
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