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
    <!-- BRIGHT 3D BACKGROUND (Tema Dasar Edubank: Biru Cerah, Sky, Bersih & Statis) -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        
        <!-- Radiant Ambient Aurora Glow (Bright Edubank Palette) -->
        <div class="absolute -top-24 -left-24 w-[500px] h-[500px] rounded-full bg-gradient-to-br from-blue-400/25 via-sky-300/20 to-transparent blur-[110px]"></div>
        <div class="absolute -bottom-28 -right-28 w-[550px] h-[550px] rounded-full bg-gradient-to-tl from-sky-400/25 via-blue-400/20 to-transparent blur-[120px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] rounded-full bg-blue-200/20 blur-[140px]"></div>

        <!-- TOP-RIGHT: Static 3D Glossy Sapphire & Cyan Sphere (Tema Biru Edubank) -->
        <div class="absolute -top-16 -right-16 sm:-top-24 sm:-right-24 w-[360px] h-[360px] sm:w-[480px] sm:h-[480px] opacity-85">
            <svg class="w-full h-full" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <!-- Bright 3D Edubank Sphere Gradient -->
                    <radialGradient id="edubank3DSphere" cx="35%" cy="30%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="22%" stop-color="#BAE6FD" />
                        <stop offset="50%" stop-color="#38BDF8" />
                        <stop offset="78%" stop-color="#2563EB" />
                        <stop offset="100%" stop-color="#1D4ED8" />
                    </radialGradient>

                    <!-- Soft Drop Shadow -->
                    <filter id="sphereCleanShadow" x="-25%" y="-25%" width="150%" height="150%">
                        <feDropShadow dx="-10" dy="20" stdDeviation="28" flood-color="#1D4ED8" flood-opacity="0.15" />
                    </filter>

                    <!-- Gloss Specular Highlight -->
                    <linearGradient id="glossHighlight" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.9" />
                        <stop offset="45%" stop-color="#FFFFFF" stop-opacity="0.2" />
                        <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0" />
                    </linearGradient>
                </defs>

                <!-- 3D Outer Halo Ring -->
                <circle cx="300" cy="300" r="245" stroke="#38BDF8" stroke-opacity="0.25" stroke-width="2" />
                <circle cx="300" cy="300" r="265" stroke="#60A5FA" stroke-opacity="0.15" stroke-width="1.5" stroke-dasharray="6 8" />

                <!-- The 3D Sphere -->
                <circle cx="300" cy="300" r="200" fill="url(#edubank3DSphere)" filter="url(#sphereCleanShadow)" />

                <!-- Specular Reflection -->
                <ellipse cx="240" cy="210" rx="75" ry="45" transform="rotate(-30 240 210)" fill="url(#glossHighlight)" />
            </svg>
        </div>

        <!-- BOTTOM-LEFT: Static 3D Sky-Blue Torus Ring (Bersih, Cerah & Statis) -->
        <div class="absolute -bottom-20 -left-20 sm:-bottom-28 sm:-left-28 w-[350px] h-[350px] sm:w-[460px] sm:h-[460px] opacity-75">
            <svg class="w-full h-full" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="edubank3DRing" cx="36%" cy="32%" r="68%">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="25%" stop-color="#E0F2FE" />
                        <stop offset="58%" stop-color="#7DD3FC" />
                        <stop offset="85%" stop-color="#0284C7" />
                        <stop offset="100%" stop-color="#0369A1" />
                    </radialGradient>
                    <filter id="ringCleanShadow" x="-25%" y="-25%" width="150%" height="150%">
                        <feDropShadow dx="12" dy="20" stdDeviation="26" flood-color="#0284C7" flood-opacity="0.12" />
                    </filter>
                </defs>

                <!-- Concentric Delicate Orbit -->
                <circle cx="300" cy="300" r="255" stroke="#0284C7" stroke-opacity="0.2" stroke-width="1.5" />

                <!-- Smooth 3D Torus Ring -->
                <path d="M 300,110 A 190,190 0 1,0 300,490 A 190,190 0 1,0 300,110 Z M 300,195 A 105,105 0 1,1 300,405 A 105,105 0 1,1 300,195 Z" 
                      fill="url(#edubank3DRing)" 
                      filter="url(#ringCleanShadow)" />
            </svg>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- MAIN LOGIN CARD (Static, Perfectly Balanced, High Whitespace)             -->
    <!-- ========================================================================= -->
    <div class="relative w-full max-w-4xl max-h-[92vh] sm:max-h-[580px] bg-white rounded-3xl border border-blue-100/80 shadow-[0_20px_60px_-15px_rgba(37,99,235,0.12)] overflow-hidden grid grid-cols-1 md:grid-cols-12 z-10">

        <!-- ===================================================================== -->
        <!-- LEFT HERO (5 Cols): Clean, Elegan & Cerah Sesuai Tema Edubank          -->
        <!-- ===================================================================== -->
        <div class="hidden md:flex md:col-span-5 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-8 lg:p-11 text-white flex-col justify-between relative overflow-hidden select-none">
            
            <!-- Soft Internal Ambient Light -->
            <div class="absolute -top-16 -left-16 w-56 h-56 bg-sky-300/25 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-indigo-400/20 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Subtle Grid Accent -->
            <div class="absolute inset-0 pointer-events-none opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="cardGrid" width="28" height="28" patternUnits="userSpaceOnUse">
                            <path d="M 28 0 L 0 0 0 28" fill="none" stroke="white" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#cardGrid)" />
                </svg>
            </div>

            <!-- TOP BRAND HEADER -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 p-2 shadow-sm inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-base tracking-wide text-white">Edubank</span>
                            <span class="px-2 py-0.5 rounded-full bg-white/20 border border-white/30 text-[9px] font-semibold text-white">Bank Mini</span>
                        </div>
                        <p class="text-xs text-blue-100 font-medium">SMKN 1 Bantul</p>
                    </div>
                </div>
            </div>

            <!-- CENTER CONTENT: TYPOGRAPHY & CLEAN FEATURE PILLS (Tanpa Ilustrasi Kartu) -->
            <div class="relative z-10 my-auto py-6">
                <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-white leading-snug">
                    Kelola Layanan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-200 via-white to-blue-200">Bank Mini</span> Lebih Cepat & Terintegrasi
                </h1>
                
                <p class="text-xs sm:text-sm text-blue-100/90 mt-3.5 leading-relaxed font-normal">
                    Satu portal cerdas untuk layanan transaksi loket Teller, pengawasan kas harian Administrator, dan kemudahan pemantauan saldo tabungan siswa.
                </p>

                <!-- Clean Minimalist Key Highlights (Sederhana & Elegan) -->
                <div class="mt-6 space-y-2.5">
                    <div class="flex items-center gap-2.5 text-xs text-white/95">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-medium">Layanan Transaksi Loket Real-Time</span>
                    </div>

                    <div class="flex items-center gap-2.5 text-xs text-white/95">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-medium">Rekapitulasi Kas & Jurnal Otomatis</span>
                    </div>

                    <div class="flex items-center gap-2.5 text-xs text-white/95">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-medium">Monitoring Saldo & Tabungan Siswa</span>
                    </div>
                </div>
            </div>

            <!-- BOTTOM FOOTER NOTE (Tanpa 'Terkoneksi Aman') -->
            <div class="relative z-10 text-xs text-blue-200/90 font-medium">
                © 2026 Edubank SMKN 1 Bantul
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