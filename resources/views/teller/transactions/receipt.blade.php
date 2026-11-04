@extends('layouts.app')

@section('title', 'Struk Transaksi: ' . $transaction->receipt_number)
@section('page_title', 'Bukti Transaksi Teller')

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
        .receipt-card {
            box-shadow: none !important;
            border: 1px dashed #94a3b8 !important;
            margin: 0 auto !important;
            width: 80mm !important;
            max-width: 80mm !important;
            padding: 10px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-md mx-auto space-y-4 pb-8">

    <!-- Top Action Card (Konsisten & Rapi Terbungkus Card) -->
    <div class="bg-white rounded-[24px] p-4 sm:p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-wrap items-center justify-between gap-3 no-print">
        <a href="{{ route('teller.transactions.create', $transaction->saver_id) }}" 
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors cursor-pointer shrink-0"
           title="Kembali ke Form Transaksi Siswa Ini">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Transaksi</span>
        </a>
        
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('teller.transactions.scanner') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 text-xs font-bold transition-all shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span class="hidden sm:inline">Scan</span> Siswa Lain
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 sm:px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow cursor-pointer shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Thermal Struk</span>
            </button>
        </div>
    </div>

    <!-- THERMAL RECEIPT SLIP (Compact & Clean) -->
    <div class="receipt-card bg-white rounded-[24px] p-6 border border-slate-200/90 shadow-[0_4px_30px_-5px_rgba(15,23,42,0.05)] text-slate-900 font-mono text-xs space-y-3.5">
        
        <!-- School & Bank Header -->
        <div class="text-center border-b border-dashed border-slate-300 pb-3 space-y-1">
            <div class="flex justify-center mb-1.5">
                <img src="{{ asset('images/logo-blue.png') }}" alt="Logo Edubank" class="w-11 h-11 object-contain mx-auto">
            </div>
            <h3 class="font-extrabold text-sm tracking-wider uppercase font-sans">BANK MINI EDUBANK</h3>
            <p class="text-[11px] font-sans font-semibold text-slate-700">SMK NEGERI 1 BANTUL</p>
            <p class="text-[10px] text-slate-400 font-sans">Jl. Parangtritis No.KM.11, Sabdodadi, Bantul, DIY</p>
            <div class="pt-1">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold font-sans {{ $transaction->transaction_type === 'deposit' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    BUKTI {{ $transaction->transaction_type === 'deposit' ? 'SETORAN TUNAI' : 'PENARIKAN TUNAI' }}
                </span>
            </div>
        </div>

        <!-- Metadata Info -->
        <div class="space-y-1 text-[11px]">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Kwitansi:</span>
                <span class="font-bold text-slate-900">{{ $transaction->receipt_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tanggal/Waktu:</span>
                <span>{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Petugas Teller:</span>
                <span>{{ $transaction->user?->name ?? 'Teller 01' }}</span>
            </div>
        </div>

        <!-- Student Account Details -->
        <div class="border-t border-dashed border-slate-300 pt-2 space-y-1 text-[11px]">
            <div class="flex justify-between">
                <span class="text-slate-500">No. Rekening:</span>
                <span class="font-bold text-blue-700">{{ $transaction->saver?->account_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nama Nasabah:</span>
                <span class="font-bold text-slate-900">{{ $transaction->saver?->full_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Kelas / NIS:</span>
                <span>{{ $transaction->saver?->class_name }} ({{ $transaction->saver?->student_id_number }})</span>
            </div>
        </div>

        <!-- Transaction Financial Figures -->
        <div class="border-t border-b border-dashed border-slate-300 py-2.5 space-y-1.5">
            <div class="flex justify-between text-xs font-extrabold">
                <span class="uppercase">Nominal {{ $transaction->transaction_type === 'deposit' ? 'Setor' : 'Tarik' }}:</span>
                <span class="text-sm {{ $transaction->transaction_type === 'deposit' ? 'text-emerald-700' : 'text-rose-700' }}">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between text-[11px] text-slate-500">
                <span>Saldo Sebelumnya:</span>
                <span>Rp {{ number_format($transaction->balance_before, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-xs font-bold pt-1 border-t border-slate-200">
                <span>Saldo Terakhir:</span>
                <span class="font-extrabold text-slate-900">Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="text-[10px] text-slate-500">
            <span>Keterangan: {{ $transaction->description ?? '-' }}</span>
        </div>

        <!-- Dual Signature Section -->
        <div class="pt-4 grid grid-cols-2 gap-4 text-center text-[10px] font-sans">
            <div>
                <p class="text-slate-500 mb-8">Nasabah (Siswa)</p>
                <div class="border-t border-slate-300 pt-1 font-semibold text-slate-800">
                    {{ $transaction->saver?->full_name }}
                </div>
            </div>
            <div>
                <p class="text-slate-500 mb-8">Petugas Teller</p>
                <div class="border-t border-slate-300 pt-1 font-semibold text-slate-800">
                    {{ $transaction->user?->name ?? 'Teller Bank' }}
                </div>
            </div>
        </div>

        <!-- Footer Thank You Note -->
        <div class="text-center pt-2 text-[9px] text-slate-400 font-sans border-t border-dashed border-slate-200">
            Simpan struk ini sebagai bukti transaksi resmi Bank Mini Edubank SMKN 1 Bantul. Terima kasih.
        </div>
    </div>

    <!-- Bottom Quick Action Card (no-print) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] space-y-3 no-print">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-800">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Transaksi Selesai & Berhasil Disimpan</span>
            </div>
            <span class="text-[10px] text-slate-400 font-mono">{{ $transaction->receipt_number }}</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
            <a href="{{ route('teller.transactions.create', $transaction->saver_id) }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Loket Siswa Ini</span>
            </a>
            <a href="{{ route('teller.transactions.scanner') }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-xs transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>Selesai & Scan Siswa Baru</span>
            </a>
        </div>
    </div>
</div>
@endsection
