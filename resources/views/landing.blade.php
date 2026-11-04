<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edubank - Bank Mini Digital SMK Negeri 1 Bantul</title>

    <!-- Favicon (Icon Tab Browser) -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-blue.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans (Tema Resmi Web Edubank) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Vite Asset Bundling -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }

        /* Custom Scrollbar Edubank */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAFC] text-slate-900 selection:bg-blue-600 selection:text-white flex flex-col justify-between">

    <!-- ========================================================================= -->
    <!-- 1. NAVIGATION BAR (Clean, Ramping & Responsif di HP)                     -->
    <!-- ========================================================================= -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition-all">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-18">
                
                <!-- Logo & Brand Header -->
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white border border-slate-200/80 p-1 shadow-xs flex items-center justify-center transition-transform group-hover:scale-105">
                        <img src="{{ asset('images/logo-blue.png') }}" alt="Edubank Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors block leading-tight">Edubank</span>
                        <p class="text-[11px] sm:text-xs text-slate-500 font-medium leading-none">SMK Negeri 1 Bantul</p>
                    </div>
                </a>

                <!-- Desktop Menu Navigasi -->
                <nav class="hidden md:flex items-center gap-7 text-xs sm:text-sm font-semibold text-slate-600">
                    <a href="#tentang" class="hover:text-blue-600 transition-colors">Tentang</a>
                    <a href="#fitur" class="hover:text-blue-600 transition-colors">Fitur Layanan</a>
                    <a href="#panduan-apk" class="hover:text-blue-600 transition-colors">Panduan APK</a>
                </nav>

                <!-- Tombol Aksi Header (Responsif dan Tidak Berantakan di HP) -->
                <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                    <!-- Download APK Button -->
                    <a href="{{ asset('edubank-v1.1.0.apk') }}" download="edubank-v1.1.0.apk" 
                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 text-xs font-bold transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh APK <span class="text-[10px] bg-blue-200/80 text-blue-800 px-1.5 py-0.2 rounded-md font-extrabold">v1.1.0</span></span>
                    </a>

                    <!-- Masuk Petugas Button -->
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-xs transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Masuk<span class="hidden sm:inline"> Petugas</span></span>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- 2. HERO SECTION (Jarak Lebih Ramping ke Navbar, Bersih & Informatif)     -->
    <!-- ========================================================================= -->
    <section class="relative pt-3 sm:pt-4 pb-8 sm:pb-12 md:pb-14 overflow-hidden">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <!-- Hero Container: Gradient Slate-900 to Indigo-950 (Edubank Signature Theme) -->
            <div class="relative w-full rounded-2xl sm:rounded-3xl md:rounded-[32px] bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-950 text-white p-5 sm:p-8 md:p-12 shadow-[0_15px_40px_-15px_rgba(15,23,42,0.12)] border border-slate-800/80 overflow-hidden">
                <!-- Ambient Glow Subtle -->
                <div class="absolute -top-24 -left-24 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-10 items-center">
                    
                    <!-- Left Hero Content -->
                    <div class="lg:col-span-7 space-y-4 sm:space-y-5">
                        
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-snug sm:leading-tight text-white">
                            Digitalisasi Tabungan Siswa <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-sky-200 to-indigo-200">
                                SMK Negeri 1 Bantul
                            </span>
                        </h1>

                        <p class="text-xs sm:text-sm md:text-base text-slate-300/95 leading-relaxed font-normal max-w-2xl">
                            Platform perbankan mini sekolah modern yang menghubungkan operasional teller, manajemen kas sekolah, dan aplikasi nasabah siswa secara aman, transparan, dan real-time.
                        </p>

                        <!-- CTA Action Buttons (Full Width di HP Kecil, Fleksibel di Layar Lebar) -->
                        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3.5">
                            <!-- Button 1: Masuk sebagai Petugas -->
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center justify-center gap-2.5 px-5 sm:px-6 py-3 rounded-xl sm:rounded-2xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-blue-600/30 transition-all hover:scale-[1.01] active:scale-[0.99]">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span>Masuk sebagai Petugas</span>
                            </a>

                            <!-- Button 2: Download APK Android -->
                            <a href="{{ asset('edubank-v1.1.0.apk') }}" download="edubank-v1.1.0.apk" 
                               class="inline-flex items-center justify-center gap-2.5 px-5 sm:px-6 py-3 rounded-xl sm:rounded-2xl bg-white/10 hover:bg-white/15 text-white border border-white/20 font-bold text-xs sm:text-sm backdrop-blur-md transition-all hover:scale-[1.01] active:scale-[0.99]">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-1.0001s.4482-.9993.9993-.9993c.551 0 .9993.4478.9993.9993s-.4483 1.0001-.9993 1.0001m-11.046 0c-.5511 0-.9993-.4486-.9993-1.0001s.4482-.9993.9993-.9993c.5511 0 .9993.4478.9993.9993s-.4482 1.0001-.9993 1.0001m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1523-.5676.416.416 0 00-.5677.1523l-2.0223 3.503C15.5902 8.4116 13.8533 8.125 12 8.125s-3.5902.2866-5.1364.825L4.8413 5.447a.4163.4163 0 00-.5677-.1523.4158.4158 0 00-.1523.5676l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.7778h24c-.3432-4.1189-2.6889-7.5911-6.1185-9.4564"/>
                                </svg>
                                <span>Download APK Android (v1.1.0)</span>
                            </a>
                        </div>

                    </div>

                    <!-- Right Hero: Mockup Kartu Edubank Digital Siswa (Tanpa Hiasan Berlebihan) -->
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="w-full max-w-sm">
                            
                            <!-- Virtual Debit Card Edubank (Signature Look) -->
                            <div class="w-full rounded-2xl sm:rounded-[24px] bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-600 p-5 sm:p-6 text-white shadow-xl shadow-blue-900/40 border border-blue-400/30 relative overflow-hidden">
                                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                                <div class="flex justify-between items-start mb-5 sm:mb-6">
                                    <div>
                                        <p class="text-[10px] sm:text-[11px] font-semibold tracking-wider text-blue-100 uppercase">Kartu Digital Nasabah</p>
                                        <h3 class="text-base sm:text-lg font-extrabold tracking-tight">Edubank Siswa</h3>
                                    </div>
                                    <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="w-7 h-7 sm:w-8 sm:h-8 object-contain">
                                </div>
                                <div class="space-y-1 mb-5 sm:mb-6">
                                    <p class="text-[9px] sm:text-[10px] text-blue-200 uppercase font-semibold">Nomor Rekening</p>
                                    <p class="text-sm sm:text-base font-mono tracking-widest font-bold">5401 •••• •••• 8821</p>
                                </div>
                                <div class="flex justify-between items-end pt-2 border-t border-white/15 text-xs">
                                    <div>
                                        <p class="text-[9px] text-blue-200 uppercase">Nama Siswa</p>
                                        <p class="font-bold text-xs sm:text-sm truncate max-w-[170px] sm:max-w-none">Ahmad Fauzan Syahputra</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] text-blue-200 uppercase">Jurusan</p>
                                        <p class="font-bold text-xs sm:text-sm">XII RPL</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. TENTANG EDUBANK (Penjelasan Ekosistem & Literasi Keuangan)             -->
    <!-- ========================================================================= -->
    <section id="tentang" class="py-10 sm:py-14 md:py-18 bg-white border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-10 sm:mb-14">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/60 uppercase tracking-wider">
                    Tentang Edubank
                </span>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Membangun Budaya Menabung & Literasi Finansial Sekolah
                </h2>
                <p class="text-xs sm:text-sm md:text-base text-slate-600 leading-relaxed">
                    Edubank dirancang khusus untuk memenuhi kebutuhan operasional Bank Mini SMK Negeri 1 Bantul. Melalui integrasi teknologi web dan aplikasi mobile, proses menabung menjadi lebih aman, transparan, dan mudah dipantau oleh seluruh civitas sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 md:gap-8">
                
                <!-- Pilar 1 -->
                <div class="bg-[#F8FAFC] rounded-2xl sm:rounded-[24px] p-6 sm:p-7 border border-slate-200/80 hover:border-blue-300 transition-all hover:shadow-xs group">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-5 group-hover:scale-105 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Edukasi & Literasi Finansial</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Mendidik siswa untuk membiasakan diri mengelola uang saku, merencanakan tabungan masa depan, dan memahami alur perbankan sejak bangku sekolah.
                    </p>
                </div>

                <!-- Pilar 2 -->
                <div class="bg-[#F8FAFC] rounded-2xl sm:rounded-[24px] p-6 sm:p-7 border border-slate-200/80 hover:border-blue-300 transition-all hover:shadow-xs group">
                    <div class="w-11 h-11 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-5 group-hover:scale-105 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Transparansi & Akuntabilitas</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Semua transaksi penarikan dan setoran tercatat digital dalam buku kas otomatis. Dilengkapi fitur daily closing kas fisik vs sistem tanpa selisih.
                    </p>
                </div>

                <!-- Pilar 3 -->
                <div class="bg-[#F8FAFC] rounded-2xl sm:rounded-[24px] p-6 sm:p-7 border border-slate-200/80 hover:border-blue-300 transition-all hover:shadow-xs group">
                    <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-5 group-hover:scale-105 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-2">Akses Mudah Siswa</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Siswa tidak perlu antre hanya untuk memeriksa saldo atau mutasi tabungan. Semua informasi dapat diakses kapan saja langsung lewat aplikasi Android.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. FITUR-FITUR LAYANAN (Web Petugas & Mobile Siswa)                      -->
    <!-- ========================================================================= -->
    <section id="fitur" class="py-10 sm:py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-10 sm:mb-14">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/60 uppercase tracking-wider">
                    Fitur Sistem Terpadu
                </span>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Lengkap untuk Siswa, Teller, dan Administrator
                </h2>
                <p class="text-xs sm:text-sm md:text-base text-slate-600 leading-relaxed">
                    Setiap peran dalam sistem Edubank dirancang memiliki modul kerja yang optimal sesuai standar operasional perbankan mini sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                
                <!-- CARD FITUR 1: APLIKASI MOBILE SISWA -->
                <div class="bg-white rounded-2xl sm:rounded-[28px] p-6 sm:p-7 border border-slate-200/80 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.03)] flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold mb-4">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Aplikasi Siswa (Android)
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2">Mobile Banking Siswa</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-5">
                            Dirancang khusus agar siswa dapat memantau tabungannya langsung dari smartphone kapan saja.
                        </p>
                        
                        <ul class="space-y-3 text-xs text-slate-700 font-medium">
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Cek Saldo Real-Time</strong>: Saldo terupdate otomatis sesaat setelah transaksi setor atau tarik.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Statistik Bulanan</strong>: Rekap total setoran dan penarikan bulan ini secara visual.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Riwayat Mutasi Lengkap</strong>: Histori transaksi lengkap dengan tanggal dan nomor referensi.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Kartu Virtual Siswa</strong>: Kartu digital identitas nasabah lengkap dengan info jurusan dan NIS.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-5 mt-5 border-t border-slate-100">
                        <a href="{{ asset('edubank-v1.1.0.apk') }}" download="edubank-v1.1.0.apk" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition-colors">
                            <span>Unduh File APK Android (v1.1.0) &rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- CARD FITUR 2: WEB TELLER -->
                <div class="bg-white rounded-2xl sm:rounded-[28px] p-6 sm:p-7 border border-slate-200/80 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.03)] flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold mb-4">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Layanan Teller (Web)
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2">Operasional Teller Kas</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-5">
                            Antarmuka cepat dan responsif bagi petugas teller sekolah untuk melayani transaksi nasabah setiap hari.
                        </p>
                        
                        <ul class="space-y-3 text-xs text-slate-700 font-medium">
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Setor & Tarik Tunai Instan</strong>: Pencarian nasabah via nomor rekening, NIS, atau nama siswa.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Buka Rekening Siswa Baru</strong>: Pendaftaran rekening baru dengan validasi data siswa otomatis.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Cetak Struk Transaksi</strong>: Pencetakan slip transaksi resmi untuk bukti fisik nasabah.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Penutupan Kas Harian</strong>: Rekapitulasi fisik uang pecahan vs saldo sistem sebelum tutup operasional.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-5 mt-5 border-t border-slate-100">
                        <a href="{{ route('login') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition-colors">
                            <span>Akses Portal Petugas &rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- CARD FITUR 3: WEB ADMINISTRATOR -->
                <div class="bg-white rounded-2xl sm:rounded-[28px] p-6 sm:p-7 border border-slate-200/80 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.03)] flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold mb-4">
                            <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                            Manajemen Admin (Web)
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-2">Monitoring & Akuntansi</h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-5">
                            Pusat kendali laporan keuangan sekolah dan pengawasan buku kas perbankan secara menyeluruh.
                        </p>
                        
                        <ul class="space-y-3 text-xs text-slate-700 font-medium">
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Jurnal Akuntansi Otomatis</strong>: Setiap mutasi terposting otomatis ke jurnal debet/kredit.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Verifikasi Serah Terima Brankas</strong>: Persetujuan (*approval*) laporan penutupan kas dari teller harian.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Database Siswa & Nasabah</strong>: Manajemen data master siswa per jurusan dan status rekening aktif.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <div class="w-4 h-4 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span><strong>Laporan Keuangan Eksekutif</strong>: Ekspor data kas, total likuiditas, dan histori transaksi berkala.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-5 mt-5 border-t border-slate-100">
                        <a href="{{ route('login') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition-colors">
                            <span>Akses Dashboard Admin &rarr;</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. PANDUAN MENGUNDUH & MEMASANG APK ANDROID                              -->
    <!-- ========================================================================= -->
    <section id="panduan-apk" class="py-10 sm:py-16 md:py-20 bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            
            <div class="max-w-3xl mx-auto text-center space-y-3 mb-10 sm:mb-14">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase tracking-wider">
                    Panduan Instalasi
                </span>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Cara Mudah Memasang Aplikasi Edubank di Android
                </h2>
                <p class="text-xs sm:text-sm md:text-base text-slate-600 leading-relaxed">
                    Aplikasi mobile Edubank didistribusikan dalam format APK resmi untuk siswa SMK Negeri 1 Bantul. Ikuti 4 langkah mudah berikut untuk mulai menggunakannya.
                </p>
            </div>

            <!-- Steps Grid (4 Langkah Terstruktur & Rapi di HP) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-10 sm:mb-12">
                
                <!-- Langkah 1 -->
                <div class="bg-[#F8FAFC] rounded-2xl sm:rounded-[24px] p-5 sm:p-6 border border-slate-200/80 flex flex-col justify-between">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center text-xs mb-3.5 shadow-xs">
                            01
                        </div>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Unduh Berkas APK</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Klik tombol <strong>"Download APK Android"</strong> di website ini. Berkas <code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded font-mono text-[11px]">edubank-v1.1.0.apk</code> (ukuran ±48.2 MB, Versi 1.1.0 Rilis Terbaru) akan langsung diunduh ke ponsel Anda.
                        </p>
                    </div>
                    <div class="pt-3 mt-3 border-t border-slate-200/60 text-[11px] text-slate-400 font-medium">
                        Ukuran: ~48.2 MB (v1.1.0)
                    </div>
                </div>

                <!-- Langkah 2 -->
                <div class="bg-[#F8FAFC] rounded-2xl sm:rounded-[24px] p-5 sm:p-6 border border-slate-200/80 flex flex-col justify-between">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center text-xs mb-3.5 shadow-xs">
                            02
                        </div>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Izinkan Sumber Tidak Dikenal</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Buka file hasil unduhan. Jika muncul konfirmasi <em>"Install unknown apps"</em> atau <em>"Sumber tidak dikenal"</em>, aktifkan izin instalasi untuk browser yang Anda gunakan (Chrome/Browser HP).
                        </p>
                    </div>
                    <div class="pt-3 mt-3 border-t border-slate-200/60 text-[11px] text-emerald-600 font-semibold">
                        Aman & Terverifikasi
                    </div>
                </div>

                <!-- Langkah 3 -->
                <div class="bg-[#F8FAFC] rounded-2xl sm:rounded-[24px] p-5 sm:p-6 border border-slate-200/80 flex flex-col justify-between">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center text-xs mb-3.5 shadow-xs">
                            03
                        </div>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Pasang (Install) Aplikasi</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Tekan tombol <strong>"Install"</strong> dan tunggu beberapa detik sampai proses pemasangan selesai. Tidak memerlukan akses root dan kompatibel mulai Android 8.0+.
                        </p>
                    </div>
                    <div class="pt-3 mt-3 border-t border-slate-200/60 text-[11px] text-slate-400 font-medium">
                        Waktu: ±10 Detik
                    </div>
                </div>

                <!-- Langkah 4 -->
                <div class="bg-[#F8FAFC] rounded-2xl sm:rounded-[24px] p-5 sm:p-6 border border-slate-200/80 flex flex-col justify-between">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-extrabold flex items-center justify-center text-xs mb-3.5 shadow-xs">
                            04
                        </div>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1.5">Buka & Masuk dengan NIS</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Buka aplikasi Edubank yang telah terpasang. Masukkan <strong>Nomor Induk Siswa (NIS)</strong> dan password yang telah didaftarkan oleh petugas Bank Mini sekolah.
                        </p>
                    </div>
                    <div class="pt-3 mt-3 border-t border-slate-200/60 text-[11px] text-blue-600 font-semibold">
                        Koneksi Otomatis Online
                    </div>
                </div>

            </div>

            <!-- Callout Card Download APK Besar (Responsif Penuh di Mobile) -->
            <div class="rounded-2xl sm:rounded-[28px] bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 p-6 sm:p-8 md:p-10 text-white shadow-md shadow-blue-500/20 flex flex-col md:flex-row items-center justify-between gap-5 sm:gap-6">
                <div class="space-y-1.5 text-center md:text-left">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-extrabold tracking-tight">Siap Menggunakan Edubank Mobile?</h3>
                    <p class="text-xs sm:text-sm text-blue-100 max-w-xl">
                        Dapatkan kemudahan mengecek saldo tabungan, mutasi setoran bulanan, dan kartu nasabah digital langsung di smartphone Anda.
                    </p>
                </div>

                <div class="w-full sm:w-auto shrink-0 flex justify-center">
                    <a href="{{ asset('edubank-v1.1.0.apk') }}" download="edubank-v1.1.0.apk" 
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl sm:rounded-2xl bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs sm:text-sm shadow-md transition-all hover:scale-[1.01] active:scale-[0.99]">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-1.0001s.4482-.9993.9993-.9993c.551 0 .9993.4478.9993.9993s-.4483 1.0001-.9993 1.0001m-11.046 0c-.5511 0-.9993-.4486-.9993-1.0001s.4482-.9993.9993-.9993c.5511 0 .9993.4478.9993.9993s-.4482 1.0001-.9993 1.0001m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1523-.5676.416.416 0 00-.5677.1523l-2.0223 3.503C15.5902 8.4116 13.8533 8.125 12 8.125s-3.5902.2866-5.1364.825L4.8413 5.447a.4163.4163 0 00-.5677-.1523.4158.4158 0 00-.1523.5676l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.7778h24c-.3432-4.1189-2.6889-7.5911-6.1185-9.4564"/>
                        </svg>
                        <span>Download APK Android (v1.1.0)</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. FOOTER (Sama Persis dengan Identitas Edubank & Ramah Mobile)           -->
    <!-- ========================================================================= -->
    <footer class="bg-white border-t border-slate-200/80 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-5 text-center md:text-left">
                
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-blue.png') }}" alt="Edubank Logo" class="w-8 h-8 object-contain">
                    <div>
                        <p class="text-sm font-bold text-slate-900 leading-tight">Edubank - Bank Mini Digital</p>
                        <p class="text-xs text-slate-500">SMK Negeri 1 Bantul &bull; Literasi Keuangan Sekolah</p>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6 text-xs text-slate-500 font-medium">
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition-colors">Portal Login Petugas</a>
                    <a href="{{ asset('edubank-v1.1.0.apk') }}" download="edubank-v1.1.0.apk" class="hover:text-blue-600 transition-colors">Unduh APK (v1.1.0)</a>
                    <a href="#tentang" class="hover:text-blue-600 transition-colors">Tentang Sistem</a>
                </div>

                <div class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} Edubank SMKN 1 Bantul.
                </div>

            </div>
        </div>
    </footer>

</body>
</html>
