@extends('layouts.app')

@section('title', 'Buku Kas & Riwayat Transaksi - Loket Teller')
@section('page_title', 'Transaksi')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Bento Stats Grid (3 Cards Informatif & Spacious) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 shrink-0">
        <!-- Card 1: Total Mutasi Transaksi -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Transaksi Tabungan
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ number_format($transactions->total(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Mutasi</span>
            </div>
        </div>

        <!-- Card 2: Uang Masuk (Setoran) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Setoran Masuk
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">
                    {{ number_format(\App\Models\Transaction::where('transaction_type', 'deposit')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-emerald-600/80 font-medium">Transaksi</span>
            </div>
        </div>

        <!-- Card 3: Uang Keluar (Penarikan) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Penarikan Keluar
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-rose-600">
                    {{ number_format(\App\Models\Transaction::where('transaction_type', 'withdrawal')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-rose-600/80 font-medium">Transaksi</span>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box (Memanjang Elegan Sejajar Bawah Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Judul & Tombol Scan QR) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Daftar Mutasi Kas Transaksi Siswa
                </h3>

                <div class="flex items-center gap-2">
                    <a href="{{ route('teller.transactions.scanner') }}" 
                       class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>Scan QR Siswa</span>
                    </a>
                </div>
            </div>

        <!-- Table Container -->
        <div class="flex-1 overflow-x-auto mt-2">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
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
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-3 font-mono font-bold text-slate-800 text-[11px]">
                                {{ $t->receipt_number }}
                            </td>
                            <td class="py-3 px-3 text-slate-600 text-[11px]">
                                <div class="font-medium text-slate-800">{{ $t->transaction_date->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $t->transaction_date->format('H:i:s') }} WIB</div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-800">{{ $t->saver?->full_name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $t->saver?->class_name ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-blue-700 text-[11px]">
                                {{ $t->saver?->account_number ?? '-' }}
                            </td>
                            <td class="py-3 px-3">
                                @if($t->transaction_type === 'deposit')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Setoran
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Penarikan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-xs {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-900 text-xs">
                                Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-slate-600 text-[11px]">
                                {{ $t->user?->name ?? 'System' }}
                            </td>
                            <td class="py-3 px-3 text-right">
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
        </div>

        <!-- Pagination -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 shrink-0">
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
