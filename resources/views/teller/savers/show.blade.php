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
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Navigation Card -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3 no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('teller.savers.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Daftar Nasabah">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Loket Teller &bull; Nasabah</span>
                    <span class="text-xs text-slate-300 font-mono">&bull;</span>
                    <span class="text-xs font-mono font-bold text-slate-500">{{ $saver->account_number }}</span>
                </div>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    {{ $saver->full_name }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" 
                    onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Kartu</span>
            </button>
            <a href="{{ route('teller.transactions.create', $saver->id) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow hover:scale-[1.01] active:scale-[0.99]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Mulai Transaksi</span>
            </a>
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
                    <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Informasi Akun Tabungan</h5>
                    
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Total Saldo Rekening</span>
                            <span class="font-mono font-extrabold text-blue-700 text-sm">
                                Rp {{ number_format($saver->balance, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Saldo Mengendap (Min)</span>
                            <span class="font-mono font-semibold text-slate-700">Rp 10.000</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Maksimal Tarik Tunai</span>
                            <span class="font-mono font-bold text-emerald-600">
                                Rp {{ number_format(max(0, $saver->balance - 10000), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Status Rekening</span>
                            @if($saver->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    {{ ucfirst($saver->status) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Akun Mobile Terdaftar</span>
                            <span class="font-medium text-slate-800">{{ $saver->user?->email ?? 'Belum ada' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-400">Tanggal Buka Rekening</span>
                            <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($saver->joined_date)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Dioperasikan oleh Teller</span>
                    <span class="font-mono font-semibold text-slate-500">{{ Auth::user()->name }}</span>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: Riwayat Mutasi Rekening Nasabah -->
        <div class="lg:col-span-7 bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex flex-col justify-between min-h-[420px]">
            <div class="flex-1 flex flex-col min-h-0">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Mutasi Tabungan</h3>
                        <p class="text-[11px] text-slate-400">Semua catatan setoran dan penarikan yang pernah dilakukan</p>
                    </div>
                </div>

                <div class="flex-1 overflow-x-auto mt-2">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                                <th class="py-2.5 px-3">Kode / Waktu</th>
                                <th class="py-2.5 px-3">Jenis Transaksi</th>
                                <th class="py-2.5 px-3 text-right">Nominal</th>
                                <th class="py-2.5 px-3 text-right">Saldo Sesudah</th>
                                <th class="py-2.5 px-3 text-right">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($transactions as $t)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-2.5 px-3">
                                        <div class="font-mono font-bold text-slate-800 text-[11px]">{{ $t->transaction_code }}</div>
                                        <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($t->transaction_date)->translatedFormat('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if($t->transaction_type === 'deposit')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/80">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                                Setoran
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200/80">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                                Penarikan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-semibold text-slate-700">
                                        Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <a href="{{ route('teller.transactions.receipt', $t->id) }}" 
                                           class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-colors inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            <span>Struk</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                                        Belum ada riwayat transaksi pada rekening ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 shrink-0">
                <span>Total: {{ $transactions->total() }} mutasi</span>
                <div>
                    {{ $transactions->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal Zoom QR Code -->
<div id="qrModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs cursor-pointer"
     onclick="if(event.target === this) closeQrModal()">
    <div class="bg-white rounded-[24px] max-w-sm w-full p-6 text-center space-y-4 shadow-2xl relative" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">QR Code Kartu Siswa</h4>
                <p class="text-[11px] text-slate-400 font-mono">{{ $saver->account_number }}</p>
            </div>
            <button type="button" onclick="closeQrModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-center">
            <img src="{{ $qrCodeDataUri }}" alt="QR Code" class="w-56 h-56 object-contain">
        </div>

        <div>
            <p class="text-sm font-bold text-slate-800">{{ $saver->full_name }}</p>
            <p class="text-xs text-slate-500">{{ $saver->student_id_number }} &bull; {{ $saver->class_name }}</p>
        </div>

        <button type="button" onclick="closeQrModal()" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
            Tutup
        </button>
    </div>
</div>

<script>
    function openQrModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
