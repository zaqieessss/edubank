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

        /* Subtle smooth float for realistic credit card */
        @keyframes float-card {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-5px) rotate(0.4deg);
            }
        }

        .animate-card-float {
            animation: float-card 8s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex items-center justify-center bg-slate-50 selection:bg-blue-600 selection:text-white p-4 sm:p-6 lg:p-8">

    <!-- ========================================================================= -->
    <!-- CLEAN AMBIENT BACKDROP (Minimalist & Static)                              -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        <!-- Soft top-left ambient light -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-100/60 rounded-full blur-3xl"></div>
        <!-- Soft bottom-right ambient light -->
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-slate-200/50 rounded-full blur-3xl"></div>
    </div>

    <!-- ========================================================================= -->
    <!-- MAIN LOGIN CARD (Static, Perfectly Balanced, High Whitespace)             -->
    <!-- ========================================================================= -->
    <div class="relative w-full max-w-4xl max-h-[92vh] sm:max-h-[600px] bg-white rounded-3xl border border-slate-200/80 shadow-[0_20px_50px_-15px_rgba(15,23,42,0.07)] overflow-hidden grid grid-cols-1 md:grid-cols-12 z-10">

        <!-- ===================================================================== -->
        <!-- LEFT HERO (5 Cols): Clean, Tasteful Mini-Bank Card Illustration        -->
        <!-- ===================================================================== -->
        <div class="hidden md:flex md:col-span-5 bg-gradient-to-br from-[#0B1528] via-[#0F1E38] to-[#14284B] p-8 lg:p-10 text-white flex-col justify-between relative overflow-hidden select-none">
            
            <!-- Subtle geometric background lines -->
            <div class="absolute inset-0 pointer-events-none opacity-20">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="cleanGrid" width="32" height="32" patternUnits="userSpaceOnUse">
                            <path d="M 32 0 L 0 0 0 32" fill="none" stroke="white" stroke-width="0.5" stroke-opacity="0.2" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#cleanGrid)" />
                </svg>
            </div>

            <!-- Soft localized ambient glow -->
            <div class="absolute -top-16 -left-16 w-48 h-48 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <!-- TOP BRAND HEADER -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 p-2 shadow-sm inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-sm tracking-wide text-white">Edubank</span>
                            <span class="px-1.5 py-0.2 rounded bg-emerald-500/20 border border-emerald-400/30 text-[9px] font-semibold text-emerald-300">Siswa</span>
                        </div>
                        <p class="text-[11px] text-slate-300 font-medium">SMKN 1 Bantul</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h1 class="text-xl lg:text-2xl font-bold tracking-tight text-white leading-snug">
                        Layanan Bank Mini <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-sky-200">Modern & Terintegrasi</span>
                    </h1>
                    <p class="text-xs text-slate-300/80 mt-2 leading-relaxed">
                        Platform operasional teller, pembukuan kas sekolah, dan tabungan siswa yang aman serta transparan.
                    </p>
                </div>
            </div>

            <!-- CENTER/BOTTOM: TASTEFUL FINTECH CARD ILLUSTRATION (Tidak Alay, Bersih & Profesional) -->
            <div class="relative z-10 my-auto py-4">
                <div class="relative w-full max-w-[270px] lg:max-w-[290px] mx-auto animate-card-float">
                    
                    <!-- The Digital Smart Card -->
                    <div class="relative rounded-2xl p-4 lg:p-5 bg-gradient-to-tr from-slate-900/90 via-[#0B2349]/90 to-blue-900/80 border border-white/20 shadow-2xl shadow-black/40 text-white backdrop-blur-md">
                        <!-- Diagonal Sheen Accent -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white/15 via-transparent to-transparent pointer-events-none rounded-2xl"></div>

                        <!-- Card Header: EMV Chip & Contactless Wave -->
                        <div class="flex items-center justify-between mb-5 relative z-10">
                            <div class="flex items-center gap-2">
                                <!-- Realistic Metallic Chip -->
                                <div class="w-8 h-6 rounded bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 p-[1px] shadow-sm border border-amber-300/50">
                                    <div class="w-full h-full border border-amber-800/25 rounded-[1px] flex items-center justify-center">
                                        <div class="w-2.5 h-full border-x border-amber-800/30"></div>
                                    </div>
                                </div>
                                <!-- Contactless wave -->
                                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 16.5a5 5 0 010-9M12 19a8.5 8.5 0 000-14m3.5 16.5a12 12 0 000-19" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold tracking-widest text-white/80">EDUBANK</span>
                        </div>

                        <!-- Card Number (Monospace) -->
                        <div class="mb-4 relative z-10">
                            <div class="text-xs lg:text-sm font-mono tracking-widest text-slate-100">
                                •••• •••• •••• 2026
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="flex items-end justify-between relative z-10 text-[9px]">
                            <div>
                                <span class="text-[8px] uppercase tracking-wider text-slate-400 block">Nama Nasabah</span>
                                <span class="font-semibold text-white tracking-wide">SISWA SMKN 1 BANTUL</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[8px] uppercase tracking-wider text-slate-400 block">Valid Thru</span>
                                <span class="font-mono text-white/90">12/28</span>
                            </div>
                        </div>
                    </div>

                    <!-- Clean Floating Status Pill (Top-Right) -->
                    <div class="absolute -top-2.5 -right-2 bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-full px-2.5 py-1 shadow-md flex items-center gap-1.5 z-20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[9px] font-bold text-slate-700">Loket Teller Aktif</span>
                    </div>

                    <!-- Clean Floating Transaction Pill (Bottom-Left) -->
                    <div class="absolute -bottom-2.5 -left-2 bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-xl px-2.5 py-1.5 shadow-md flex items-center gap-2 z-20">
                        <div class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        </div>
                        <div>
                            <span class="text-[8px] text-slate-400 block font-medium leading-none">Tabungan</span>
                            <span class="text-[10px] font-bold text-slate-800 font-mono leading-none">+ Rp 50.000</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- BOTTOM FOOTER NOTE -->
            <div class="relative z-10 text-[11px] text-slate-400 flex items-center justify-between">
                <span>© 2026 Edubank</span>
                <span class="flex items-center gap-1 text-[10px] text-slate-400">
                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Sistem Terenkripsi
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