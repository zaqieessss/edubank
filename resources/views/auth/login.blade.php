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
                <pattern id="loginIsoGrid" width="60" height="103.92" patternUnits="userSpaceOnUse">
                    <path d="M 30 0 L 60 17.32 L 60 51.96 L 30 69.28 L 0 51.96 L 0 17.32 Z M 30 103.92 L 60 86.6 L 60 51.96 L 30 69.28 L 0 51.96 L 0 86.6 Z" 
                          fill="none" 
                          stroke="currentColor" 
                          stroke-width="0.8" 
                          stroke-opacity="0.3" />
                </pattern>
                <radialGradient id="loginGridFade" cx="50%" cy="50%" r="55%">
                    <stop offset="0%" stop-color="black" stop-opacity="0.05" />
                    <stop offset="65%" stop-color="black" stop-opacity="0.5" />
                    <stop offset="100%" stop-color="black" stop-opacity="0.85" />
                </radialGradient>
                <mask id="loginMask">
                    <rect width="100%" height="100%" fill="url(#loginGridFade)" />
                </mask>
            </defs>
            <rect width="100%" height="100%" fill="url(#loginIsoGrid)" mask="url(#loginMask)" />
        </svg>

        <!-- Top-Right Prismatic Facet Accent -->
        <div class="absolute -top-16 -right-16 w-80 h-80 sm:w-96 sm:h-96 opacity-60">
            <svg class="w-full h-full" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="loginPrism1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#38BDF8" />
                        <stop offset="100%" stop-color="#1D4ED8" />
                    </linearGradient>
                    <linearGradient id="loginPrism2" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#93C5FD" />
                        <stop offset="100%" stop-color="#2563EB" />
                    </linearGradient>
                    <linearGradient id="loginPrism3" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="100%" stop-color="#60A5FA" />
                    </linearGradient>
                </defs>
                <polygon points="150,20 280,95 280,245 150,170" fill="url(#loginPrism1)" opacity="0.4" />
                <polygon points="150,20 20,95 20,245 150,170" fill="url(#loginPrism2)" opacity="0.6" />
                <polygon points="20,95 150,20 280,95 150,170" fill="url(#loginPrism3)" opacity="0.8" />
            </svg>
        </div>

        <!-- Bottom-Left Prismatic Facet Accent -->
        <div class="absolute -bottom-16 -left-16 w-72 h-72 sm:w-88 sm:h-88 opacity-50">
            <svg class="w-full h-full" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <polygon points="150,30 270,100 270,240 150,170" fill="url(#loginPrism2)" opacity="0.5" />
                <polygon points="150,30 30,100 30,240 150,170" fill="url(#loginPrism1)" opacity="0.7" />
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
                                <linearGradient id="chartLineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.3" />
                                    <stop offset="60%" stop-color="#38BDF8" />
                                    <stop offset="100%" stop-color="#FFFFFF" />
                                </linearGradient>
                                <linearGradient id="barGrad1" x1="0%" y1="100%" x2="0%" y2="0%">
                                    <stop offset="0%" stop-color="#1E40AF" stop-opacity="0.2" />
                                    <stop offset="100%" stop-color="#60A5FA" stop-opacity="0.6" />
                                </linearGradient>
                                <linearGradient id="barGrad2" x1="0%" y1="100%" x2="0%" y2="0%">
                                    <stop offset="0%" stop-color="#1E40AF" stop-opacity="0.3" />
                                    <stop offset="100%" stop-color="#38BDF8" stop-opacity="0.8" />
                                </linearGradient>
                                <linearGradient id="shieldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.25" />
                                    <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0.05" />
                                </linearGradient>
                            </defs>

                            <!-- Background Reference Grid -->
                            <line x1="15" y1="20" x2="265" y2="20" stroke="white" stroke-opacity="0.08" stroke-dasharray="3 3" />
                            <line x1="15" y1="50" x2="265" y2="50" stroke="white" stroke-opacity="0.08" stroke-dasharray="3 3" />
                            <line x1="15" y1="80" x2="265" y2="80" stroke="white" stroke-opacity="0.12" />

                            <!-- Financial Growth Bars -->
                            <rect x="35" y="55" width="16" height="25" rx="3.5" fill="url(#barGrad1)" />
                            <rect x="65" y="40" width="16" height="40" rx="3.5" fill="url(#barGrad1)" />
                            <rect x="95" y="28" width="16" height="52" rx="3.5" fill="url(#barGrad2)" />
                            <rect x="125" y="16" width="16" height="64" rx="3.5" fill="url(#barGrad2)" />

                            <!-- Upward Growth Trend Curve -->
                            <path d="M 25 68 Q 70 55, 105 35 T 150 12" stroke="url(#chartLineGrad)" stroke-width="2.2" stroke-linecap="round" />
                            <circle cx="150" cy="12" r="4.5" fill="#38BDF8" />
                            <circle cx="150" cy="12" r="2" fill="#FFFFFF" />

                            <!-- Floating Secure Shield / Vault Emblem -->
                            <g transform="translate(185, 12)">
                                <path d="M 28 4 C 44 4, 52 10, 52 24 C 52 40, 34 52, 28 56 C 22 52, 4 40, 4 24 C 4 10, 12 4, 28 4 Z" 
                                      fill="url(#shieldGrad)" 
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