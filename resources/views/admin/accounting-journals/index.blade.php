@extends('layouts.app')

@section('title', 'Jurnal Akuntansi & Buku Besar')
@section('page_title', 'Jurnal Akuntansi')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Stats Grid (4 Card Bersih: Status, Uang Masuk, Uang Keluar, Total Ayat) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">
        <!-- Card 1: Status Keseimbangan Double-Entry -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400">Status Keseimbangan</span>
            <div class="mt-2 flex items-center gap-2">
                @if($isBalanced)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Seimbang
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        Tidak Seimbang
                    </span>
                @endif
            </div>
        </div>

        <!-- Card 2: Total Uang Masuk (Kas Debet) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400">Total Uang Masuk</span>
            <p class="text-2xl font-mono font-extrabold text-slate-900 mt-2">
                Rp {{ number_format($totalIn, 0, ',', '.') }}
            </p>
        </div>

        <!-- Card 3: Total Uang Keluar (Kas Kredit) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400">Total Uang Keluar</span>
            <p class="text-2xl font-mono font-extrabold text-slate-900 mt-2">
                Rp {{ number_format($totalOut, 0, ',', '.') }}
            </p>
        </div>

        <!-- Card 4: Total Ayat Jurnal (Baris Buku Besar) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400">Total Ayat Jurnal</span>
            <p class="text-2xl font-mono font-extrabold text-slate-900 mt-2">
                {{ number_format($totalEntries ?? $journals->total(), 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Main Table Card (Memanjang Sepanjang Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Judul & Filter) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Buku Besar & Ayat Jurnal
                </h3>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.accounting-journals.index') }}" class="flex flex-wrap items-center gap-2">
                    <input type="date" 
                           name="date" 
                           value="{{ request('date') }}" 
                           onchange="this.form.submit()" 
                           class="bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">

                    <select name="account_code" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Kode Akun</option>
                        <option value="101" {{ request('account_code') == '101' ? 'selected' : '' }}>101 - Kas Fisik Bank Mini</option>
                        <option value="201" {{ request('account_code') == '201' ? 'selected' : '' }}>201 - Simpanan Tabungan Siswa</option>
                    </select>

                    @if(request()->anyFilled(['date', 'account_code']))
                        <a href="{{ route('admin.accounting-journals.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3 font-medium">Tanggal & Waktu</th>
                            <th class="py-2.5 px-3 font-medium">Kode Akun</th>
                            <th class="py-2.5 px-3 font-medium">Nama Akun (COA)</th>
                            <th class="py-2.5 px-3 font-medium">Keterangan Jurnal</th>
                            <th class="py-2.5 px-3 font-medium text-right">Debet</th>
                            <th class="py-2.5 px-3 font-medium text-right">Kredit</th>
                            <th class="py-2.5 px-3 font-medium text-right">No. Kwitansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($journals as $j)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-800">
                                        {{ $j->entry_date->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $j->entry_date->format('H:i:s') }} WIB</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-mono font-semibold px-2.5 py-0.5 rounded-full text-[11px] {{ $j->account_code === '101' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-purple-50 text-purple-700 border border-purple-200/60' }}">
                                        {{ $j->account_code }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 font-semibold text-slate-800">
                                    {{ $j->account_name }}
                                </td>
                                <td class="py-3 px-3 text-slate-600">
                                    <div class="font-medium text-slate-800">{{ $j->description }}</div>
                                    @if($j->transaction?->saver)
                                        <div class="text-[11px] text-slate-400 font-mono">
                                            Nasabah: {{ $j->transaction->saver->full_name }} ({{ $j->transaction->saver->account_number }})
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-xs text-slate-900">
                                    @if($j->position === 'debit')
                                        <span class="text-blue-700">Rp {{ number_format($j->amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-xs text-slate-900">
                                    @if($j->position === 'credit')
                                        <span class="text-indigo-700">Rp {{ number_format($j->amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if($j->transaction)
                                        <span class="font-mono text-xs font-semibold text-slate-700">
                                            {{ $j->transaction->receipt_number }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-400">Manual Entry</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        <span class="text-xs font-medium">Tidak ada catatan jurnal akuntansi untuk kriteria pencarian ini</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer (Pinned at bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $journals->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $journals->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $journals->total() }}</span> ayat jurnal
            </div>
            <div>
                {{ $journals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
