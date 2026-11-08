@extends('layouts.app')

@section('title', 'Dashboard Teller - Loket Pelayanan')
@section('page_title', 'Dashboard Loket')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-3 md:gap-4 pb-2">

    <!-- ========================================================================= -->
    <!-- 1. WELCOME HERO CARD (Serasi dengan Dashboard Admin)                      -->
    <!-- ========================================================================= -->
    <div class="relative w-full rounded-[24px] bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white p-5 md:p-6 shadow-sm border border-slate-800/60 overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
        <!-- Ambient Glow -->
        <div class="absolute -left-10 -top-10 w-44 h-44 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <h2 class="text-xl md:text-2xl font-bold tracking-tight text-white">
                Pelayanan Transaksi Loket
            </h2>
            <p class="text-xs text-slate-300 max-w-xl leading-relaxed">
                Pusat operasional teller perbankan mini sekolah. Scan QR kartu siswa atau cari rekening untuk melayani setoran dan penarikan.
            </p>
        </div>

        <!-- Tombol Scan QR & Cari Rekening -->
        <div class="relative z-10 flex flex-wrap items-center gap-2.5 shrink-0">
            @if(isset($todayClosing) && $todayClosing)
                <!-- Status Loket Tutup Buku -->
                <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500/20 border border-amber-400/30 text-amber-200 text-xs font-semibold backdrop-blur-md">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Kas Hari Ini Ditutup</span>
                </div>
                <!-- Tombol Disabled yang memicu Modal Error Elegan -->
                <button type="button" 
                        onclick="showClosedModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-700/80 hover:bg-slate-700 text-slate-300 text-xs font-semibold shadow-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Scan QR Siswa</span>
                </button>
                <button type="button" 
                        onclick="showClosedModal()"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-700/80 hover:bg-slate-700 text-slate-300 text-xs font-semibold shadow-xs transition-all cursor-pointer">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>Buka Rekening</span>
                </button>
            @else
                <!-- Tombol Buka Rekening Baru -->
                <a href="{{ route('teller.savers.create') }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-white text-xs font-semibold backdrop-blur-md transition-all hover:scale-[1.01] active:scale-[0.99]">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>Buka Rekening</span>
                </a>

                <!-- Tombol Scan QR Utama -->
                <a href="{{ route('teller.transactions.scanner') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-md shadow-blue-600/30 transition-all hover:scale-[1.01] active:scale-[0.99]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Scan QR Siswa</span>
                </a>

                <!-- Quick Search Form -->
                <form method="POST" action="{{ route('teller.transactions.search') }}" class="flex items-center bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/15 rounded-xl px-3 py-1.5 text-xs transition-all focus-within:bg-white focus-within:text-slate-900">
                    @csrf
                    <svg class="w-3.5 h-3.5 text-slate-300 mr-2 shrink-0 group-focus:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="query" placeholder="Ketik No. Rekening / NIS..." required class="bg-transparent border-none outline-none text-xs w-44 md:w-52 text-white placeholder-slate-300 focus:text-slate-900 focus:placeholder-slate-400 p-0 font-medium">
                    <button type="submit" class="ml-2 px-2.5 py-1 bg-white/20 hover:bg-white/30 text-white rounded-lg text-[10px] font-bold shrink-0 transition-colors">
                        Cari
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 1.5. BANNER PERINGATAN MODAL AWAL (Jika Belum Diinput Pagi Ini)            -->
    <!-- ========================================================================= -->
    @if(!$summary['has_set_opening'])
        <div class="bg-gradient-to-r from-blue-500/10 via-indigo-500/5 to-transparent border border-blue-300/60 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900">Modal Awal Kas Pagi Belum Diinput</h4>
                    <p class="text-[11px] text-slate-600">Berapa uang kas fisik yang diberikan guru pembimbing untuk modal kerja laci loket Anda pagi ini?</p>
                </div>
            </div>
            <button type="button" onclick="openOpeningModal()" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 transition-all shrink-0 cursor-pointer">
                + Input Modal Awal Kas
            </button>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 2. TIGA KARTU INFORMASI LOKET (Kas Laci, Mutasi Setor/Tarik, Transaksi)    -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 shrink-0">
        
        <!-- Kartu 1: Saldo Kas Fisik Laci (Buku) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 tracking-wide">
                    Saldo Kas Laci Hari Ini
                </span>
                <button type="button" 
                        onclick="openOpeningModal()" 
                        class="text-[10px] font-bold px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition-colors cursor-pointer">
                    {{ $summary['has_set_opening'] ? 'Ubah Modal' : '+ Modal Awal' }}
                </button>
            </div>
            <div class="py-1.5">
                <p class="text-2xl md:text-3xl font-extrabold text-blue-700 font-mono tracking-tight">
                    Rp {{ number_format($summary['closing_cash'], 0, ',', '.') }}
                </p>
                <div class="text-[11px] text-slate-500 flex items-center gap-1.5 mt-1">
                    <span class="text-slate-400">Modal Guru:</span>
                    <strong class="font-mono text-slate-700">Rp {{ number_format($summary['opening_balance'], 0, ',', '.') }}</strong>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400 font-medium pt-1.5 border-t border-slate-100">
                <span>{{ \Carbon\Carbon::parse($summary['date'])->translatedFormat('d F Y') }}</span>
                <a href="{{ route('teller.daily-closings.create') }}" class="font-bold text-amber-600 hover:text-amber-700">Tutup Kas &rarr;</a>
            </div>
        </div>

        <!-- Kartu 2: Uang Masuk & Keluar Hari Ini -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Arus Kas Masuk & Keluar
            </span>
            <div class="py-2 flex items-baseline gap-4">
                <div>
                    <span class="text-[10px] text-emerald-600 font-bold block uppercase">Setoran Masuk</span>
                    <p class="text-lg md:text-xl font-extrabold text-emerald-700 font-mono">
                        +Rp {{ number_format($summary['total_deposits'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-[10px] text-rose-500 font-bold block uppercase">Penarikan Keluar</span>
                    <p class="text-lg md:text-xl font-extrabold text-slate-800 font-mono">
                        -Rp {{ number_format($summary['total_withdrawals'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Total mutasi loket Anda hari ini
            </span>
        </div>

        <!-- Kartu 3: Total Transaksi Dilayani -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Pelayanan Hari Ini
            </span>
            <div class="py-2">
                <p class="text-2xl md:text-3xl font-extrabold text-slate-900 font-mono tracking-tight">
                    {{ number_format($summary['transaction_count'], 0, ',', '.') }}
                </p>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Siswa telah dilayani di meja loket
            </span>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 3. TABEL TRANSAKSI TERAKHIR (Memanjang Elegan Sejajar Bawah Sidebar)       -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[380px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3 shrink-0">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">
                        Aktivitas Transaksi Terakhir di Loket
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                        Daftar transaksi siswa yang baru saja Anda otorisasi (maksimal 20 transaksi terakhir).
                    </p>
                </div>
                <a href="{{ route('teller.transactions.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Semua &rarr;
                </a>
            </div>

            <!-- Table Wrapper (Anti Overflow & Scrollable Sejajar Bawah Sidebar) -->
            <div class="flex-1 overflow-auto rounded-xl border border-slate-100/80 min-h-0">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="sticky top-0 bg-slate-50/95 backdrop-blur-xs z-10">
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-500 shadow-xs">
                            <th class="py-3 px-3.5 whitespace-nowrap">Waktu</th>
                            <th class="py-3 px-3.5 whitespace-nowrap">No. Kwitansi</th>
                            <th class="py-3 px-3.5 whitespace-nowrap">Nasabah (Siswa)</th>
                            <th class="py-3 px-3.5 whitespace-nowrap">No. Rekening</th>
                            <th class="py-3 px-3.5 whitespace-nowrap">Jenis Transaksi</th>
                            <th class="py-3 px-3.5 whitespace-nowrap text-right">Nominal</th>
                            <th class="py-3 px-3.5 whitespace-nowrap text-right">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTransactions as $t)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3.5 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                    {{ $t->transaction_date ? $t->transaction_date->format('H:i:s') . ' WIB' : '-' }}
                                </td>
                                <td class="py-3 px-3.5 font-mono font-bold text-slate-700 text-xs whitespace-nowrap">
                                    {{ $t->receipt_number }}
                                </td>
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    <div class="font-bold text-slate-800 text-xs">{{ $t->saver?->full_name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">{{ $t->saver?->class_name ?? '' }}</div>
                                </td>
                                <td class="py-3 px-3.5 font-mono font-semibold text-blue-700 whitespace-nowrap">
                                    {{ $t->saver?->account_number ?? '-' }}
                                </td>
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    @if($t->transaction_type === 'deposit')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Setoran
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Penarikan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3.5 text-right font-mono font-bold text-xs whitespace-nowrap {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-slate-800' }}">
                                    {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                    <a href="{{ route('teller.transactions.receipt', $t->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-colors">
                                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Cetak</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-slate-400 font-medium">
                                    Belum ada aktivitas transaksi tabungan di loket Anda hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subtle Footer Alignment -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 mt-3 shrink-0">
            <div>
                Menampilkan <span class="font-bold text-slate-700">{{ $recentTransactions->count() }}</span> data transaksi terakhir di loket
            </div>
            <span class="font-mono">Edubank SMKN 1 Bantul</span>
        </div>

    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL: Notifikasi / Alert Error Kas Ditutup (Bento Style)                 -->
<!-- ========================================================================= -->
<div id="closedModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
     onclick="if(event.target === this) closeClosedModal()">
    <div class="bg-white rounded-[24px] max-w-sm w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 text-center">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900">
                Kas Hari Ini Telah Ditutup
            </h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                Anda sudah menyelesaikan penutupan kas harian (Daily Closing). Sesuai SOP perbankan, loket tidak diperkenankan melayani transaksi setoran atau penarikan baru sampai hari kerja berikutnya.
            </p>
        </div>
        <div class="pt-2">
            <button type="button" 
                    onclick="closeClosedModal()" 
                    class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all cursor-pointer shadow-xs">
                Mengerti
            </button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Input Modal Awal Kas Hari Ini dari Guru Pembimbing                 -->
<!-- ========================================================================= -->
<div id="openingModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs" 
     onclick="if(event.target === this) closeOpeningModal()">
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Modal Kas dari Guru Pembimbing</h3>
            </div>
            <button type="button" onclick="closeOpeningModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm cursor-pointer">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('teller.daily-closings.opening-balance') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Jumlah Modal Kas Pagi Ini (Rp) *
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                    <input type="text"
                           inputmode="numeric"
                           data-currency="true"
                           name="opening_balance"
                           id="openingBalanceModalInput"
                           value="{{ old('opening_balance', $summary['opening_balance'] > 0 ? number_format($summary['opening_balance'], 0, ',', '.') : '') }}"
                           placeholder="Contoh: 200.000"
                           required
                           class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl pl-12 pr-4 py-3 text-lg font-mono font-extrabold text-blue-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Uang modal fisik yang diserahkan guru pembimbing pagi hari untuk melayani nasabah jika ada yang menarik uang.</p>
            </div>

            <!-- Quick Preset Chips -->
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">Pilihan Cepat:</span>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="setPresetOpening(50000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-mono font-bold text-slate-700 cursor-pointer">Rp 50.000</button>
                    <button type="button" onclick="setPresetOpening(100000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-mono font-bold text-slate-700 cursor-pointer">Rp 100.000</button>
                    <button type="button" onclick="setPresetOpening(200000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-mono font-bold text-slate-700 cursor-pointer">Rp 200.000</button>
                    <button type="button" onclick="setPresetOpening(500000)" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[11px] font-mono font-bold text-slate-700 cursor-pointer">Rp 500.000</button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeOpeningModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all cursor-pointer">
                    Simpan Modal Kas
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openOpeningModal() {
        const modal = document.getElementById('openingModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            const input = document.getElementById('openingBalanceModalInput');
            if (input) setTimeout(() => input.focus(), 50);
        }
    }

    function closeOpeningModal() {
        const modal = document.getElementById('openingModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function setPresetOpening(amount) {
        const input = document.getElementById('openingBalanceModalInput');
        if (input) {
            input.value = new Intl.NumberFormat('id-ID').format(amount);
            input.dispatchEvent(new Event('input'));
        }
    }

    function showClosedModal() {
        const modal = document.getElementById('closedModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeClosedModal() {
        const modal = document.getElementById('closedModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    @if(session('daily_closed_error'))
        document.addEventListener('DOMContentLoaded', function () {
            showClosedModal();
        });
    @endif
</script>
@endpush
@endsection
