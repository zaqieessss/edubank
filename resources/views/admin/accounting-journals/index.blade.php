@extends('layouts.app')

@section('title', 'Jurnal Akuntansi & Buku Besar (Journal)')
@section('page_title', 'Journal / Buku Besar')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Stats Grid with Balance Status -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <!-- Balance Status Badge Card -->
        <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Keseimbangan Jurnal</p>
                <div class="mt-1 flex items-center gap-1.5">
                    @if($isBalanced)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            SEIMBANG (BALANCED)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            TIDAK SEIMBANG!
                        </span>
                    @endif
                </div>
                <span class="text-[10px] font-medium text-slate-500 mt-1 block">Prinsip Akuntansi Double-Entry</span>
            </div>
            <div class="w-10 h-10 rounded-2xl {{ $isBalanced ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>

        <!-- Total Debit -->
        <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Akumulasi Debet</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5 font-mono">
                    Rp {{ number_format($totalDebits, 0, ',', '.') }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Sisi kiri buku jurnal</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            </div>
        </div>

        <!-- Total Kredit -->
        <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Akumulasi Kredit</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5 font-mono">
                    Rp {{ number_format($totalCredits, 0, ',', '.') }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Sisi kanan buku jurnal</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
            </div>
        </div>

        <!-- Bagan Akun Standar (COA) -->
        <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Standar Kode Akun</p>
                <div class="mt-1 flex items-center gap-2">
                    <span class="text-[10px] bg-slate-100 font-bold px-2 py-0.5 rounded text-slate-700">101: Kas</span>
                    <span class="text-[10px] bg-slate-100 font-bold px-2 py-0.5 rounded text-slate-700">201: Simpanan</span>
                </div>
                <span class="text-[10px] font-medium text-slate-400 mt-1 block">Otomatisasi trigger double entry</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box with Controls -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <!-- Action Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('admin.accounting-journals.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-slate-100/90 rounded-xl px-3 py-1.5 text-xs text-slate-600 border border-slate-200/60 w-44 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                    <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-transparent border-none outline-none text-xs w-full p-0">
                </div>

                <select name="account_code" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none">
                    <option value="">Semua Akun (101 & 201)</option>
                    <option value="101" {{ request('account_code') == '101' ? 'selected' : '' }}>101 - Kas Fisik Bank Mini</option>
                    <option value="201" {{ request('account_code') == '201' ? 'selected' : '' }}>201 - Simpanan Tabungan Siswa</option>
                </select>

                @if(request()->anyFilled(['date', 'account_code']))
                    <a href="{{ route('admin.accounting-journals.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                @endif
            </form>

            <span class="text-xs text-slate-500">
                Setiap mutasi transaksi mencatat 2 baris seimbang berpasangan
            </span>
        </div>

        <!-- Dense Modern Responsive Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">Tanggal & Waktu</th>
                        <th class="py-2.5 px-3">Kode Akun</th>
                        <th class="py-2.5 px-3">Nama Akun (COA)</th>
                        <th class="py-2.5 px-3">Keterangan Jurnal</th>
                        <th class="py-2.5 px-3 text-right">Debet</th>
                        <th class="py-2.5 px-3 text-right">Kredit</th>
                        <th class="py-2.5 px-3 text-right">Ref. Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($journals as $j)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 font-semibold text-slate-800">
                                {{ $j->entry_date->translatedFormat('d/m/Y') }}
                                <div class="text-[10px] text-slate-400 font-mono">{{ $j->entry_date->format('H:i:s') }}</div>
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="font-mono font-bold px-2 py-0.5 rounded-md text-[11px] {{ $j->account_code === '101' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $j->account_code }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 font-bold text-slate-800">
                                {{ $j->account_name }}
                            </td>
                            <td class="py-2.5 px-3 text-slate-600">
                                <div>{{ $j->description }}</div>
                                @if($j->transaction?->saver)
                                    <div class="text-[10px] text-slate-400 font-mono">Nasabah: {{ $j->transaction->saver->full_name }} ({{ $j->transaction->saver->account_number }})</div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                @if($j->position === 'debit')
                                    <span class="text-blue-700 font-extrabold">Rp {{ number_format($j->amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                @if($j->position === 'credit')
                                    <span class="text-indigo-700 font-extrabold">Rp {{ number_format($j->amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                @if($j->transaction)
                                    <span class="font-mono text-[11px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $j->transaction->receipt_number }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-400">Manual Entry</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Tidak ada catatan jurnal akuntansi untuk kriteria pencarian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
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
