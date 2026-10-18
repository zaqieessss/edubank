@extends('layouts.app')

@section('title', 'Dashboard Teller - Loket Pelayanan')
@section('page_title', 'Loket Teller')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento: Welcome Hero & Drawer Cash Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5">
        
        <!-- Welcome Hero & Quick Action Card -->
        <div class="lg:col-span-7 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 rounded-[28px] p-5 text-white shadow-xl shadow-slate-900/10 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[11px] font-semibold text-blue-200 mb-2.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Loket Teller Aktif &bull; {{ Auth::user()->name }}
                </div>
                <h2 class="text-xl font-extrabold tracking-tight">Pelayanan Setor & Tarik Tabungan</h2>
                <p class="text-xs text-slate-300 mt-1 max-w-lg leading-relaxed">
                    Siap melayani transaksi tabungan siswa SMKN 1 Bantul. Scan kartu QR siswa atau ketikkan nomor rekening/NIS untuk memulai transaksi.
                </p>
            </div>

            <!-- Quick Action Form: Search / Scan -->
            <div class="relative z-10 mt-5 pt-4 border-t border-white/10 flex flex-col sm:flex-row items-center gap-2.5">
                <form method="POST" action="{{ route('teller.transactions.search') }}" class="flex-1 w-full flex items-center bg-white/15 backdrop-blur-md border border-white/20 rounded-2xl px-3 py-2 text-xs focus-within:bg-white focus-within:text-slate-900 transition-all">
                    @csrf
                    <svg class="w-4 h-4 text-slate-300 mr-2 shrink-0 group-focus:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="query" placeholder="Ketik No. Rekening atau NIS Siswa..." required class="bg-transparent border-none outline-none text-xs w-full text-white placeholder-slate-300 focus:text-slate-900 focus:placeholder-slate-400 p-0">
                    <button type="submit" class="ml-2 px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[11px] font-bold shrink-0 shadow-sm">
                        Cari Akun
                    </button>
                </form>

                <a href="{{ route('teller.transactions.scanner') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs font-bold shadow-md shadow-emerald-600/30 transition-all shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Kamera QR Scanner</span>
                </a>
            </div>
        </div>

        <!-- Cash Drawer (Laci Kas) Summary Card -->
        <div class="lg:col-span-5 bg-white rounded-[28px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Laci Kas Fisik Hari Ini</h4>
                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($summary['date'])->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('teller.daily-closings.create') }}" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/70 rounded-xl text-[10px] font-bold transition-colors">
                    Tutup Kas (Closing)
                </a>
            </div>

            <!-- Estimated Drawer Balance -->
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                <div class="flex justify-between items-center text-slate-500">
                    <span>Kas Masuk (Total Setoran):</span>
                    <span class="font-mono font-bold text-emerald-600">+ Rp {{ number_format($summary['total_deposits'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-slate-500">
                    <span>Kas Keluar (Total Penarikan):</span>
                    <span class="font-mono font-bold text-rose-600">- Rp {{ number_format($summary['total_withdrawals'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-slate-200/70">
                    <span class="font-bold text-slate-800">Saldo Akhir di Laci (Buku):</span>
                    <span class="font-mono font-extrabold text-blue-700 text-base">Rp {{ number_format($summary['closing_cash'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-slate-500 px-1">
                <span>Total Transaksi Dilayani: <strong class="text-slate-800 font-mono">{{ $summary['transaction_count'] }}</strong></span>
                <span class="text-slate-400">Pastikan uang fisik pas</span>
            </div>
        </div>
    </div>

    <!-- Bottom Bento: Recent Transactions Handled by this Teller -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <div>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Transaksi Terakhir di Loket Anda</h3>
                <p class="text-[10px] text-slate-400">Catatan pelayanan loket secara real-time</p>
            </div>
            <a href="{{ route('teller.transactions.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">No. Kwitansi</th>
                        <th class="py-2.5 px-3">Waktu</th>
                        <th class="py-2.5 px-3">Nasabah (Siswa)</th>
                        <th class="py-2.5 px-3">No. Rekening</th>
                        <th class="py-2.5 px-3">Jenis</th>
                        <th class="py-2.5 px-3 text-right">Nominal</th>
                        <th class="py-2.5 px-3 text-right">Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTransactions as $t)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-800 text-[11px]">
                                {{ $t->receipt_number }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-500 text-[10px]">
                                {{ $t->transaction_date->format('H:i:s') }} WIB
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-800">{{ $t->saver?->full_name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $t->saver?->class_name ?? '-' }}</div>
                            </td>
                            <td class="py-2.5 px-3 font-mono text-blue-700 font-bold text-[11px]">
                                {{ $t->saver?->account_number ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3">
                                @if($t->transaction_type === 'deposit')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700">
                                        Setoran
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700">
                                        Penarikan
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-[11px] {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <a href="{{ route('teller.transactions.receipt', $t->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-colors">
                                    <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Cetak</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">
                                Belum ada transaksi yang diproses di loket Anda hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
