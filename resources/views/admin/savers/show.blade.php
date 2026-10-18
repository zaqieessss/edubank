@extends('layouts.app')

@section('title', 'Detail Tabungan & Kartu Siswa: ' . $saver->full_name)
@section('page_title', 'Kartu & Detail Tabungan')

@push('styles')
<style>
    @media print {
        body {
            background: white !important;
            padding: 0 !important;
        }
        aside, header, .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
        .card-printable {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            margin: 0 auto !important;
            page-break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-4 pb-8">

    <!-- Top Action Toolbar -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('admin.savers.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Nasabah</span>
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold shadow-sm transition-all hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Kartu Tabungan Siswa</span>
            </button>
        </div>
    </div>

    <!-- Bento Grid Layout: 2 Columns (Left: Digital Card & Account Specs, Right: Balance & Mutations) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        
        <!-- Kolom Kiri: DIGITAL STUDENT SAVINGS CARD (Card Holographic Modern) -->
        <div class="lg:col-span-5 space-y-4">
            
            <!-- THE PRINTABLE DIGITAL SMART CARD -->
            <div class="card-printable relative w-full aspect-[1.586/1] rounded-[24px] bg-gradient-to-tr from-slate-950 via-slate-900 to-indigo-950 p-5 text-white shadow-xl shadow-slate-900/15 overflow-hidden flex flex-col justify-between border border-slate-700/60">
                <!-- Glossy Texture / Overlay -->
                <div class="absolute -right-16 -top-16 w-56 h-56 bg-gradient-to-br from-blue-500/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-56 h-56 bg-gradient-to-tr from-indigo-500/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>

                <!-- Card Header -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-extrabold tracking-wider uppercase text-white">EDUBANK MINI</h4>
                            <p class="text-[9px] text-slate-400">SMK Negeri 1 Bantul</p>
                        </div>
                    </div>
                    <!-- Chip Simulation -->
                    <div class="w-9 h-7 rounded-md bg-gradient-to-r from-amber-300 via-amber-400 to-amber-200 border border-amber-500/60 flex items-center justify-center shadow-inner">
                        <div class="w-6 h-4 border border-amber-600/40 rounded-xs flex items-center justify-center">
                            <div class="w-3 h-2 border-t border-b border-amber-600/40"></div>
                        </div>
                    </div>
                </div>

                <!-- Account Number (Large Monospace) -->
                <div class="relative z-10 my-auto text-center py-2">
                    <p class="text-[9px] uppercase tracking-widest text-slate-400 font-semibold mb-0.5">Nomor Rekening Tabungan</p>
                    <p class="text-lg sm:text-xl font-mono font-bold tracking-widest text-blue-300 drop-shadow-sm">
                        {{ $saver->account_number }}
                    </p>
                </div>

                <!-- Card Footer (Student Details & QR Code) -->
                <div class="relative z-10 flex items-end justify-between border-t border-slate-800/80 pt-3">
                    <div>
                        <p class="text-sm font-bold text-white uppercase tracking-tight">{{ $saver->full_name }}</p>
                        <p class="text-[10px] text-slate-300">NIS: <span class="font-mono text-slate-200">{{ $saver->student_id_number }}</span> | {{ $saver->class_name }}</p>
                        <p class="text-[9px] text-slate-400 font-medium mt-0.5">{{ $saver->major }} &bull; Angkatan {{ $saver->cohort }}</p>
                    </div>

                    <!-- Clean QR Code with crisp border -->
                    <div class="w-16 h-16 bg-white p-1 rounded-xl shadow-md shrink-0 flex items-center justify-center">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code Rekening" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>

            <!-- Bento Account Metadata -->
            <div class="bg-white rounded-[24px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] space-y-3 text-xs">
                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Informasi Akun & Login</h5>
                
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Email Akun Login:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $saver->user?->email ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Nomor Induk Siswa (NIS):</span>
                    <span class="font-mono font-bold text-slate-800">{{ $saver->student_id_number }}</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Status Rekening:</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ ucfirst($saver->account?->status ?? 'active') }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-1.5">
                    <span class="text-slate-500">Tanggal Buka Rekening:</span>
                    <span class="font-medium text-slate-700">{{ $saver->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Balance Overview & Transaction Ledger -->
        <div class="lg:col-span-7 space-y-4">
            
            <!-- Balance Bento Stats (3 Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Total Balance -->
                <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)]">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Saldo Simpanan</p>
                    <h3 class="text-lg font-extrabold text-blue-700 mt-1 font-mono">
                        Rp {{ number_format($saver->balance, 0, ',', '.') }}
                    </h3>
                    <span class="text-[10px] text-slate-500">Tercatat di pembukuan</span>
                </div>

                <!-- Saldo Minimum Mengendap -->
                <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)]">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Saldo Mengendap</p>
                    <h3 class="text-lg font-extrabold text-slate-800 mt-1 font-mono">
                        Rp 10.000
                    </h3>
                    <span class="text-[10px] text-indigo-600 font-semibold">Aturan holding minimum</span>
                </div>

                <!-- Saldo Yang Bisa Ditarik -->
                <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)]">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Dapat Ditarik Tunai</p>
                    <h3 class="text-lg font-extrabold text-emerald-600 mt-1 font-mono">
                        Rp {{ number_format(max(0, $saver->balance - 10000), 0, ',', '.') }}
                    </h3>
                    <span class="text-[10px] text-emerald-600 font-medium">Batas penarikan saat ini</span>
                </div>
            </div>

            <!-- Recent Transaction History Bento Table -->
            <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Riwayat Mutasi Transaksi Siswa</h4>
                        <p class="text-[10px] text-slate-400">Buku kas simpanan & penarikan digital</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                                <th class="py-2 px-3">No. Kwitansi</th>
                                <th class="py-2 px-3">Waktu</th>
                                <th class="py-2 px-3">Jenis</th>
                                <th class="py-2 px-3 text-right">Nominal</th>
                                <th class="py-2 px-3 text-right">Saldo Setelahnya</th>
                                <th class="py-2 px-3">Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($saver->transactions as $t)
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
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                                Setoran
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
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
                                    <td colspan="6" class="py-6 text-center text-slate-400">
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
