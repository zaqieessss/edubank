@extends('layouts.app')

@section('title', 'Kelola Rekening: ' . $account->account_number)
@section('page_title', 'Kelola Rekening')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.accounts.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Daftar Rekening">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kelola Rekening</span>
                    <span class="text-xs text-slate-300 font-mono">&bull;</span>
                    <span class="text-xs font-mono font-bold text-slate-500">{{ $account->account_number }}</span>
                </div>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    {{ $account->saver?->full_name }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-left sm:text-right">
                <div class="text-xs font-bold text-slate-800">{{ $account->saver?->student_id_number }} &bull; {{ $account->saver?->class_name }}</div>
                <div class="text-[11px] text-slate-400">{{ $account->saver?->major }}</div>
            </div>

            <div>
                @if($account->status === 'active')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                @elseif($account->status === 'frozen')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Dibekukan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ ucfirst($account->status) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Bento Grid (2 Kolom Memenuhi Layar & Sejajar Sidebar) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 flex-1 min-h-0">
        
        <!-- KOLOM KIRI (5 Kolom): Saldo & Kontrol Status Rekening -->
        <div class="lg:col-span-5 flex flex-col justify-between gap-4">
            
            <!-- Card 1: Saldo Rekening (Minimalis, Tanpa Teks Mikro) -->
            <div class="bg-white rounded-[24px] p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <span class="text-xs font-semibold text-slate-400">Total Saldo Rekening</span>
                        <a href="{{ route('admin.savers.show', $account->saver_id) }}" 
                           class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                            Lihat Profil Nasabah &rarr;
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl lg:text-4xl font-mono font-extrabold text-slate-900">
                            Rp {{ number_format($account->balance, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-6 mt-6 border-t border-slate-100">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block">Saldo Mengendap</span>
                        <p class="text-base font-mono font-bold text-slate-700 mt-1">Rp 10.000</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block">Dapat Ditarik</span>
                        <p class="text-base font-mono font-bold text-emerald-700 mt-1">
                            Rp {{ number_format(max(0, $account->balance - 10000), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Pengaturan Status Keaktifan Rekening -->
            <div class="bg-white rounded-[24px] p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between">
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        Perbarui Status Rekening
                    </h3>

                    <form method="POST" action="{{ route('admin.accounts.status', $account->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Rekening</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                                <option value="active" {{ $account->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="frozen" {{ $account->status === 'frozen' ? 'selected' : '' }}>Dibekukan</option>
                                <option value="dormant" {{ $account->status === 'dormant' ? 'selected' : '' }}>Dormant (Pasif)</option>
                                <option value="closed" {{ $account->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Perubahan (Opsional)</label>
                            <input type="text" 
                                   name="notes" 
                                   placeholder="Tuliskan alasan perubahan status rekening..." 
                                   class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                            Simpan Perubahan Status
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (7 Kolom): Riwayat Mutasi / Transaksi (Sepanjang Sidebar) -->
        <div class="lg:col-span-7 flex flex-col h-full">
            <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
                
                <div class="flex-1 flex flex-col min-h-0">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Riwayat Mutasi Transaksi
                        </h3>
                    </div>

                    <!-- Table Container -->
                    <div class="flex-1 overflow-x-auto mt-2">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                                    <th class="py-2.5 px-3 font-medium">No. Kwitansi</th>
                                    <th class="py-2.5 px-3 font-medium">Tanggal</th>
                                    <th class="py-2.5 px-3 font-medium">Jenis</th>
                                    <th class="py-2.5 px-3 font-medium text-right">Nominal</th>
                                    <th class="py-2.5 px-3 font-medium text-right">Saldo Setelahnya</th>
                                    <th class="py-2.5 px-3 font-medium">Petugas Teller</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($transactions as $t)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3 px-3 font-mono font-semibold text-slate-700 text-xs">
                                            {{ $t->receipt_number }}
                                        </td>
                                        <td class="py-3 px-3 text-slate-500 font-mono text-[11px]">
                                            {{ $t->transaction_date ? $t->transaction_date->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="py-3 px-3">
                                            @if($t->transaction_type === 'deposit')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                                    Setoran
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                                    Penarikan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-right font-mono font-bold text-xs {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-3 text-right font-mono font-bold text-slate-800 text-xs">
                                            Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-3 text-slate-600 text-xs">
                                            {{ $t->user?->name ?? 'System' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-16 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center gap-1.5">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                <span class="text-xs font-medium">Belum ada riwayat transaksi pada rekening ini</span>
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
                        Menampilkan <span class="font-bold text-slate-800">{{ $transactions->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $transactions->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $transactions->total() }}</span> transaksi
                    </div>
                    <div>
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
