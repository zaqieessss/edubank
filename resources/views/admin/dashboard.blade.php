@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-4 pb-4">

    <!-- ========================================================================= -->
    <!-- 1. CARD PANJANG: ELEGANT FULL-WIDTH WELCOME HERO BANNER                   -->
    <!-- ========================================================================= -->
    <div class="relative w-full rounded-[26px] bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-5 md:p-6 shadow-xl shadow-slate-900/10 border border-slate-800/80 overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-5">
        
        <!-- Ambient Glowing Background Accents -->
        <div class="absolute -left-12 -top-12 w-48 h-48 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Left: Status Pill, Title & Description -->
        <div class="relative z-10 max-w-xl space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[10px] font-bold text-blue-200 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Sistem Siap Operasional &bull; TA 2026/2027</span>
            </div>
            
            <h2 class="text-lg md:text-xl font-extrabold tracking-tight text-white leading-snug">
                Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}!
            </h2>
            
            <p class="text-xs text-slate-300 font-normal leading-relaxed">
                Pusat kendali dan audit operasional perbankan mini SMK Negeri 1 Bantul. Seluruh transaksi terekam otomatis dalam prinsip akuntansi double-entry.
            </p>
        </div>

        <!-- Right: Elegant Quick Action Buttons -->
        <div class="relative z-10 flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('admin.members.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 backdrop-blur-md text-xs font-bold transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Data Siswa</span>
            </a>

            <a href="{{ route('admin.savers.create') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white border border-blue-400/40 text-xs font-bold shadow-md shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buka Rekening</span>
            </a>

            <a href="{{ route('admin.daily-closings.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 backdrop-blur-md text-xs font-bold transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Approval Kas</span>
            </a>

            <a href="{{ route('admin.accounting-journals.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 backdrop-blur-md text-xs font-bold transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>Audit Jurnal</span>
            </a>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. 4 FINANCIAL KPI CARDS (Bersih, Lega & Whitespace Nyaman)               -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        
        <!-- KPI 1: Kas di Brankas Utama -->
        <div class="bg-white rounded-[22px] p-4 md:p-5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kas Brankas Utama</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/80 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="pt-3">
                <p class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight font-mono">
                    Rp {{ number_format($vaultBalance, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- KPI 2: Total Simpanan Siswa -->
        <div class="bg-white rounded-[22px] p-4 md:p-5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Simpanan Siswa</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/80 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <div class="pt-3">
                <p class="text-xl md:text-2xl font-extrabold text-blue-700 tracking-tight font-mono">
                    Rp {{ number_format($totalStudentSavings, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- KPI 3: Akumulasi Setoran Tunai -->
        <div class="bg-white rounded-[22px] p-4 md:p-5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Setoran Tunai</span>
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100/80 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                </div>
            </div>
            <div class="pt-3">
                <p class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight font-mono">
                    Rp {{ number_format($totalDeposits, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- KPI 4: Akumulasi Penarikan Tunai -->
        <div class="bg-white rounded-[22px] p-4 md:p-5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Penarikan Tunai</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100/80 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                </div>
            </div>
            <div class="pt-3">
                <p class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-tight font-mono">
                    Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}
                </p>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 3. MAIN BENTO SECTION: RECENT TRANSACTIONS & OPERATIONAL STATUS           -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5">
        
        <!-- KOLOM KIRI (7 Cols): Dense Recent Transactions Table -->
        <div class="lg:col-span-7 bg-white rounded-[24px] border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] p-4 md:p-5 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Aktivitas Transaksi Loket Terkini</h3>
                    </div>
                    <a href="{{ route('admin.accounting-journals.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">
                        Buku Besar &rarr;
                    </a>
                </div>

                <!-- Dense Table -->
                <div class="overflow-x-auto pt-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] uppercase font-bold text-slate-400">
                                <th class="pb-2.5 font-semibold">Kode TRX</th>
                                <th class="pb-2.5 font-semibold">Nasabah / Siswa</th>
                                <th class="pb-2.5 font-semibold">Jurusan</th>
                                <th class="pb-2.5 font-semibold">Jenis</th>
                                <th class="pb-2.5 font-semibold text-right">Nominal</th>
                                <th class="pb-2.5 font-semibold text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-[11px]">
                            @forelse($recentTransactions as $trx)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 font-mono text-[10px] font-semibold text-slate-600">
                                        {{ $trx->receipt_number }}
                                    </td>
                                    <td class="py-2.5">
                                        <p class="font-bold text-slate-800 leading-tight">{{ $trx->saver->full_name ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $trx->saver->account_number ?? '-' }}</p>
                                    </td>
                                    <td class="py-2.5">
                                        <span class="inline-block px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-slate-100 text-slate-700">
                                            {{ $trx->saver->major ?? 'RPL' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5">
                                        @if($trx->transaction_type === 'deposit')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1 h-1 rounded-full bg-emerald-600"></span> Setoran
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                                <span class="w-1 h-1 rounded-full bg-rose-600"></span> Penarikan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 text-right font-mono font-bold {{ $trx->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-slate-800' }}">
                                        {{ $trx->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 text-right text-[10px] text-slate-400">
                                        {{ $trx->transaction_date ? $trx->transaction_date->format('H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-xs text-slate-400">
                                        Belum ada mutasi transaksi hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 8 Jurusan SMKN 1 Bantul Footer Pills -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider">8 Jurusan SMKN 1 Bantul:</span>
                <div class="flex items-center gap-1 flex-wrap">
                    @foreach(['TKJ', 'RPL', 'DKV', 'AKL', 'LPS', 'MP', 'BR', 'BD'] as $m)
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/40">
                            {{ $m }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (5 Cols): Operational Queue & Journal Audit -->
        <div class="lg:col-span-5 flex flex-col gap-3.5">
            
            <!-- Bento Box 1: Antrean Penutupan Kas Harian (Daily Report Queue) -->
            <div class="bg-white rounded-[24px] border border-slate-200/80 p-4 md:p-5 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Antrean Penutupan Kas Harian</span>
                    <a href="{{ route('admin.daily-closings.index') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800">Lihat Semua &rarr;</a>
                </div>

                @if($pendingClosings > 0)
                    <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping shrink-0"></span>
                            <span class="text-xs font-bold text-amber-900">{{ $pendingClosings }} Laporan Teller Menunggu Approval Brankas</span>
                        </div>
                        <a href="{{ route('admin.daily-closings.index') }}" class="px-3 py-1.5 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-700 shadow-xs shrink-0">
                            Verifikasi
                        </a>
                    </div>
                @else
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs text-slate-600 font-medium">Seluruh rekonsiliasi kas loket teller hari ini tertib & seimbang.</span>
                    </div>
                @endif
            </div>

            <!-- Bento Box 2: Audit Integritas Jurnal Akuntansi -->
            <div class="bg-white rounded-[24px] border border-slate-200/80 p-4 md:p-5 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Audit Integritas Jurnal</span>
                    <span class="text-[10px] font-mono text-slate-400">Prinsip Akuntansi</span>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-800">Double-Entry Ledger</p>
                        <p class="text-[11px] text-slate-500">101 Kas Fisik vs 201 Simpanan Siswa</p>
                    </div>
                    <div class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-extrabold flex items-center gap-1.5 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        100% BALANCE
                    </div>
                </div>
            </div>

            <!-- Bento Box 3: Ringkasan Partisipasi Siswa (Nasabah Ratio) -->
            <div class="bg-white rounded-[24px] border border-slate-200/80 p-4 md:p-5 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Cakupan Rekening Siswa</span>
                    <span class="text-xs font-mono font-bold text-blue-700">
                        {{ $totalMembers > 0 ? round(($totalSavers / $totalMembers) * 100, 1) : 0 }}%
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ $totalMembers > 0 ? min(100, ($totalSavers / $totalMembers) * 100) : 0 }}%">
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] text-slate-500">
                    <span><strong>{{ $totalSavers }}</strong> Ber-rekening</span>
                    <span>Dari <strong>{{ $totalMembers }}</strong> Master Siswa</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
