<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Edubank SMKN 1 Bantul</title>

    <!-- Google Fonts: Plus Jakarta Sans (Crisp, Modern, Eye-Friendly) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #E8EEF5;
            color: #0F172A;
            -webkit-font-smoothing: antialiased;
        }

        /* Custom Scrollbar for Dense Tables */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, 0.6);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen p-2.5 md:p-5 flex items-center justify-center">

    <!-- Floating Master Application Container -->
    <div class="w-full max-w-[1600px] h-[94vh] bg-[#F8FAFC] rounded-[32px] border border-white/80 shadow-[0_20px_50px_-15px_rgba(15,23,42,0.07)] flex overflow-hidden p-3 md:p-4 gap-3.5 relative">

        <!-- ========================================================================= -->
        <!-- FLOATING SIDEBAR DOCK (Melayang Ramping & Elegan)                          -->
        <!-- ========================================================================= -->
        <aside class="w-18 md:w-20 bg-white/95 backdrop-blur-md rounded-[26px] border border-slate-200/70 shadow-sm flex flex-col items-center py-4 justify-between shrink-0 select-none z-30">
            
            <!-- Logo Icon -->
            <div class="flex flex-col items-center gap-1">
                <a href="{{ route('admin.dashboard') }}" class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-blue-500 flex items-center justify-center shadow-md shadow-blue-500/25 group transition-transform hover:scale-105 active:scale-95" title="Edubank SMKN 1 Bantul">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </a>
                <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mt-1">Edubank</span>
            </div>

            <!-- Navigation Menu Icons -->
            <nav class="flex flex-col items-center gap-2 w-full px-2 my-auto">
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
                    <!-- Teller Dashboard -->
                    <a href="{{ route('teller.dashboard') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.dashboard') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Dashboard Loket
                        </span>
                    </a>

                    <!-- Teller Scanner / Quick Transaction -->
                    <a href="{{ route('teller.transactions.scanner') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.transactions.scanner') || request()->routeIs('teller.transactions.create') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <span class="absolute left-16 bg-blue-600 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Scan QR / Loket
                        </span>
                    </a>

                    <!-- Teller Transactions Ledger -->
                    <a href="{{ route('teller.transactions.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.transactions.index') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Riwayat Transaksi
                        </span>
                    </a>

                    <!-- Teller Daily Closing -->
                    <a href="{{ route('teller.daily-closings.index') }}" 
                       class="relative group w-11 h-11 rounded-2xl flex items-center justify-center transition-all duration-200 {{ request()->routeIs('teller.daily-closings.*') ? 'bg-slate-900 text-white shadow-md shadow-slate-900/20' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-100/80' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="absolute left-16 bg-slate-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-lg z-50">
                            Rekonsiliasi Kas Harian
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
                
                <!-- Kiri: Breadcrumb / Page Title & Search Pill -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-slate-400">Edubank</span>
                        <span class="text-slate-300">/</span>
                        <h1 class="text-xs font-bold text-slate-800 uppercase tracking-wider">@yield('page_title', 'Dashboard')</h1>
                    </div>

                    <!-- Compact Search Pill -->
                    <div class="hidden md:flex items-center bg-slate-100/80 rounded-full px-3 py-1.5 text-xs text-slate-500 border border-slate-200/50 w-64 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:bg-white transition-all">
                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" placeholder="Cari siswa, rekening, atau transaksi..." class="bg-transparent border-none outline-none text-xs w-full text-slate-700 placeholder-slate-400 focus:ring-0 p-0">
                    </div>
                </div>

                <!-- Kanan: Quick Date, Badges & Profile Pill -->
                <div class="flex items-center gap-3">
                    <!-- Academic Year Badge -->
                    <div class="hidden sm:flex items-center gap-1.5 bg-blue-50/80 text-blue-700 border border-blue-200/60 rounded-full px-3 py-1 text-[11px] font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        TA 2026/2027
                    </div>

                    <!-- Clock / Date -->
                    <div class="hidden lg:block text-right">
                        <p class="text-[11px] font-semibold text-slate-600">{{ date('d M Y') }}</p>
                        <p class="text-[10px] text-slate-400">SMKN 1 Bantul</p>
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
                <div class="bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-3.5 py-2 rounded-2xl text-xs flex items-center justify-between shadow-sm animate-fade-in shrink-0">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200/80 text-rose-800 px-3.5 py-2 rounded-2xl text-xs flex items-center justify-between shadow-sm animate-fade-in shrink-0">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-sm font-bold">&times;</button>
                </div>
            @endif

            <!-- CONTENT SLOT (Bento Grid Halaman) -->
            <div class="flex-1 overflow-y-auto pr-1">
                @yield('content')
            </div>

        </main>
    </div>

    @stack('scripts')
</body>
</html>
