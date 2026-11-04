<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Edubank SMKN 1 Bantul</title>

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
<body class="min-h-screen p-3 md:p-6 flex items-center justify-center bg-[#F8FAFC]">

    <!-- Container Form (Identik dengan Desain Form Login) -->
    <div class="w-full max-w-4xl bg-white rounded-[32px] border border-slate-200/90 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.1)] overflow-hidden grid grid-cols-1 md:grid-cols-12 relative">
        
        <!-- LEFT HERO (5 Cols): Branding & Atmosphere (Terang Sesuai Tema Minibank) -->
        <div class="md:col-span-5 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Background Glow Terang -->
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-sky-400/25 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/30 flex items-center justify-center mb-6 shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold mt-3 tracking-tight leading-snug">Edubank</h1>
                <p class="text-xs text-blue-100 font-semibold mt-1 leading-relaxed">
                    Bank Mini SMKN 1 Bantul
                </p>
            </div>

            <!-- Steps Mini Checklist -->
            <div class="relative z-10 space-y-2.5 pt-6 border-t border-white/20 text-[11px] text-blue-50 font-medium">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-white text-blue-700 flex items-center justify-center text-[10px] font-extrabold shadow-xs">1</span>
                    <span class="font-bold text-white">Masukkan alamat email terdaftar</span>
                </div>
                <div class="flex items-center gap-2 text-white/60">
                    <span class="w-5 h-5 rounded-full bg-white/15 text-white/70 flex items-center justify-center text-[10px] font-bold">2</span>
                    <span>Verifikasi 6 digit kode OTP email</span>
                </div>
                <div class="flex items-center gap-2 text-white/60">
                    <span class="w-5 h-5 rounded-full bg-white/15 text-white/70 flex items-center justify-center text-[10px] font-bold">3</span>
                    <span>Buat kata sandi baru yang aman</span>
                </div>
            </div>

            <div class="relative z-10 pt-4 text-[10px] text-blue-200 font-medium">
                &copy; {{ date('Y') }} SMKN 1 Bantul
            </div>
        </div>

        <!-- RIGHT FORM (7 Cols): Form Input Email -->
        <div class="md:col-span-7 p-7 md:p-10 flex flex-col justify-center bg-white">
            <div class="mb-6">
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Lupa Kata Sandi?</h2>
                <p class="text-xs text-slate-500 mt-1">Masukkan email akun Anda. Kami akan mengirimkan 6 digit kode verifikasi OTP.</p>
            </div>

            @if(session('error'))
                <div class="mb-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email Terdaftar</label>
                    <div class="relative">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               placeholder="nama@edubank.test">
                    </div>
                    @error('email')
                        <p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button (Terang Sesuai Tema Minibank) -->
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs py-3.5 rounded-xl shadow-md shadow-blue-600/30 hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                    <span>Kirim Kode OTP</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>

                <!-- Back to Login -->
                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Kembali ke Halaman Login</span>
                    </a>
                </div>
            </form>
        </div>

    </div>

</body>
</html>
