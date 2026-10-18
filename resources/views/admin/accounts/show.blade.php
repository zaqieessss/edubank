@extends('layouts.app')

@section('title', 'Kelola Rekening: ' . $account->account_number)
@section('page_title', 'Detail & Status Rekening')

@section('content')
<div class="space-y-4 pb-8">

    <!-- Top Action Toolbar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Rekening</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.savers.show', $account->saver_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Lihat Kartu Digital Siswa</span>
            </a>
        </div>
    </div>

    <!-- Bento Layout Grid: 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        
        <!-- Left Column: Account Info & Status Control -->
        <div class="lg:col-span-5 space-y-4">
            
            <!-- Account Summary Card -->
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-4">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Rekening Tabungan Siswa</span>
                        <h3 class="text-xl font-mono font-extrabold text-blue-700 mt-0.5">{{ $account->account_number }}</h3>
                        <p class="text-xs font-bold text-slate-800 mt-1">{{ $account->saver?->full_name }}</p>
                        <p class="text-[11px] text-slate-500">NIS: {{ $account->saver?->student_id_number }} &bull; {{ $account->saver?->class_name }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white p-1 rounded-xl shadow border border-slate-200 shrink-0">
                        <img src="{{ $qrCodeDataUri }}" alt="QR" class="w-full h-full object-contain">
                    </div>
                </div>

                <!-- Balances Breakdown -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Total Saldo:</span>
                        <span class="font-mono font-extrabold text-slate-900 text-sm">Rp {{ number_format($account->balance, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Saldo Mengendap (Holding):</span>
                        <span class="font-mono font-semibold text-slate-700">Rp 10.000</span>
                    </div>
                    <div class="flex justify-between items-center pt-1.5 border-t border-slate-200/70 text-emerald-700 font-bold">
                        <span>Dapat Ditarik (Available):</span>
                        <span class="font-mono font-extrabold">Rp {{ number_format(max(0, $account->balance - 10000), 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Status Update Form -->
                <form method="POST" action="{{ route('admin.accounts.status', $account->id) }}" class="pt-2 border-t border-slate-100 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Status Keaktifan Rekening</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            <option value="active" {{ $account->status === 'active' ? 'selected' : '' }}>Aktif (Normal bertransaksi)</option>
                            <option value="frozen" {{ $account->status === 'frozen' ? 'selected' : '' }}>Dibekukan / Frozen (Kunci transaksi)</option>
                            <option value="dormant" {{ $account->status === 'dormant' ? 'selected' : '' }}>Dormant (Pasif / Tidak aktif lama)</option>
                            <option value="closed" {{ $account->status === 'closed' ? 'selected' : '' }}>Ditutup / Closed (Selesai/Lulus)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Catatan Administrator (Opsional)</label>
                        <input type="text" name="notes" placeholder="Alasan perubahan status..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                    </div>

                    <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                        Perbarui Status Rekening
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Transactions History Ledger -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Log Transaksi Rekening Ini</h4>
                        <p class="text-[10px] text-slate-400">Arsip mutasi debet & kredit siswa</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                                <th class="py-2 px-3">Kwitansi</th>
                                <th class="py-2 px-3">Waktu</th>
                                <th class="py-2 px-3">Tipe</th>
                                <th class="py-2 px-3 text-right">Nominal</th>
                                <th class="py-2 px-3 text-right">Saldo Akhir</th>
                                <th class="py-2 px-3">Teller</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($account->transactions as $t)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-2 px-3 font-mono font-bold text-slate-700 text-[11px]">
                                        {{ $t->receipt_number }}
                                    </td>
                                    <td class="py-2 px-3 text-slate-500 text-[10px]">
                                        {{ $t->transaction_date->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-2 px-3">
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
                                    <td class="py-2 px-3 text-right font-mono font-bold text-[11px] {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 px-3 text-right font-mono font-bold text-slate-800 text-[11px]">
                                        Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 px-3 text-slate-500 text-[10px]">
                                        {{ $t->user?->name ?? 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">
                                        Belum ada riwayat transaksi pada rekening ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
