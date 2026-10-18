<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Edubank SMKN 1 Bantul</title>

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
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white flex items-center justify-center mx-auto shadow-md shadow-indigo-500/25">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900">Buat Password Baru</h2>
            <p class="text-xs text-slate-500">
                Atur ulang password untuk akun perbankan Edubank Anda.
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                <input type="email" 
                       name="email" 
                       value="{{ $email ?? old('email') }}" 
                       required 
                       readonly 
                       class="w-full bg-slate-100 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-600">
                @error('email')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru (Minimal 6 Karakter)</label>
                <input type="password" 
                       name="password" 
                       required 
                       placeholder="******" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                @error('password')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" 
                       name="password_confirmation" 
                       required 
                       placeholder="******" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold transition-all shadow-md shadow-slate-900/10">
                Perbarui Password Sekarang
            </button>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                &larr; Batalkan & Kembali ke Login
            </a>
        </div>
    </div>

</body>
</html>
