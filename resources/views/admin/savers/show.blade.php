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
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.savers.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Daftar Nasabah">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Detail Nasabah</span>
                    <span class="text-xs text-slate-300 font-mono">&bull;</span>
                    <span class="text-xs font-mono font-bold text-slate-500">{{ $saver->account_number }}</span>
                </div>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    {{ $saver->full_name }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-left sm:text-right">
                <div class="text-xs font-bold text-slate-800">{{ $saver->student_id_number }} &bull; {{ $saver->class_name }}</div>
                <div class="text-[11px] text-slate-400">{{ $saver->major }}</div>
            </div>

            <div>
                @if($saver->status === 'active')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Dibekukan
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Bento Grid Layout: 2 Kolom Seimbang -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 flex-1 min-h-0">
        
        <!-- KOLOM KIRI: Kartu Digital Siswa & Informasi Akun -->
        <div class="lg:col-span-5 flex flex-col justify-between gap-4">
            
            <!-- 1. KARTU DIGITAL SMART CARD EDUBANK (Klik QR untuk perbesar) -->
            <div class="card-printable relative w-full aspect-[1.586/1] rounded-[24px] bg-gradient-to-tr from-slate-950 via-slate-900 to-indigo-950 p-5 text-white shadow-xl shadow-slate-900/15 overflow-hidden flex flex-col justify-between border border-slate-700/60 shrink-0">
                <!-- Glossy Texture / Ambient Glow -->
                <div class="absolute -right-16 -top-16 w-56 h-56 bg-gradient-to-br from-blue-500/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-56 h-56 bg-gradient-to-tr from-indigo-500/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>

                <!-- Card Header -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Edubank Logo" class="w-9 h-9 object-contain drop-shadow-sm">
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

                <!-- Account Number (Monospace) -->
                <div class="relative z-10 my-auto text-center py-1">
                    <p class="text-[9px] uppercase tracking-widest text-slate-400 font-semibold mb-0.5">Nomor Rekening Tabungan</p>
                    <p class="text-lg sm:text-xl font-mono font-bold tracking-widest text-blue-300 drop-shadow-sm">
                        {{ $saver->account_number }}
                    </p>
                </div>

                <!-- Card Footer (Data Siswa & QR Code Interaktif) -->
                <div class="relative z-10 flex items-end justify-between border-t border-slate-800/80 pt-3">
                    <div>
                        <p class="text-sm font-bold text-white uppercase tracking-tight">{{ $saver->full_name }}</p>
                        <p class="text-[10px] text-slate-300">NIS: <span class="font-mono text-slate-200">{{ $saver->student_id_number }}</span> | {{ $saver->class_name }}</p>
                        <p class="text-[9px] text-slate-400 font-medium mt-0.5">{{ $saver->major }} &bull; Angkatan {{ $saver->cohort }}</p>
                    </div>

                    <!-- Clean QR Code with Click-to-Zoom Button -->
                    <button type="button" 
                            onclick="openQrModal()"
                            class="group relative w-16 h-16 bg-white p-1 rounded-xl shadow-md shrink-0 flex items-center justify-center cursor-pointer hover:scale-105 active:scale-95 transition-all" 
                            title="Klik untuk memperbesar QR Code">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code Rekening" class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-slate-900/15 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-900 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                        </div>
                    </button>
                </div>
            </div>

            <!-- 2. INFORMASI AKUN & STATUS KELOLAAN -->
            <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between text-xs min-h-[200px]">
                <div>
                    <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Informasi Akun</h5>
                    
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Status Nasabah:</span>
                            @if($saver->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Nonaktif / Rekening Dibekukan
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Email Login Siswa:</span>
                            <span class="font-mono font-semibold text-slate-800">{{ $saver->user?->email ?? '-' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Nomor Induk Siswa:</span>
                            <span class="font-mono font-semibold text-slate-800">{{ $saver->student_id_number }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-500">Tanggal Pembukaan:</span>
                            <span class="font-semibold text-slate-700">{{ $saver->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- KOLOM KANAN: Single Balance Card & Riwayat Mutasi Sepanjang Sidebar -->
        <div class="lg:col-span-7 flex flex-col justify-between gap-4 h-full min-h-0">
            
            <!-- 1. SINGLE BALANCE CARD (Sesuai Permintaan: Hanya card saldo) -->
            <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold text-slate-400 tracking-wide">Total Saldo Simpanan Siswa</span>
                    <div class="mt-1 flex items-baseline gap-2">
                        <span class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 font-mono">
                            Rp {{ number_format($saver->balance, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Tombol Nonaktifkan / Aktifkan Nasabah -->
                <div class="flex items-center gap-2 shrink-0">
                    @if($saver->status === 'active')
                        <button type="button" 
                                onclick="openToggleSaverModal()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 text-xs font-bold transition-all shadow-xs cursor-pointer" 
                                title="Nonaktifkan nasabah dan bekukan rekening">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            <span>Nonaktifkan Nasabah</span>
                        </button>
                    @else
                        <button type="button" 
                                onclick="openToggleSaverModal()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/80 text-xs font-bold transition-all shadow-xs cursor-pointer" 
                                title="Aktifkan kembali nasabah dan rekening">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Aktifkan Kembali</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- 2. RIWAYAT MUTASI TRANSAKSI (Memanjang Sejajar Bawah Sidebar) -->
            <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[360px]">
                
                <div class="flex-1 flex flex-col min-h-0">
                    <!-- Table Title -->
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Riwayat Mutasi Transaksi</h4>
                    </div>

                    <!-- Scrollable Table -->
                    <div class="flex-1 overflow-x-auto mt-2">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                                    <th class="py-2.5 px-3 font-medium">No. Kwitansi</th>
                                    <th class="py-2.5 px-3 font-medium">Waktu</th>
                                    <th class="py-2.5 px-3 font-medium">Jenis</th>
                                    <th class="py-2.5 px-3 font-medium text-right">Nominal</th>
                                    <th class="py-2.5 px-3 font-medium text-right">Saldo Setelahnya</th>
                                    <th class="py-2.5 px-3 font-medium">Teller</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($transactions as $t)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-2.5 px-3 font-mono font-semibold text-slate-700 text-xs">
                                            {{ $t->receipt_number }}
                                        </td>
                                        <td class="py-2.5 px-3 text-slate-500 font-mono text-[11px]">
                                            {{ $t->transaction_date ? $t->transaction_date->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td class="py-2.5 px-3">
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
                                        <td class="py-2.5 px-3 text-right font-mono font-bold text-xs {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-800 text-xs">
                                            Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 px-3 text-slate-600 text-xs">
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

<!-- ========================================================================= -->
<!-- MODAL: QR Code Rekening Ukuran Besar & Elegan                             -->
<!-- ========================================================================= -->
<div id="qrModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4" onclick="if(event.target === this) closeQrModal()">
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 shadow-2xl border border-slate-200/80 text-center animate-fade-in relative">
        <button onclick="closeQrModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">&times;</button>
        
        <!-- Header Identitas Bank Mini -->
        <div class="flex items-center justify-center gap-2 mb-4">
            <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Edubank SMKN 1 Bantul</span>
        </div>

        <!-- Big QR Code Frame -->
        <div class="w-64 h-64 mx-auto p-4 bg-white rounded-2xl border border-slate-200/80 shadow-inner flex items-center justify-center">
            <img src="{{ $qrCodeDataUri }}" alt="QR Code Rekening" class="w-full h-full object-contain">
        </div>

        <!-- Student & Account Details -->
        <div class="mt-4 space-y-0.5">
            <p class="text-base font-bold text-slate-900 uppercase tracking-tight">{{ $saver->full_name }}</p>
            <p class="text-sm font-mono font-bold text-blue-600">{{ $saver->account_number }}</p>
            <p class="text-xs text-slate-500 font-medium">{{ $saver->class_name }} &bull; NIS: {{ $saver->student_id_number }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 mt-5">
            <button type="button" onclick="window.print()" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Kartu</span>
            </button>
            <button type="button" onclick="closeQrModal()" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Status Nasabah (Centered Backdrop Blur) -->
<div id="confirmToggleSaverModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeToggleSaverModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl {{ $saver->status === 'active' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center shrink-0">
                    @if($saver->status === 'active')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                <h3 class="text-sm font-bold text-slate-800">
                    {{ $saver->status === 'active' ? 'Konfirmasi Nonaktifkan Nasabah' : 'Konfirmasi Aktifkan Nasabah' }}
                </h3>
            </div>
            <button type="button" onclick="closeToggleSaverModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            @if($saver->status === 'active')
                Apakah Anda yakin ingin menonaktifkan nasabah <strong>{{ $saver->full_name }}</strong>? Rekening tabungan siswa ini otomatis akan dibekukan sementara.
            @else
                Apakah Anda yakin ingin mengaktifkan kembali nasabah <strong>{{ $saver->full_name }}</strong> beserta rekening tabungannya?
            @endif
        </p>

        <form method="POST" action="{{ route('admin.savers.toggle-status', $saver->id) }}" class="pt-2">
            @csrf
            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeToggleSaverModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 {{ $saver->status === 'active' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                    {{ $saver->status === 'active' ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openQrModal() {
        const modal = document.getElementById('qrModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        if (modal) modal.classList.add('hidden');
    }

    function openToggleSaverModal() {
        const modal = document.getElementById('confirmToggleSaverModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeToggleSaverModal() {
        const modal = document.getElementById('confirmToggleSaverModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Keyboard ESC to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQrModal();
            closeToggleSaverModal();
        }
    });
</script>
@endpush
@endsection
