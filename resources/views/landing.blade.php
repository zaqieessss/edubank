<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edubank - Bank Mini Digital SMK Negeri 1 Bantul</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-blue.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0B1120;
            color: #F8FAFC;
            -webkit-font-smoothing: antialiased;
        }
        .glow-mesh {
            background-image: 
                radial-gradient(at 10% 20%, rgba(37, 99, 235, 0.22) 0px, transparent 50%),
                radial-gradient(at 90% 10%, rgba(79, 70, 229, 0.22) 0px, transparent 50%),
                radial-gradient(at 50% 90%, rgba(14, 165, 233, 0.15) 0px, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-nav {
            background: rgba(11, 17, 32, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-[#0B1120] text-slate-100 overflow-x-hidden selection:bg-blue-600 selection:text-white">

    <!-- Top Sticky Glass Navbar -->
    <header class="sticky top-0 z-50 glass-nav transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo & School Identity -->
            <a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-500 p-2 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-xl font-black tracking-tight text-white block leading-none">EDUBANK</span>
                    <span class="text-[11px] font-semibold text-blue-300 tracking-wider uppercase block mt-1">SMK Negeri 1 Bantul</span>
                </div>
            </a>

            <!-- Navigation Links & Action Buttons -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="#fitur" class="hidden md:inline-flex text-xs font-semibold text-slate-300 hover:text-white px-3 py-2 transition-colors">
                    Fitur Unggulan
                </a>
                <a href="#keamanan" class="hidden md:inline-flex text-xs font-semibold text-slate-300 hover:text-white px-3 py-2 transition-colors">
                    Keamanan
                </a>

                <!-- 1. Tombol Download APK (Versi Mobile Navbar) -->
                <a href="{{ asset('edubank.apk') }}" download="edubank.apk" class="inline-flex items-center gap-2 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 text-xs font-bold text-slate-200 hover:text-white transition-all shadow-sm">
                    <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-1.0003s.4482-1.0002.9993-1.0002c.5516 0 1 .4485 1 1.0002s-.4484 1.0003-1 1.0003m-11.046 0c-.5511 0-.9993-.4486-.9993-1.0003s.4482-1.0002.9993-1.0002c.5516 0 1 .4485 1 1.0002s-.4484 1.0003-1 1.0003m11.4045-6.02l1.9964-3.4578c.0754-.1308.0305-.2976-.1002-.373-.1308-.0755-.2976-.0305-.373.1002L17.375 9.1026C15.8234 8.3974 14.001 8 12 8s-3.8234.3974-5.375 1.1026L4.595 5.5908c-.0754-.1307-.2422-.1757-.373-.1002-.1307.0754-.1756.2422-.1002.373l1.9964 3.4578C2.6568 11.2384.444 14.7766 0 19h24c-.444-4.2234-2.6568-7.7616-6.1185-9.6786"/>
                    </svg>
                    <span>Download APK</span>
                </a>

                <!-- 2. Tombol Masuk Petugas -->
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 sm:px-5 sm:py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-xs font-bold text-white transition-all shadow-lg shadow-blue-600/30 active:scale-95">
                    <span>Masuk sebagai Petugas</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 relative glow-mesh">
        <!-- Floating Ambient Blurs -->
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-24 lg:pt-24 lg:pb-32 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Column: Copywriting & Primary CTAs -->
                <div class="lg:col-span-7 text-center lg:text-left">
                    <!-- School Verification Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full glass-card text-blue-300 text-xs font-bold mb-6 border border-blue-500/25">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Sistem Perbankan Digital Resmi • SMK Negeri 1 Bantul</span>
                    </div>

                    <!-- Main Hero Headline -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.12]">
                        Solusi Cerdas Tabungan & Transaksi Digital Sekolah
                    </h1>

                    <!-- Subtitle -->
                    <p class="mt-6 text-base sm:text-lg text-slate-300/90 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-normal">
                        Edubank menghadirkan tata kelola bank mini sekolah yang transparan, aman, dan modern. Mendukung literasi finansial siswa serta mempermudah petugas loket dalam pencatatan kas sekolah secara real-time.
                    </p>

                    <!-- TWO PRIMARY ACTION BUTTONS (Diminta User) -->
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <!-- 1. Tombol Masuk sebagai Petugas -->
                        <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-7 py-4 rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-sm shadow-xl shadow-blue-600/30 hover:shadow-blue-600/50 hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Masuk sebagai Petugas</span>
                            <span class="text-blue-200 text-xs font-normal">→</span>
                        </a>

                        <!-- 2. Tombol Download APK Android -->
                        <a href="{{ asset('edubank.apk') }}" download="edubank.apk" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-7 py-4 rounded-2xl bg-slate-800/90 hover:bg-slate-700/90 border border-slate-700/80 text-white font-extrabold text-sm shadow-lg shadow-black/20 hover:-translate-y-0.5 transition-all group">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-1.0003s.4482-1.0002.9993-1.0002c.5516 0 1 .4485 1 1.0002s-.4484 1.0003-1 1.0003m-11.046 0c-.5511 0-.9993-.4486-.9993-1.0003s.4482-1.0002.9993-1.0002c.5516 0 1 .4485 1 1.0002s-.4484 1.0003-1 1.0003m11.4045-6.02l1.9964-3.4578c.0754-.1308.0305-.2976-.1002-.373-.1308-.0755-.2976-.0305-.373.1002L17.375 9.1026C15.8234 8.3974 14.001 8 12 8s-3.8234.3974-5.375 1.1026L4.595 5.5908c-.0754-.1307-.2422-.1757-.373-.1002-.1307.0754-.1756.2422-.1002.373l1.9964 3.4578C2.6568 11.2384.444 14.7766 0 19h24c-.444-4.2234-2.6568-7.7616-6.1185-9.6786"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <span class="block leading-tight">Download APK Android</span>
                                <span class="text-[10px] text-slate-400 font-mono font-medium block">Versi Rilis Resmi • 48 MB</span>
                            </div>
                        </a>
                    </div>

                    <!-- Trust Stats Bar -->
                    <div class="mt-12 pt-8 border-t border-slate-800/80 grid grid-cols-3 gap-6 max-w-lg mx-auto lg:mx-0 text-left">
                        <div>
                            <span class="block text-2xl font-black text-white">100%</span>
                            <span class="block text-xs font-semibold text-slate-400 mt-0.5">Online Real-time</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-emerald-400">256-bit</span>
                            <span class="block text-xs font-semibold text-slate-400 mt-0.5">Enkripsi SSL</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-black text-blue-400">Multi</span>
                            <span class="block text-xs font-semibold text-slate-400 mt-0.5">Platform Web & App</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive App & Card Visual Showcase -->
                <div class="lg:col-span-5 relative">
                    <!-- Ambient Glow behind card -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-600/30 to-indigo-600/20 rounded-3xl blur-2xl transform rotate-3"></div>

                    <!-- Virtual Digital Card Mockup (Sleek & Realistic) -->
                    <div class="relative glass-card rounded-3xl p-6 sm:p-7 shadow-2xl border border-white/10 space-y-6">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center p-1.5 shadow-md shadow-blue-500/30">
                                    <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-white tracking-wider">EDUBANK DIGITAL CARD</h4>
                                    <p class="text-[9px] text-blue-300 font-medium">SMK NEGERI 1 BANTUL</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">
                                REKENING AKTIF
                            </span>
                        </div>

                        <!-- Card Chip & Hologram -->
                        <div class="flex items-center justify-between pt-2">
                            <div class="w-10 h-7 rounded-md bg-gradient-to-br from-amber-200 via-yellow-400 to-amber-500 shadow-inner flex items-center justify-center">
                                <div class="w-7 h-5 border border-amber-700/30 rounded-xs"></div>
                            </div>
                            <span class="text-xs font-mono font-bold tracking-widest text-slate-400">PASSTHROUGH NFC</span>
                        </div>

                        <!-- Account Number & Balance Preview -->
                        <div class="pt-2">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Nomor Rekening Siswa</p>
                            <p class="text-xl sm:text-2xl font-mono font-extrabold tracking-widest text-white mt-1">RPL-2026-0001</p>
                        </div>

                        <!-- Student Details & Status -->
                        <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs">
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase font-semibold">Nama Nasabah</p>
                                <p class="font-bold text-white mt-0.5">Ahmad Fajar Prasetyo</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] text-slate-400 uppercase font-semibold">Kelas / Jurusan</p>
                                <p class="font-bold text-blue-300 mt-0.5">X RPL 1 (PPLG)</p>
                            </div>
                        </div>

                        <!-- Mini Live Feature Floating Badges -->
                        <div class="pt-2 grid grid-cols-2 gap-3">
                            <div class="p-3 rounded-xl bg-slate-900/60 border border-white/5 flex items-center gap-2.5">
                                <div class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">✓</div>
                                <div class="text-[11px]">
                                    <p class="font-bold text-slate-200">Cek Saldo HP</p>
                                    <p class="text-[9px] text-slate-400">Real-time sinkron</p>
                                </div>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-900/60 border border-white/5 flex items-center gap-2.5">
                                <div class="w-6 h-6 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs">QR</div>
                                <div class="text-[11px]">
                                    <p class="font-bold text-slate-200">Scan QR Loket</p>
                                    <p class="text-[9px] text-slate-400">Setor/tarik instan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Features Showcase Section -->
        <section id="fitur" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-slate-800/80">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-xs uppercase font-extrabold tracking-widest text-blue-400">Fitur Ekosistem Lengkap</h2>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Didesain Khusus untuk Perbankan Sekolah Modern</h3>
                <p class="text-slate-400 mt-3 text-sm">Menghubungkan siswa, loket teller bank mini, dan pengelola sekolah dalam satu platform terpadu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Fitur 1 -->
                <div class="glass-card rounded-2xl p-6 hover:border-blue-500/40 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-base font-bold text-white">Setor & Tarik Cepat</h4>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Proses setoran dan penarikan di loket teller dengan bukti cetak kwitansi resmi dan validasi saldo mengendap.</p>
                </div>

                <!-- Fitur 2 -->
                <div class="glass-card rounded-2xl p-6 hover:border-emerald-500/40 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h4 class="text-base font-bold text-white">Aplikasi Siswa Android</h4>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Siswa dapat memantau saldo, memeriksa riwayat mutasi bulanan, dan menunjukkan kartu digital QR kapan saja.</p>
                </div>

                <!-- Fitur 3 -->
                <div class="glass-card rounded-2xl p-6 hover:border-indigo-500/40 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h4 class="text-base font-bold text-white">Otorisasi PIN Aman</h4>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Penarikan tabungan aman dengan verifikasi PIN 6 digit atau persetujuan langsung lewat push notifikasi HP siswa.</p>
                </div>

                <!-- Fitur 4 -->
                <div class="glass-card rounded-2xl p-6 hover:border-sky-500/40 transition-all hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-sky-600/20 text-sky-400 flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h4 class="text-base font-bold text-white">Jurnal Akuntansi Otomatis</h4>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Setiap transaksi otomatis mencatat jurnal debit-kredit dan tutup kas harian teller yang terverifikasi supervisor.</p>
                </div>
            </div>
        </section>

        <!-- Download Banner Callout Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="rounded-3xl bg-gradient-to-r from-blue-900/60 via-indigo-900/50 to-slate-900/80 border border-blue-500/20 p-8 sm:p-12 text-center relative overflow-hidden">
                <div class="relative z-10 max-w-2xl mx-auto">
                    <span class="px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-bold border border-blue-400/30">
                        Unduh Aplikasi Sekarang
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-4">
                        Akses Tabungan Sekolah di Genggaman Anda
                    </h3>
                    <p class="text-slate-300 text-xs sm:text-sm mt-3">
                        Dapatkan aplikasi Android resmi Edubank untuk siswa SMK Negeri 1 Bantul dan nikmati kemudahan transaksi digital.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ asset('edubank.apk') }}" download="edubank.apk" class="inline-flex items-center gap-3 px-8 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-sm shadow-xl shadow-emerald-500/25 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh File APK Android (48 MB)</span>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl glass-card text-white hover:bg-white/10 font-bold text-sm transition-all">
                            <span>Portal Login Web</span>
                            <span class="text-slate-400 text-xs">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-[#070C18] py-12 text-xs text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="w-7 h-7 object-contain opacity-80">
                <div>
                    <span class="font-bold text-slate-200">EDUBANK</span> • SMK Negeri 1 Bantul
                    <p class="text-[10px] text-slate-500 mt-0.5">Jl. Sabdodadi, Bantul, D.I. Yogyakarta</p>
                </div>
            </div>

            <div class="flex items-center gap-6 text-[11px]">
                <a href="{{ route('login') }}" class="hover:text-white transition-colors">Login Petugas</a>
                <a href="{{ asset('edubank.apk') }}" download="edubank.apk" class="hover:text-white transition-colors">Download APK</a>
                <span class="flex items-center gap-1.5 text-emerald-400 font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    SSL Encrypted
                </span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 pt-6 border-t border-slate-800/40 text-center text-[10px] text-slate-500">
            &copy; {{ date('Y') }} Edubank SMK Negeri 1 Bantul. Hak Cipta Dilindungi Undang-Undang. Proyek Perbankan Mini Sekolah Digital.
        </div>
    </footer>

</body>
</html>
