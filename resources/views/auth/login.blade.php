<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Edubank SMKN 1 Bantul</title>

    <!-- Favicon (Icon Tab Browser) -->
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
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-10 lg:p-12 flex items-center justify-center bg-[#F8FAFC]">

    <!-- Floating Login Container (Besar, Mewah, dan Proporsional) -->
    <div class="w-full max-w-5xl min-h-[620px] bg-white rounded-[32px] border border-slate-200/90 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.12)] overflow-hidden grid grid-cols-1 md:grid-cols-12 relative">
        
        <!-- LEFT HERO (5 Cols): Branding Elegan & Berkelas (Midnight Sapphire & Geometric Art) -->
        <div class="md:col-span-5 bg-gradient-to-br from-[#0B1528] via-[#102A54] to-[#1E3A8A] p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            
            <!-- Geometric Background Illustration Layer (Aesthetic & Luxury Bank Look) -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none select-none">
                <!-- Ambient Sapphire Glows -->
                <div class="absolute -top-24 -left-24 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-500/25 rounded-full blur-3xl"></div>
                
                <!-- Geometric Concentric Rings & Radial Crosshair (Guilloche / Security Token Aesthetic) -->
                <svg class="absolute -right-16 -top-16 w-80 h-80 text-blue-300/15" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="200" r="185" stroke="currentColor" stroke-width="1" stroke-dasharray="4 6" opacity="0.4" />
                    <circle cx="200" cy="200" r="145" stroke="currentColor" stroke-width="1.2" opacity="0.6" />
                    <circle cx="200" cy="200" r="105" stroke="currentColor" stroke-width="1" stroke-dasharray="3 4" opacity="0.5" />
                    <circle cx="200" cy="200" r="65" stroke="currentColor" stroke-width="1.5" opacity="0.8" />
                    <line x1="20" y1="200" x2="380" y2="200" stroke="currentColor" stroke-width="0.8" opacity="0.3" />
                    <line x1="200" y1="20" x2="200" y2="380" stroke="currentColor" stroke-width="0.8" opacity="0.3" />
                    <line x1="70" y1="70" x2="330" y2="330" stroke="currentColor" stroke-width="0.8" stroke-dasharray="3 3" opacity="0.25" />
                    <line x1="70" y1="330" x2="330" y2="70" stroke="currentColor" stroke-width="0.8" stroke-dasharray="3 3" opacity="0.25" />
                    <circle cx="200" cy="55" r="3.5" fill="#60A5FA" opacity="0.9" />
                    <circle cx="345" cy="200" r="3.5" fill="#60A5FA" opacity="0.9" />
                    <circle cx="200" cy="345" r="3.5" fill="#38BDF8" opacity="0.9" />
                    <circle cx="55" cy="200" r="3.5" fill="#38BDF8" opacity="0.9" />
                </svg>

                <!-- Isometric Geometric Dot Matrix Pattern (Bottom Right) -->
                <svg class="absolute -bottom-8 right-0 w-64 h-64 text-blue-200/10 opacity-70" viewBox="0 0 200 200" fill="none">
                    <defs>
                        <pattern id="loginDotPattern" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.2" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="200" height="200" fill="url(#loginDotPattern)" />
                </svg>

                <!-- Floating Glassmorphic Geometric Polygon (Hexagon/Diamond Facet) -->
                <div class="absolute top-1/2 right-3 -translate-y-1/2 w-28 h-28 border border-white/15 rounded-3xl rotate-45 bg-gradient-to-tr from-white/5 to-transparent backdrop-blur-xs opacity-60">
                    <div class="absolute inset-3 border border-blue-400/20 rounded-2xl rotate-12"></div>
                </div>
            </div>

            <div class="relative z-10">
                <div class="mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-2.5 shadow-md inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-full h-full object-contain drop-shadow-md">
                    </div>
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">Edubank</h1>
                <p class="text-sm text-blue-200 font-semibold mt-1">Bank Mini Sekolah Digital</p>

                <p class="text-sm text-slate-300/90 mt-5 leading-relaxed">
                    Sistem perbankan sekolah yang dirancang untuk mendukung literasi keuangan, tabungan siswa, dan transparansi transaksi sekolah.
                </p>

                <!-- Keunggulan Singkat (Glassmorphism Pill) -->
                <div class="mt-8 space-y-3 pt-6 border-t border-white/15 text-xs text-blue-100/90">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-lg bg-blue-500/20 border border-blue-400/30 text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-medium text-slate-200">Pencatatan Transaksi & Mutasi Real-Time</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-lg bg-blue-500/20 border border-blue-400/30 text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <span class="font-medium text-slate-200">Keamanan PIN & Otorisasi Bertingkat</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-lg bg-blue-500/20 border border-blue-400/30 text-blue-300 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="font-medium text-slate-200">Terintegrasi Mobile & Web Dashboard</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 pt-8 text-xs text-blue-300/70 font-medium">
                &copy; {{ date('Y') }} SMKN 1 Bantul • Hak Cipta Dilindungi
            </div>
        </div>

        <!-- RIGHT FORM (7 Cols): Form Login Luas, Bersih & Nyaman -->
        <div class="md:col-span-7 p-8 md:p-12 lg:p-14 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
                <p class="text-sm text-slate-500 mt-2">Masuk ke akun Anda untuk mengakses sistem Edubank.</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-xs"
                               placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-600 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input with Toggle Eye -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Kata Sandi</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline">Lupa Password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-12 py-3.5 text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-xs"
                               placeholder="••••••••">
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none"
                                title="Lihat/Sembunyikan Kata Sandi">
                            <svg id="eyeIconOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eyeIconClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer">
                        <span class="text-sm text-slate-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button (Terang & Elegan Sesuai Tema Minibank) -->
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-blue-600/30 transition-all hover:shadow-lg hover:shadow-blue-600/40 active:scale-[0.99] flex items-center justify-center gap-2.5 cursor-pointer mt-4">
                    <span>Masuk ke Akun</span>
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
