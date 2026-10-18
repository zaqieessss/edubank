@extends('layouts.app')

@section('title', 'Buku Tabungan & Mutasi Siswa')
@section('page_title', 'Mutasi Tabungan')

@push('styles')
<style>
    @media print {
        body { background: white !important; padding: 0 !important; }
        aside, header, .no-print { display: none !important; }
        .print-only { display: block !important; }
        .passbook-table { border: 1px solid #cbd5e1 !important; }
    }
</style>
@endpush

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Header & Filter Toolbar -->
    <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Buku Tabungan Siswa Digital</span>
                <h2 class="text-base font-extrabold text-slate-900 mt-0.5">
                    {{ $saver->full_name }} — <span class="font-mono text-blue-700">{{ $saver->account_number }}</span>
                </h2>
                <p class="text-xs text-slate-500">Kelas: {{ $saver->class_name }} &bull; NIS: {{ $saver->student_id_number }}</p>
            </div>

            <div class="flex items-center gap-2 no-print">
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Mutasi</span>
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('saver.mutation') }}" class="flex flex-wrap items-center gap-2 text-xs no-print">
            <div class="flex items-center gap-1.5 bg-slate-100 rounded-xl px-2.5 py-1.5 border border-slate-200/60">
                <span class="text-slate-400 text-[10px] uppercase font-bold">Mulai:</span>
                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="bg-transparent border-none outline-none text-xs text-slate-700 p-0">
            </div>

            <div class="flex items-center gap-1.5 bg-slate-100 rounded-xl px-2.5 py-1.5 border border-slate-200/60">
                <span class="text-slate-400 text-[10px] uppercase font-bold">Sampai:</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="bg-transparent border-none outline-none text-xs text-slate-700 p-0">
            </div>

            <select name="type" onchange="this.form.submit()" class="bg-slate-100 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none">
                <option value="">Semua Transaksi</option>
                <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Setoran Saja</option>
                <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Penarikan Saja</option>
            </select>

            @if(request()->anyFilled(['start_date', 'end_date', 'type']))
                <a href="{{ route('saver.mutation') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset Filter</a>
            @endif
        </form>
    </div>

    <!-- Passbook Table Bento Box -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        <div class="overflow-x-auto rounded-2xl border border-slate-100 passbook-table">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">Tanggal / Waktu</th>
                        <th class="py-2.5 px-3">No. Kwitansi</th>
                        <th class="py-2.5 px-3">Jenis Transaksi</th>
                        <th class="py-2.5 px-3">Keterangan</th>
                        <th class="py-2.5 px-3 text-right">Debit (Setor)</th>
                        <th class="py-2.5 px-3 text-right">Kredit (Tarik)</th>
                        <th class="py-2.5 px-3 text-right">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 font-semibold text-slate-800">
                                {{ $t->transaction_date->format('d/m/Y') }}
                                <div class="text-[10px] text-slate-400 font-mono">{{ $t->transaction_date->format('H:i') }}</div>
                            </td>
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-700 text-[11px]">
                                {{ $t->receipt_number }}
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
                            <td class="py-2.5 px-3 text-slate-600">
                                {{ $t->description ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-600 text-[11px]">
                                @if($t->transaction_type === 'deposit')
                                    + Rp {{ number_format($t->amount, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-rose-600 text-[11px]">
                                @if($t->transaction_type === 'withdrawal')
                                    - Rp {{ number_format($t->amount, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-extrabold text-slate-900 text-[11px]">
                                Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Tidak ada catatan transaksi yang sesuai dengan rentang tanggal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 no-print">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $transactions->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $transactions->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $transactions->total() }}</span> mutasi
            </div>
            <div>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
