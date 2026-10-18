@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-3.5 pb-2">

    <!-- ========================================================================= -->
    <!-- ROW 1: 4 COMPACT FINANCIAL KPI CARDS (Bento Metric Pills)                 -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        
        <!-- KPI 1: Kas di Brankas Utama -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-200/60 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kas Brankas Utama</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">
                Rp {{ number_format($vaultBalance, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1.5 text-[10px] text-emerald-600 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Tersimpan di Brankas Sekolah
            </div>
        </div>

        <!-- KPI 2: Total Tabungan Siswa (Liabilitas Bank) -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-200/60 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Simpanan Siswa</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <p class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">
                Rp {{ number_format($totalStudentSavings, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1.5 text-[10px] text-blue-600 font-semibold">
                <span>{{ $totalSavers }} Nasabah Aktif</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-400">Dari {{ $totalMembers }} Siswa</span>
            </div>
        </div>

        <!-- KPI 3: Akumulasi Setoran Masuk -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-200/60 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Setoran Tunai</span>
                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                </div>
            </div>
            <p class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">
                Rp {{ number_format($totalDeposits, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1.5 text-[10px] text-teal-600 font-semibold">
                <span>Arus Kas Masuk Loket</span>
            </div>
        </div>

        <!-- KPI 4: Akumulasi Penarikan Keluar -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-200/60 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Penarikan Tunai</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                </div>
            </div>
            <p class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">
                Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1.5 text-[10px] text-amber-600 font-semibold">
                <span>Sisa Mengendap Min Rp 10.000</span>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- ROW 2: BENTO GRID SPLIT (Recent Activity Table + Welcome Card & Status)   -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5">
        
        <!-- KOLOM KIRI (7 Cols): Dense Recent Transactions Table -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-600"></div>
                        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Aktivitas Transaksi Loket Terkini</h2>
                    </div>
                    <span class="text-[11px] text-slate-400 font-medium">Auto-Sync Real-time</span>
                </div>

                <!-- Dense Table (Font Kecil, Elegan, Muat Banyak Data) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-[10px] uppercase font-bold text-slate-400">
                                <th class="pb-2 font-semibold">Kode TRX</th>
                                <th class="pb-2 font-semibold">Nasabah / Siswa</th>
                                <th class="pb-2 font-semibold">Jurusan</th>
                                <th class="pb-2 font-semibold">Jenis</th>
                                <th class="pb-2 font-semibold text-right">Nominal</th>
                                <th class="pb-2 font-semibold text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-[11px]">
                            @forelse($recentTransactions as $trx)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 font-mono text-[10px] font-semibold text-slate-600">
                                        {{ $trx->transaction_code }}
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
                                    <td class="py-2.5 text-right font-bold {{ $trx->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-slate-800' }}">
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

            <!-- Mini Major Distribution Pills (8 Jurusan SMKN 1 Bantul) -->
            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider">8 Jurusan SMKN 1 Bantul:</span>
                <div class="flex items-center gap-1 flex-wrap">
                    @foreach(['TKJ', 'RPL', 'DKV', 'AKL', 'LPS', 'MP', 'BR', 'BD'] as $m)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/40">
                            {{ $m }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (5 Cols): Welcome Card Bento & Quick Status -->
        <div class="lg:col-span-5 flex flex-col gap-3.5">
            
            <!-- Bento Card 1: WELCOME CARD (Sesuai Referensi Gambar) -->
            <div class="bg-gradient-to-br from-white via-blue-50/30 to-indigo-50/40 rounded-2xl border border-slate-200/70 p-4 shadow-sm relative overflow-hidden">
                <!-- 3D Orb Effect Visual -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-gradient-to-tr from-blue-400/30 via-indigo-400/20 to-teal-300/30 blur-2xl pointer-events-none"></div>

                <div class="flex items-start justify-between mb-3 relative z-10">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-100/70 text-blue-700 text-[10px] font-bold mb-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            Sistem Siap Operasional
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-800">
                            Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}!
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                            Pusat kendali E-Teller Bank Mini SMKN 1 Bantul. Pilih aksi cepat berikut:
                        </p>
                    </div>

                    <!-- 3D Sphere Graphic -->
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-500 to-sky-300 shadow-lg shadow-blue-500/30 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>

                <!-- Quick Action Bento Pills -->
                <div class="grid grid-cols-2 gap-2 relative z-10">
                    <a href="{{ route('admin.members.index') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60 shadow-xs hover:border-blue-400 hover:shadow-sm transition-all group">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Data Siswa</span>
                    </a>

                    <a href="{{ route('admin.savers.create') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60 shadow-xs hover:border-emerald-400 hover:shadow-sm transition-all group">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Buka Rekening</span>
                    </a>

                    <a href="{{ route('admin.daily-closings.index') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60 shadow-xs hover:border-amber-400 hover:shadow-sm transition-all group">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Approval Kas</span>
                    </a>

                    <a href="{{ route('admin.accounting-journals.index') }}" class="flex items-center gap-2 p-2 rounded-xl bg-white border border-slate-200/60 shadow-xs hover:border-indigo-400 hover:shadow-sm transition-all group">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Audit Jurnal</span>
                    </a>
                </div>
            </div>

            <!-- Bento Card 2: Status Rekonsiliasi Kas Harian (Daily Report Queue) -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-3.5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-800">Antrean Penutupan Kas Harian</span>
                    <a href="{{ route('admin.daily-closings.index') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800">Lihat Semua &rarr;</a>
                </div>

                @if($pendingClosings > 0)
                    <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                            <span class="text-[11px] font-bold text-amber-800">{{ $pendingClosings }} Laporan Teller Menunggu Approval Brankas</span>
                        </div>
                        <a href="{{ route('admin.daily-closings.index') }}" class="px-2.5 py-1 rounded-lg bg-amber-600 text-white text-[10px] font-bold hover:bg-amber-700 shadow-xs">
                            Verifikasi
                        </a>
                    </div>
                @else
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-[11px] text-slate-600 font-medium">Seluruh rekonsiliasi kas loket teller hari ini tertib & seimbang.</span>
                    </div>
                @endif
            </div>

            <!-- Bento Card 3: Double-Entry Accounting Audit Badge -->
            <div class="bg-white rounded-2xl border border-slate-200/60 p-3.5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Audit Integritas Jurnal</p>
                    <p class="text-xs font-bold text-slate-800 mt-0.5">Double-Entry Akuntansi (101 Kas vs 201 Simpanan)</p>
                </div>
                <div class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/70 text-[10px] font-bold flex items-center gap-1.5 shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    100% BALANCE
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
