@extends('layouts.app')

@section('title', 'Laporan Bulanan Transaksi Tabungan')
@section('page_title', 'Laporan Transaksi')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Bento Stats Grid (4 Cards: Total Volume, Setoran Masuk, Penarikan Keluar, Arus Kas Bersih) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">
        
        <!-- Card 1: Total Mutasi Periode Bulan Terpilih -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Total Transaksi</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                    {{ $periodDate->translatedFormat('F Y') }}
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-mono font-extrabold text-slate-900 tracking-tight">
                    {{ number_format($totalTransactions, 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Mutasi</span>
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                {{ $depositCount }} Setor &bull; {{ $withdrawalCount }} Tarik
            </div>
        </div>

        <!-- Card 2: Total Setoran Masuk -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Setoran Masuk (Debet Kas)</span>
                <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
            </div>
            <p class="text-2xl font-mono font-extrabold text-emerald-600 mt-2 tracking-tight">
                +Rp {{ number_format($totalDepositAmount, 0, ',', '.') }}
            </p>
            <div class="text-[11px] text-slate-400 mt-1">
                Total akumulasi dana siswa masuk
            </div>
        </div>

        <!-- Card 3: Total Penarikan Keluar -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Penarikan Keluar (Kredit Kas)</span>
                <div class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
            </div>
            <p class="text-2xl font-mono font-extrabold text-rose-600 mt-2 tracking-tight">
                -Rp {{ number_format($totalWithdrawalAmount, 0, ',', '.') }}
            </p>
            <div class="text-[11px] text-slate-400 mt-1">
                Total pencairan tabungan siswa
            </div>
        </div>

        <!-- Card 4: Arus Kas Bersih (Net Growth) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400">Arus Kas Bersih Bulan Ini</span>
                <span class="w-2 h-2 rounded-full {{ $netCashFlow >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            </div>
            <p class="text-2xl font-mono font-extrabold mt-2 tracking-tight {{ $netCashFlow >= 0 ? 'text-slate-900' : 'text-rose-600' }}">
                {{ $netCashFlow >= 0 ? '+' : '' }}Rp {{ number_format($netCashFlow, 0, ',', '.') }}
            </p>
            <div class="text-[11px] text-slate-400 mt-1">
                {{ $netCashFlow >= 0 ? 'Pertumbuhan saldo positif' : 'Penarikan melebihi setoran' }}
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box (Memanjang Sepanjang Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Judul, Filter Bulan/Tahun, Jenis, Pencarian & Tombol Cetak PDF) -->
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Daftar Transaksi Periode {{ $periodDate->translatedFormat('F Y') }}
                        </h3>
                        <p class="text-[11px] text-slate-400">Pilih periode atau gunakan pencarian untuk memfilter data mutasi.</p>
                    </div>
                </div>

                <!-- Monthly Filter, Search & Print Bar (Berjajar Rapi Satu Baris) -->
                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap items-center gap-2">
                        <!-- Filter Bulan -->
                        <select name="month" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/80 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 font-semibold outline-none focus:bg-white cursor-pointer">
                            @for($m = 1; $m <= 12; $m++)
                                @php $val = sprintf('%02d', $m); @endphp
                                <option value="{{ $val }}" {{ $selectedMonth == $val ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>

                        <!-- Filter Tahun -->
                        <select name="year" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/80 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 font-semibold outline-none focus:bg-white cursor-pointer">
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>

                        <!-- Filter Jenis -->
                        <select name="type" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/80 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white cursor-pointer">
                            <option value="">Semua Jenis</option>
                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Setoran (+)</option>
                            <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Penarikan (-)</option>
                        </select>

                        <!-- Input Pencarian -->
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Ketik kwitansi/nama..." 
                                   class="bg-slate-50 border border-slate-200/80 rounded-xl pl-7 pr-3 py-1.5 text-xs text-slate-700 placeholder-slate-400 focus:bg-white outline-none w-36 sm:w-44 font-medium">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>

                        @if(request()->anyFilled(['type', 'search']) || $selectedMonth != \Carbon\Carbon::now()->format('m') || $selectedYear != \Carbon\Carbon::now()->format('Y'))
                            <a href="{{ route('admin.transactions.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-1 py-1">Reset</a>
                        @endif
                    </form>

                    <!-- Tombol Cetak PDF Bulanan (Berjajar Sejajar) -->
                    <a href="{{ route('admin.transactions.print', ['month' => $selectedMonth, 'year' => $selectedYear, 'type' => request('type')]) }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer shrink-0"
                       title="Cetak atau Simpan Laporan Bulanan ke PDF">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak PDF</span>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3">No. Kwitansi</th>
                            <th class="py-2.5 px-3">Tanggal & Waktu</th>
                            <th class="py-2.5 px-3">Nasabah (Siswa)</th>
                            <th class="py-2.5 px-3">No. Rekening</th>
                            <th class="py-2.5 px-3">Jenis</th>
                            <th class="py-2.5 px-3 text-right">Nominal</th>
                            <th class="py-2.5 px-3 text-right">Saldo Setelahnya</th>
                            <th class="py-2.5 px-3">Teller</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
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
                                    <div class="text-[10px] text-slate-400">{{ $t->saver?->class_name ?? '-' }} &bull; NIS: {{ $t->saver?->student_id_number ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-blue-700 text-[11px]">
                                    {{ $t->saver?->account_number ?? '-' }}
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
                                <td class="py-3 px-3 text-right font-mono font-extrabold text-[12px] {{ $t->transaction_type === 'deposit' ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $t->transaction_type === 'deposit' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-slate-800 text-[11px]">
                                    Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-slate-600 text-[11px]">
                                    <div class="font-medium text-slate-800">{{ $t->user?->name ?? 'System' }}</div>
                                    <div class="text-[10px] text-slate-400 capitalize">{{ $t->user?->role ?? 'Teller' }}</div>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('admin.transactions.show', $t->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-700 rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer"
                                       title="Lihat Detail Transaksi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-9 h-9 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <span class="text-xs font-medium">Tidak ada transaksi ditemukan pada periode {{ $periodDate->translatedFormat('F Y') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer (Pinned at Bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0">
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
