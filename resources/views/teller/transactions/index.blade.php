@extends('layouts.app')

@section('title', 'Riwayat Transaksi Teller')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Total Transaksi -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Transaksi</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($transactions->total(), 0, ',', '.') }}</h3>
                <span class="text-[10px] font-medium text-slate-500">Mutasi tabungan</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
        </div>

        <!-- Total Setoran -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Setoran</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">
                    {{ \App\Models\Transaction::where('transaction_type', 'deposit')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Kas masuk loket</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </div>
        </div>

        <!-- Total Penarikan -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Penarikan</p>
                <h3 class="text-xl font-extrabold text-rose-600 mt-0.5">
                    {{ \App\Models\Transaction::where('transaction_type', 'withdrawal')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Kas keluar loket</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            </div>
        </div>

        <!-- Quick Scan -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Loket Pelayanan</p>
                <a href="{{ route('teller.transactions.scanner') }}" class="inline-block mt-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                    + Layani Nasabah
                </a>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Buku Kas Transaksi Siswa</h3>
                <p class="text-[11px] text-slate-400">Seluruh mutasi tercatat otomatis ganda (Double-entry)</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">No. Kwitansi</th>
                        <th class="py-2.5 px-3">Waktu Transaksi</th>
                        <th class="py-2.5 px-3">Nasabah (Siswa)</th>
                        <th class="py-2.5 px-3">No. Rekening</th>
                        <th class="py-2.5 px-3">Jenis</th>
                        <th class="py-2.5 px-3 text-right">Nominal</th>
                        <th class="py-2.5 px-3 text-right">Saldo Akhir</th>
                        <th class="py-2.5 px-3">Teller</th>
                        <th class="py-2.5 px-3 text-right">Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-800 text-[11px]">
                                {{ $t->receipt_number }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-600 text-[11px]">
                                {{ $t->transaction_date->format('d/m/Y') }}
                                <div class="text-[10px] text-slate-400 font-mono">{{ $t->transaction_date->format('H:i:s') }}</div>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-800">{{ $t->saver?->full_name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $t->saver?->class_name ?? '-' }}</div>
                            </td>
                            <td class="py-2.5 px-3 font-mono font-bold text-blue-700 text-[11px]">
                                {{ $t->saver?->account_number ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3">
                                @if($t->transaction_type === 'deposit')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        Setoran
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700">
                                        Penarikan
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-[11px] {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 text-[11px]">
                                Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-600 text-[11px]">
                                {{ $t->user?->name ?? 'System' }}
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <a href="{{ route('teller.transactions.receipt', $t->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-colors">
                                    <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Struk</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">
                                Belum ada riwayat transaksi di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $transactions->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $transactions->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $transactions->total() }}</span> transaksi
            </div>
            <div>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
