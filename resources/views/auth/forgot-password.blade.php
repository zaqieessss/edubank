<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Edubank SMKN 1 Bantul</title>

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

    <!-- Floating Container -->
    <div class="w-full max-w-md bg-white/95 backdrop-blur-md rounded-[32px] border border-white/80 shadow-[0_25px_60px_-15px_rgba(15,23,42,0.1)] p-8 relative space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center mx-auto shadow-md shadow-blue-500/25">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900">Pemulihan Password</h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                Masukkan alamat email yang terdaftar pada akun Anda. Sistem akan membuatkan tautan pengaturan ulang kata sandi.
            </p>
        </div>

        @if(session('status'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs space-y-1">
                <span class="font-bold block">Tautan Reset Password:</span>
                <p class="text-[11px] break-all">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email Terdaftar</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       placeholder="nama@siswa.edubank.test" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                @error('email')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all shadow-md shadow-slate-900/10">
                Kirim Tautan Reset Password
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Halaman Login</span>
            </a>
        </div>
    </div>

</body>
</html>
