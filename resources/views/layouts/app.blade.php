<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Edubank SMKN 1 Bantul</title>

    <!-- Favicon (Icon Tab Browser) -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-blue.png') }}">

    <!-- Google Fonts: Nunito (Friendly Rounded) & JetBrains Mono (Serious Financial Monospace) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }

        /* Dedicated Monospace for Technical, Financial Records & Account Numbers (Serious & Professional) */
        .font-mono {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
            font-feature-settings: "tnum" 1, "zero" 1;
        }

        /* Tabular figures for strictly aligned accounting figures */
        .tabular-nums, [data-amount] {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum" 1;
        }

        /* Modern Thin Floating Custom Scrollbar (Clean, Sleek & Elegant) */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.45) transparent;
        }
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.35);
            border-radius: 9999px;
            transition: background 0.2s ease-in-out;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.65);
        }

        /* Utility: Hide Scrollbar but keep scrollability */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('styles')
</head>
<body class="h-screen w-screen p-0 flex overflow-hidden bg-[#F8FAFC] relative selection:bg-blue-600 selection:text-white">

    <!-- ========================================================================= -->
    <!-- AMBIENT SOFT & ELEGANT BLUE GRADIENT BACKGROUND (Admin & Teller Pages)     -->
    <!-- ========================================================================= -->
    <div class="fixed inset-0 pointer-events-none select-none overflow-hidden z-0">
        <!-- Soft Ambient Atmospheric Blue Gradient Glows -->
        <div class="absolute -top-40 -left-40 w-[780px] h-[780px] rounded-full bg-gradient-to-br from-blue-500/14 via-sky-400/10 to-transparent blur-[140px]"></div>
        <div class="absolute top-1/4 -right-36 w-[650px] h-[650px] rounded-full bg-gradient-to-bl from-sky-400/12 via-blue-400/8 to-transparent blur-[150px]"></div>
        <div class="absolute -bottom-48 -right-32 w-[850px] h-[850px] rounded-full bg-gradient-to-tl from-blue-600/12 via-indigo-500/8 to-transparent blur-[160px]"></div>
        <div class="absolute -bottom-40 left-1/4 w-[700px] h-[700px] rounded-full bg-gradient-to-tr from-sky-400/10 via-blue-500/8 to-transparent blur-[150px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[900px] h-[600px] rounded-full bg-radial from-blue-200/20 via-sky-100/10 to-transparent blur-[160px]"></div>

        <!-- Subtle Dot Matrix Pattern -->
        <svg class="absolute inset-0 w-full h-full text-slate-400/20 opacity-25" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="appBgDots" x="0" y="0" width="32" height="32" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1" fill="currentColor" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#appBgDots)" />
        </svg>
    </div>

    <!-- Full-bleed Container (Full ke pojokan layar, tanpa card luar) -->
    <div class="w-full h-full flex overflow-hidden p-3 md:p-3.5 gap-3 relative z-10">

        <!-- ========================================================================= -->
        <!-- FLOATING SIDEBAR DOCK (Melayang Ramping & Elegan)                          -->
        <!-- ========================================================================= -->
        <aside class="w-18 md:w-20 bg-white/95 backdrop-blur-md rounded-[26px] border border-slate-200/80 shadow-[0_10px_30px_-5px_rgba(15,23,42,0.06)] flex flex-col items-center py-4 justify-between shrink-0 select-none z-30">
            
            <!-- Logo Icon -->
            <div class="flex flex-col items-center gap-1">
                <a href="{{ route('admin.dashboard') }}" class="w-12 h-12 flex items-center justify-center group transition-transform hover:scale-110 active:scale-95" title="Edubank SMKN 1 Bantul">
                    <img src="{{ asset('images/logo-blue.png') }}" alt="Edubank Logo" class="w-11 h-11 object-contain drop-shadow-xs">
                </a>
                <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">Edubank</span>
            </div>

            <!-- Navigation Menu Icons (Responsive max-height with no-scrollbar) -->
            <nav class="flex flex-col items-center gap-1.5 md:gap-2 w-full px-2 my-auto overflow-y-auto no-scrollbar max-h-[calc(100vh-170px)]">
                @if(Auth::user()->role === 'admin')
                    <!-- 1. Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Dashboard
                        </span>
                    </a>

                    <!-- 2. Members (Master 600 Murid) -->
                    <a href="{{ route('admin.members.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.members.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Members (Siswa)
                        </span>
                    </a>

                    <!-- 3. Savers (Nasabah Tabungan) -->
                    <a href="{{ route('admin.savers.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.savers.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Savers (Nasabah)
                        </span>
                    </a>

                    <!-- 4. Account (Rekening Siswa) -->
                    <a href="{{ route('admin.accounts.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.accounts.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Account (Rekening)
                        </span>
                    </a>

                    <!-- 5. Users (Pengguna Sistem) -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Users (Pengguna)
                        </span>
                    </a>

                    <!-- 6. Daily Report (Penutupan Kas & Brankas) -->
                    <a href="{{ route('admin.daily-closings.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.daily-closings.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Daily Report
                        </span>
                    </a>

                    <!-- 7. Journal (Jurnal Akuntansi) -->
                    <a href="{{ route('admin.accounting-journals.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.accounting-journals.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Journal (Buku Besar)
                        </span>
                    </a>

                    <!-- 8. Laporan Transaksi Bulanan -->
                    <a href="{{ route('admin.transactions.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.transactions.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Laporan Transaksi
                        </span>
                    </a>

                    <!-- 8. Setting (Konfigurasi Sistem) -->
                    <a href="{{ route('admin.settings.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Setting
                        </span>
                    </a>
                @elseif(Auth::user()->role === 'teller')
                    <!-- 1. Dashboard -->
                    <a href="{{ route('teller.dashboard') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.dashboard') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Dashboard
                        </span>
                    </a>

                    <!-- 2. Transaksi (Buku Kas & Layanan Transaksi) -->
                    <a href="{{ route('teller.transactions.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.transactions.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Transaksi
                        </span>
                    </a>

                    <!-- 3. Buka Rekening & Nasabah (Tabungan Siswa) -->
                    <a href="{{ route('teller.savers.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.savers.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Buka Rekening
                        </span>
                    </a>

                    <!-- 4. Daily Report (Rekonsiliasi Kas Harian) -->
                    <a href="{{ route('teller.daily-closings.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.daily-closings.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Daily Report
                        </span>
                    </a>
                @elseif(Auth::user()->role === 'saver')
                    <!-- Saver Dashboard -->
                    <a href="{{ route('saver.dashboard') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('saver.dashboard') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Tabungan Digital
                        </span>
                    </a>

                    <!-- Saver Mutation -->
                    <a href="{{ route('saver.mutation') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('saver.mutation') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Buku Tabungan / Mutasi
                        </span>
                    </a>
                @endif
            </nav>

            <!-- 9. Logout Button (Bottom Pill) -->
            <form method="POST" action="{{ route('logout') }}" class="w-full px-2">
                @csrf
                <button type="submit" 
                        class="relative group w-11 h-11 mx-auto rounded-2xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all duration-200" 
                        title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="absolute left-16 bg-rose-600 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                        Logout
                    </span>
                </button>
            </form>
        </aside>

        <!-- ========================================================================= -->
        <!-- MAIN CANVAS AREA (Topbar Melayang + Bento Grid Konten)                     -->
        <!-- ========================================================================= -->
        <main class="flex-1 flex flex-col min-w-0 h-full gap-3 overflow-hidden">
            
            <!-- FLOATING TOPBAR PILL (Sesuai Referensi Gambar) -->
            <header class="h-14 bg-white/95 backdrop-blur-md rounded-[22px] border border-slate-200/70 shadow-sm px-4 flex items-center justify-between shrink-0">
                
                <!-- Kiri: Breadcrumb / Page Title -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-slate-400">Edubank</span>
                    <span class="text-slate-300">/</span>
                    <h1 class="text-xs font-bold text-slate-800 uppercase tracking-wider">@yield('page_title', 'Dashboard')</h1>
                </div>

                <!-- Kanan: Quick Date & Profile Pill -->
                <div class="flex items-center gap-3">
                    <!-- Real-Time Clock & Date (Jam, Menit, Detik Dunia Nyata) -->
                    <div class="hidden sm:flex items-center gap-2.5 px-3 py-1 bg-slate-50 border border-slate-200/70 rounded-2xl select-none">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                        <div class="text-right leading-tight">
                            <div class="text-xs font-mono font-bold text-slate-800 tracking-wider" id="appLiveClock">
                                {{ now()->format('H:i:s') }} WIB
                            </div>
                            <div class="text-[10px] text-slate-400 font-medium" id="appLiveDate">
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Profile Pill Button -->
                    <div class="flex items-center gap-2.5 bg-slate-100/70 hover:bg-slate-100 border border-slate-200/60 rounded-full pl-1.5 pr-3 py-1 transition-colors cursor-pointer">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-500 to-blue-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-slate-500 leading-tight capitalize">{{ Auth::user()->role ?? 'Admin' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- FLASH ALERTS (Pemberitahuan Sukses / Error yang Lembut) -->
            @if(session('success'))
                <div class="flash-alert bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-3.5 py-2.5 rounded-2xl text-xs flex items-center justify-between shadow-sm animate-fade-in shrink-0 gap-3">
                    <div class="flex items-center gap-2 flex-1">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="leading-relaxed">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.closest('.flash-alert').remove()" aria-label="Tutup notifikasi" class="inline-flex items-center justify-center w-6 h-6 rounded-lg text-emerald-500 hover:text-emerald-800 hover:bg-emerald-100/60 transition-colors shrink-0 my-auto cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-alert bg-rose-50 border border-rose-200/80 text-rose-800 px-3.5 py-2.5 rounded-2xl text-xs flex items-center justify-between shadow-sm animate-fade-in shrink-0 gap-3">
                    <div class="flex items-center gap-2 flex-1">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span class="leading-relaxed">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.closest('.flash-alert').remove()" aria-label="Tutup notifikasi" class="inline-flex items-center justify-center w-6 h-6 rounded-lg text-rose-500 hover:text-rose-800 hover:bg-rose-100/60 transition-colors shrink-0 my-auto cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- CONTENT SLOT (Bento Grid Halaman) -->
            <div class="flex-1 overflow-y-auto pr-1 flex flex-col">
                @yield('content')
            </div>

        </main>
    </div>

    @stack('scripts')

    <!-- Universal Auto-Format Mata Uang Rupiah (Pemisah Titik Ribuan) -->
    <script>
        (function() {
            function formatRupiah(value) {
                if (!value && value !== 0) return '';
                const clean = String(value).replace(/[^0-9]/g, '');
                if (!clean) return '';
                return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function setupCurrencyInputs() {
                const currencyInputs = document.querySelectorAll('input[data-currency="true"]');
                currencyInputs.forEach(input => {
                    if (input.dataset.currencyInitialized) return;
                    input.dataset.currencyInitialized = 'true';

                    // Ensure there's a hidden input for raw integer value if name exists
                    const form = input.form;
                    let hidden = null;
                    if (form && (input.name || input.dataset.targetName)) {
                        const rawName = input.name || input.dataset.targetName;
                        input.dataset.targetName = rawName;
                        // Create or reuse hidden input
                        hidden = form.querySelector(`input[type="hidden"][name="${rawName}"]`);
                        if (!hidden) {
                            hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = rawName;
                            input.insertAdjacentElement('afterend', hidden);
                        }
                        input.removeAttribute('name');
                        hidden.value = String(input.value || '').replace(/[^0-9]/g, '');
                    }

                    // Format initial value
                    if (input.value) {
                        input.value = formatRupiah(input.value);
                    }

                    // On input event
                    input.addEventListener('input', function(e) {
                        const cursor = this.selectionStart;
                        const prevLen = this.value.length;
                        const rawVal = this.value.replace(/[^0-9]/g, '');
                        
                        this.value = formatRupiah(rawVal);
                        if (hidden) {
                            hidden.value = rawVal;
                        }

                        // Dispatch custom or change event if needed
                        const event = new Event('currency-change', { bubbles: true });
                        this.dispatchEvent(event);

                        // Try to keep cursor position relatively natural
                        const newLen = this.value.length;
                        const diff = newLen - prevLen;
                        this.setSelectionRange(cursor + diff, cursor + diff);
                    });

                    // Keep hidden input in sync before form submit
                    if (form) {
                        form.addEventListener('submit', function() {
                            if (hidden) {
                                hidden.value = String(input.value).replace(/[^0-9]/g, '');
                            }
                        });
                    }
                });
            }

            // Expose global helper for dynamic changes (like quick pills)
            window.setCurrencyInputValue = function(inputId, rawNumber) {
                const input = document.getElementById(inputId);
                if (!input) return;
                input.value = formatRupiah(rawNumber);
                const form = input.form;
                if (form) {
                    const targetName = input.dataset.targetName || input.id;
                    const hidden = form.querySelector(`input[type="hidden"][name="${targetName}"]`);
                    if (hidden) {
                        hidden.value = String(rawNumber).replace(/[^0-9]/g, '');
                    }
                }
                input.dispatchEvent(new Event('input', { bubbles: true }));
            };

            document.addEventListener('DOMContentLoaded', setupCurrencyInputs);
            window.setupCurrencyInputs = setupCurrencyInputs;

            // Live Real-Time Clock Ticker (Jam Menit Detik WIB Dunia Nyata)
            function initLiveRealtimeClock() {
                const clockEl = document.getElementById('appLiveClock');
                if (!clockEl) return;

                function tick() {
                    const now = new Date();
                    const h = String(now.getHours()).padStart(2, '0');
                    const m = String(now.getMinutes()).padStart(2, '0');
                    const s = String(now.getSeconds()).padStart(2, '0');
                    clockEl.textContent = `${h}:${m}:${s} WIB`;
                }
                tick();
                setInterval(tick, 1000);
            }
            document.addEventListener('DOMContentLoaded', initLiveRealtimeClock);

            // Auto-Logout Setelah 30 Menit Tidak Ada Interaksi / Meninggalkan Halaman (Teller & Admin)
            function initIdleTimeout() {
                const IDLE_TIMEOUT_MS = 30 * 60 * 1000; // 30 Menit (1.800.000 ms)
                const STORAGE_KEY = 'edubank_last_active_time';
                const LOGOUT_URL = "{{ route('logout') }}?timeout=1";
                let isLoggingOut = false;

                function recordActivity() {
                    if (isLoggingOut) return;
                    const now = Date.now();
                    try {
                        localStorage.setItem(STORAGE_KEY, String(now));
                    } catch (e) {}
                }

                function checkTimeout() {
                    if (isLoggingOut) return;
                    let lastActive = Date.now();
                    try {
                        const stored = localStorage.getItem(STORAGE_KEY);
                        if (stored) {
                            lastActive = parseInt(stored, 10);
                        } else {
                            localStorage.setItem(STORAGE_KEY, String(lastActive));
                        }
                    } catch (e) {}

                    const idleElapsed = Date.now() - lastActive;
                    if (idleElapsed >= IDLE_TIMEOUT_MS) {
                        isLoggingOut = true;
                        try {
                            localStorage.removeItem(STORAGE_KEY);
                        } catch (e) {}
                        window.location.href = LOGOUT_URL;
                    }
                }

                // Inisialisasi waktu saat halaman dibuka
                recordActivity();

                // Deteksi interaksi pengguna (throttled agar performa tetap ringan)
                let throttleTimer = null;
                const events = ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click', 'wheel'];
                events.forEach(eventType => {
                    window.addEventListener(eventType, () => {
                        if (!throttleTimer) {
                            throttleTimer = setTimeout(() => {
                                recordActivity();
                                throttleTimer = null;
                            }, 3000);
                        }
                    }, { passive: true });
                });

                // Cek berkala setiap 5 detik
                setInterval(checkTimeout, 5000);

                // Jika pengguna meninggalkan halaman / tab, cek langsung saat tab aktif kembali
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible') {
                        checkTimeout();
                    }
                });

                // Sinkronisasi antar-tab browser
                window.addEventListener('storage', (e) => {
                    if (e.key === STORAGE_KEY) {
                        // Waktu aktif diperbarui di tab lain
                    }
                });
            }
            document.addEventListener('DOMContentLoaded', initIdleTimeout);
        })();
    </script>
</body>
</html>
