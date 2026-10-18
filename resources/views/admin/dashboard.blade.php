@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-5 max-w-6xl mx-auto py-1">

    <!-- ========================================================================= -->
    <!-- 1. WELCOME CARD (Minimalis, Simpel & Elegan)                               -->
    <!-- ========================================================================= -->
    <div class="relative w-full rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white p-6 md:p-7 shadow-sm border border-slate-800/60 overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-5">
        <!-- Subtle Ambient Glow -->
        <div class="absolute -left-10 -top-10 w-44 h-44 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <h2 class="text-xl md:text-2xl font-bold tracking-tight text-white">
                Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}
            </h2>
            <p class="text-xs text-slate-300 max-w-xl leading-relaxed">
                Pusat kendali operasional perbankan mini sekolah. Pantau pertumbuhan tabungan siswa dan mutasi kas secara real-time.
            </p>
        </div>

        <!-- Minimalist Action Buttons -->
        <div class="relative z-10 flex items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.savers.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-all hover:shadow hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buka Rekening</span>
            </a>
            <a href="{{ route('admin.members.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 text-xs font-semibold backdrop-blur-md transition-all">
                <span>Data Siswa</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi jika ada Penutupan Kas Menunggu Approval -->
    @if($pendingClosings > 0)
        <div class="bg-amber-50/80 border border-amber-200/80 rounded-2xl px-4 py-3 flex items-center justify-between gap-3 text-xs text-amber-900 animate-fade-in">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                <span>Terdapat <strong>{{ $pendingClosings }} laporan penutupan kas teller</strong> yang menunggu verifikasi serah terima brankas.</span>
            </div>
            <a href="{{ route('admin.daily-closings.index') }}" class="font-bold underline text-amber-900 hover:text-amber-950 shrink-0">
                Verifikasi Sekarang &rarr;
            </a>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 2. TIGA KARTU INFORMASI UTAMA (Jumlah Nasabah, Total Simpanan, Mutasi)    -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Kartu 1: Jumlah Nasabah -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Jumlah Nasabah
            </span>
            <div class="my-auto py-2">
                <p class="text-3xl font-extrabold text-slate-900 font-mono tracking-tight">
                    {{ number_format($totalSavers, 0, ',', '.') }}
                </p>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Siswa aktif ber-rekening dari {{ $totalMembers }} total murid
            </span>
        </div>

        <!-- Kartu 2: Total Simpanan Siswa -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Simpanan Siswa
            </span>
            <div class="my-auto py-2">
                <p class="text-3xl font-extrabold text-blue-700 font-mono tracking-tight">
                    Rp {{ number_format($totalStudentSavings, 0, ',', '.') }}
                </p>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Saldo simpanan tabungan siswa
            </span>
        </div>

        <!-- Kartu 3: Transaksi Masuk & Keluar Hari Ini -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Transaksi Masuk & Keluar Hari Ini
            </span>
            <div class="my-auto py-2 flex items-baseline gap-4">
                <div>
                    <span class="text-[10px] text-emerald-600 font-bold block uppercase">Setoran Masuk</span>
                    <p class="text-xl font-extrabold text-emerald-700 font-mono">
                        +Rp {{ number_format($totalDeposits, 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-[10px] text-rose-500 font-bold block uppercase">Penarikan Keluar</span>
                    <p class="text-xl font-extrabold text-slate-800 font-mono">
                        -Rp {{ number_format($totalWithdrawals, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Buku kas loket teller hari ini
            </span>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 3. TABEL TRANSAKSI TERAKHIR (Bersih, Renggang & Tenang di Mata)           -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-4">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-800">
                    Transaksi Terakhir
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    Aktivitas setoran dan penarikan yang baru saja dilayani teller.
                </p>
            </div>
            <a href="{{ route('admin.accounting-journals.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                Lihat Semua Jurnal &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                        <th class="py-3 px-2 font-medium">Waktu</th>
                        <th class="py-3 px-2 font-medium">No. Kwitansi</th>
                        <th class="py-3 px-2 font-medium">Nama Siswa / Nasabah</th>
                        <th class="py-3 px-2 font-medium">No. Rekening</th>
                        <th class="py-3 px-2 font-medium">Jenis Transaksi</th>
                        <th class="py-3 px-2 font-medium text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-2 text-slate-500 font-mono">
                                {{ $trx->transaction_date ? $trx->transaction_date->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-2 font-mono font-semibold text-slate-700">
                                {{ $trx->receipt_number }}
                            </td>
                            <td class="py-4 px-2">
                                <div class="font-bold text-slate-800">{{ $trx->saver->full_name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $trx->saver->class_name ?? '' }}</div>
                            </td>
                            <td class="py-4 px-2 font-mono font-medium text-blue-700">
                                {{ $trx->saver->account_number ?? '-' }}
                            </td>
                            <td class="py-4 px-2">
                                @if($trx->transaction_type === 'deposit')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Setoran
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Penarikan
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-2 text-right font-mono font-bold text-sm {{ $trx->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-slate-800' }}">
                                {{ $trx->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                                Belum ada aktivitas transaksi tabungan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
