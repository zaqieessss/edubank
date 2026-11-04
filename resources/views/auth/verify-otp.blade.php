<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Edubank SMKN 1 Bantul</title>

    <!-- Favicon (Icon Tab Browser) -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-blue.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #E8EEF5;
            color: #0F172A;
        }
    </style>
</head>
<body class="min-h-screen p-3 md:p-6 flex items-center justify-center">

    <!-- Container Form (Identik dengan Desain Form Login) -->
    <div class="w-full max-w-4xl bg-white/95 backdrop-blur-md rounded-[32px] border border-white/80 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.1)] overflow-hidden grid grid-cols-1 md:grid-cols-12 relative">
        
        <!-- LEFT HERO (5 Cols): Branding & Atmosphere -->
        <div class="md:col-span-5 bg-gradient-to-br from-blue-600 via-indigo-600 to-slate-900 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-blue-400/30 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center mb-6 shadow-inner">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold mt-3 tracking-tight leading-snug">Edubank</h1>
                <p class="text-xs text-blue-100/80 mt-1 leading-relaxed">
                    Bank Mini SMKN 1 Bantul
                </p>
            </div>

            <!-- Steps Mini Checklist -->
            <div class="relative z-10 space-y-2.5 pt-6 border-t border-white/15 text-[11px] text-blue-100/90 font-medium">
                <div class="flex items-center gap-2 text-white/60">
                    <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="line-through">Masukkan alamat email terdaftar</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-white/20 text-white flex items-center justify-center text-[10px] font-bold">2</span>
                    <span class="font-bold text-white">Verifikasi 6 digit kode OTP email</span>
                </div>
                <div class="flex items-center gap-2 text-white/50">
                    <span class="w-5 h-5 rounded-full bg-white/10 text-white/50 flex items-center justify-center text-[10px] font-bold">3</span>
                    <span>Buat kata sandi baru yang aman</span>
                </div>
            </div>

            <div class="relative z-10 pt-4 text-[10px] text-blue-200/60 font-medium">
                &copy; {{ date('Y') }} SMKN 1 Bantul
            </div>
        </div>

        <!-- RIGHT FORM (7 Cols): Form Verifikasi OTP -->
        <div class="md:col-span-7 p-7 md:p-10 flex flex-col justify-center bg-white">
            <div class="mb-5">
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Masukkan Kode OTP</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Kode 6 digit telah dikirim ke <strong class="text-slate-800 font-semibold">{{ $email }}</strong>.
                </p>
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

            <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- OTP Input Field -->
                <div>
                    <label for="otp" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5 text-center">
                        6 Digit Kode OTP
                    </label>
                    <div class="relative max-w-xs mx-auto">
                        <input type="text" 
                               id="otp" 
                               name="otp" 
                               maxlength="6" 
                               pattern="[0-9]{6}" 
                               inputmode="numeric" 
                               required 
                               autofocus 
                               autocomplete="one-time-code"
                               placeholder="000000"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-center font-mono text-2xl font-extrabold text-blue-700 tracking-[0.45em] placeholder-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-inner">
                    </div>
                    @error('otp')
                        <p class="text-[11px] text-rose-600 font-semibold mt-1.5 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs py-3 rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                    <span>Verifikasi Kode OTP</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Resend OTP & Navigation -->
            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <!-- Resend Form -->
                <form method="POST" action="{{ route('password.otp.resend') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <span>Belum terima kode?</span>
                    <button type="submit" class="font-bold text-blue-600 hover:text-blue-700 hover:underline cursor-pointer ml-1">
                        Kirim Ulang OTP
                    </button>
                </form>

                <a href="{{ route('password.request') }}" class="text-slate-400 hover:text-slate-700 transition-colors">
                    Ganti Email
                </a>
            </div>
        </div>

    </div>

</body>
</html>
