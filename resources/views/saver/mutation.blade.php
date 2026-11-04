@extends('layouts.app')

@section('title', 'Buku Tabungan & Riwayat Mutasi Siswa')
@section('page_title', 'Buku Tabungan Siswa')

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
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Bento Header & Filter Toolbar (Konsisten Dibungkus Card) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('saver.dashboard') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0 cursor-pointer no-print"
               title="Kembali ke Dashboard Tabungan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Buku Tabungan Digital</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    {{ $saver->full_name }} — <span class="font-mono text-blue-700">{{ $saver->account_number }}</span>
                </h1>
                <p class="text-xs text-slate-500">Kelas: <strong class="text-slate-700">{{ $saver->class_name }}</strong> &bull; NIS: <span class="font-mono">{{ $saver->student_id_number }}</span></p>
            </div>
        </div>

        <div class="flex items-center gap-2 no-print">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-xs transition-all cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Buku Mutasi</span>
            </button>
        </div>
    </div>

    <!-- Passbook Table Bento Box (Memanjang Penuh Sejajar Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Filter Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0 no-print">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Daftar Ayat Mutasi Tabungan
                </h3>

                <form method="GET" action="{{ route('saver.mutation') }}" class="flex flex-wrap items-center gap-2 text-xs">
                    <div class="flex items-center gap-1.5 bg-slate-50 rounded-xl px-2.5 py-1.5 border border-slate-200/80">
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Mulai:</span>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" class="bg-transparent border-none outline-none text-xs text-slate-700 p-0 font-medium">
                    </div>

                    <div class="flex items-center gap-1.5 bg-slate-50 rounded-xl px-2.5 py-1.5 border border-slate-200/80">
                        <span class="text-slate-400 text-[10px] uppercase font-bold">Sampai:</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" class="bg-transparent border-none outline-none text-xs text-slate-700 p-0 font-medium">
                    </div>

                    <select name="type" onchange="this.form.submit()" class="bg-slate-50 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/80 outline-none font-medium cursor-pointer">
                        <option value="">Semua Transaksi</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Setoran Saja (+)</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Penarikan Saja (-)</option>
                    </select>

                    @if(request()->anyFilled(['start_date', 'end_date', 'type']))
                        <a href="{{ route('saver.mutation') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3">Tanggal & Waktu</th>
                            <th class="py-2.5 px-3">No. Kwitansi</th>
                            <th class="py-2.5 px-3">Jenis</th>
                            <th class="py-2.5 px-3">Keterangan</th>
                            <th class="py-2.5 px-3 text-right">Debit (Setor)</th>
                            <th class="py-2.5 px-3 text-right">Kredit (Tarik)</th>
                            <th class="py-2.5 px-3 text-right">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transactions as $t)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3 text-slate-600 text-[11px]">
                                    <div class="font-medium text-slate-800">{{ $t->transaction_date->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $t->transaction_date->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-slate-800 text-[11px]">
                                    {{ $t->receipt_number }}
                                </td>
                                <td class="py-3 px-3">
                                    @if($t->transaction_type === 'deposit')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Setoran
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Penarikan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-slate-600 text-[11px]">
                                    {{ $t->description ?: '-' }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-emerald-700 text-xs">
                                    @if($t->transaction_type === 'deposit')
                                        +Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-300 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-rose-600 text-xs">
                                    @if($t->transaction_type === 'withdrawal')
                                        -Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-300 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-extrabold text-slate-900 text-xs">
                                    Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <span class="text-xs font-medium">Tidak ada catatan transaksi yang sesuai dengan kriteria filter</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer (Pinned at Bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0 no-print">
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
