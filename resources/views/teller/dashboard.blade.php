@extends('layouts.app')

@section('title', 'Dashboard Teller - Loket Pelayanan')
@section('page_title', 'Dashboard Loket')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- ========================================================================= -->
    <!-- 1. WELCOME HERO CARD (Serasi dengan Dashboard Admin)                      -->
    <!-- ========================================================================= -->
    <div class="relative w-full rounded-[24px] bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white p-5 md:p-6 shadow-sm border border-slate-800/60 overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
        <!-- Ambient Glow -->
        <div class="absolute -left-10 -top-10 w-44 h-44 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[11px] font-semibold text-blue-200 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Loket Teller Aktif &bull; {{ Auth::user()->name }}
            </div>
            <h2 class="text-xl md:text-2xl font-bold tracking-tight text-white">
                Pelayanan Transaksi Loket
            </h2>
            <p class="text-xs text-slate-300 max-w-xl leading-relaxed">
                Pusat operasional teller perbankan mini sekolah. Scan QR kartu siswa atau cari rekening untuk melayani setoran dan penarikan.
            </p>
        </div>

        <!-- Tombol Scan QR & Cari Rekening -->
        <div class="relative z-10 flex flex-wrap items-center gap-2.5 shrink-0">
            <!-- Tombol Scan QR Utama -->
            <a href="{{ route('teller.transactions.scanner') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-md shadow-blue-600/30 transition-all hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>Scan QR Siswa</span>
            </a>

            <!-- Quick Search Form -->
            <form method="POST" action="{{ route('teller.transactions.search') }}" class="flex items-center bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/15 rounded-xl px-3 py-1.5 text-xs transition-all focus-within:bg-white focus-within:text-slate-900">
                @csrf
                <svg class="w-3.5 h-3.5 text-slate-300 mr-2 shrink-0 group-focus:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="query" placeholder="Ketik No. Rekening / NIS..." required class="bg-transparent border-none outline-none text-xs w-44 md:w-52 text-white placeholder-slate-300 focus:text-slate-900 focus:placeholder-slate-400 p-0 font-medium">
                <button type="submit" class="ml-2 px-2.5 py-1 bg-white/20 hover:bg-white/30 text-white rounded-lg text-[10px] font-bold shrink-0 transition-colors">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. TIGA KARTU INFORMASI LOKET (Kas Laci, Mutasi Setor/Tarik, Transaksi)    -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 shrink-0">
        
        <!-- Kartu 1: Saldo Kas Fisik Laci (Buku) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Saldo Kas Laci Hari Ini
            </span>
            <div class="py-2">
                <p class="text-2xl md:text-3xl font-extrabold text-blue-700 font-mono tracking-tight">
                    Rp {{ number_format($summary['closing_cash'], 0, ',', '.') }}
                </p>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400 font-medium">
                <span>{{ \Carbon\Carbon::parse($summary['date'])->translatedFormat('d F Y') }}</span>
                <a href="{{ route('teller.daily-closings.create') }}" class="font-bold text-amber-600 hover:text-amber-700">Tutup Kas &rarr;</a>
            </div>
        </div>

        <!-- Kartu 2: Uang Masuk & Keluar Hari Ini -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Arus Kas Masuk & Keluar
            </span>
            <div class="py-2 flex items-baseline gap-4">
                <div>
                    <span class="text-[10px] text-emerald-600 font-bold block uppercase">Setoran Masuk</span>
                    <p class="text-lg md:text-xl font-extrabold text-emerald-700 font-mono">
                        +Rp {{ number_format($summary['total_deposits'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-[10px] text-rose-500 font-bold block uppercase">Penarikan Keluar</span>
                    <p class="text-lg md:text-xl font-extrabold text-slate-800 font-mono">
                        -Rp {{ number_format($summary['total_withdrawals'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Total mutasi loket Anda hari ini
            </span>
        </div>

        <!-- Kartu 3: Total Transaksi Dilayani -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Pelayanan Hari Ini
            </span>
            <div class="py-2">
                <p class="text-2xl md:text-3xl font-extrabold text-slate-900 font-mono tracking-tight">
                    {{ number_format($summary['transaction_count'], 0, ',', '.') }}
                </p>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Siswa telah dilayani di meja loket
            </span>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 3. TABEL TRANSAKSI TERAKHIR (Memanjang Elegan Sejajar Bawah Sidebar)       -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[220px]">
        
        <div>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-2">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">
                        Aktivitas Transaksi Terakhir di Loket
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Daftar transaksi siswa yang baru saja Anda otorisasi.
                    </p>
                </div>
                <a href="{{ route('teller.transactions.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-2 font-medium">Waktu</th>
                            <th class="py-2.5 px-2 font-medium">No. Kwitansi</th>
                            <th class="py-2.5 px-2 font-medium">Nasabah (Siswa)</th>
                            <th class="py-2.5 px-2 font-medium">No. Rekening</th>
                            <th class="py-2.5 px-2 font-medium">Jenis Transaksi</th>
                            <th class="py-2.5 px-2 font-medium text-right">Nominal</th>
                            <th class="py-2.5 px-2 font-medium text-right">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentTransactions as $t)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-2 text-slate-500 font-mono text-[11px]">
                                    {{ $t->transaction_date ? $t->transaction_date->format('H:i') . ' WIB' : '-' }}
                                </td>
                                <td class="py-3 px-2 font-mono font-semibold text-slate-700 text-xs">
                                    {{ $t->receipt_number }}
                                </td>
                                <td class="py-3 px-2">
                                    <div class="font-bold text-slate-800">{{ $t->saver?->full_name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $t->saver?->class_name ?? '' }}</div>
                                </td>
                                <td class="py-3 px-2 font-mono font-medium text-blue-700">
                                    {{ $t->saver?->account_number ?? '-' }}
                                </td>
                                <td class="py-3 px-2">
                                    @if($t->transaction_type === 'deposit')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Setoran
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Penarikan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-right font-mono font-bold text-sm {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-slate-800' }}">
                                    {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-right">
                                    <a href="{{ route('teller.transactions.receipt', $t->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-colors">
                                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Cetak</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-slate-400">
                                    Belum ada aktivitas transaksi tabungan di loket Anda hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subtle Footer Alignment -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
            <span>Loket Pelayanan Teller</span>
            <span class="font-mono">Edubank SMKN 1 Bantul</span>
        </div>

    </div>

</div>
@endsection
