@extends('layouts.app')

@section('title', 'Verifikasi Kas: ' . \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('d F Y'))
@section('page_title', 'Verifikasi & Approval Kas')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 pb-8">

    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.daily-closings.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Rekonsiliasi Kas</span>
        </a>
        <span class="text-xs text-slate-400 font-mono">Closing Ref: #CLS-{{ str_pad($dailyClosing->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>

    <!-- Main Bento Review Card -->
    <div class="bg-white rounded-[28px] p-6 border border-slate-200/80 shadow-[0_4px_30px_-5px_rgba(15,23,42,0.04)] space-y-5">
        
        <!-- Header Status -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold mb-1.5">
                    Laporan Tutup Kas Harian
                </div>
                <h2 class="text-lg font-extrabold text-slate-900">
                    {{ \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('l, d F Y') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Diajukan oleh Teller: <strong class="text-slate-800">{{ $dailyClosing->teller?->name }}</strong> ({{ $dailyClosing->teller?->email }})
                </p>
            </div>

            <div>
                @if($dailyClosing->status === 'approved')
                    <div class="px-4 py-2 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-right">
                        <span class="text-[10px] uppercase font-bold text-emerald-600 block">Status Rekonsiliasi</span>
                        <div class="text-sm font-extrabold flex items-center gap-1.5 justify-end">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Telah Diverifikasi Brankas
                        </div>
                        <span class="text-[10px] text-slate-400">Oleh: {{ $dailyClosing->admin?->name ?? 'Admin' }} pada {{ $dailyClosing->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                @else
                    <div class="px-4 py-2 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-right">
                        <span class="text-[10px] uppercase font-bold text-amber-600 block">Status Rekonsiliasi</span>
                        <div class="text-sm font-extrabold flex items-center gap-1.5 justify-end">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Menunggu Verifikasi Brankas
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3-Column Bento Financial Audit Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Kas Tercatat Sistem -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kas Menurut Sistem (Buku)</span>
                <p class="text-xl font-mono font-extrabold text-slate-900 mt-1">
                    Rp {{ number_format($dailyClosing->system_cash, 0, ',', '.') }}
                </p>
                <span class="text-[10px] text-slate-500">Saldo kumulatif mutasi loket</span>
            </div>

            <!-- Kas Fisik Nyata di Laci -->
            <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-200/60">
                <span class="text-[10px] uppercase font-bold text-blue-700 block tracking-wider">Kas Fisik Nyata (Hitung Laci)</span>
                <p class="text-xl font-mono font-extrabold text-blue-800 mt-1">
                    Rp {{ number_format($dailyClosing->physical_cash, 0, ',', '.') }}
                </p>
                <span class="text-[10px] text-blue-600">Dihitung manual oleh teller</span>
            </div>

            <!-- Selisih (Difference) -->
            <div class="p-4 rounded-2xl border {{ $dailyClosing->difference === 0 ? 'bg-emerald-50/60 border-emerald-200/60' : 'bg-rose-50/60 border-rose-200/60' }}">
                <span class="text-[10px] uppercase font-bold {{ $dailyClosing->difference === 0 ? 'text-emerald-700' : 'text-rose-700' }} block tracking-wider">
                    Kondisi Selisih Kas
                </span>
                <p class="text-xl font-mono font-extrabold {{ $dailyClosing->difference === 0 ? 'text-emerald-800' : 'text-rose-800' }} mt-1">
                    @if($dailyClosing->difference === 0)
                        Rp 0 (PAS / SEIMBANG)
                    @elseif($dailyClosing->difference > 0)
                        + Rp {{ number_format($dailyClosing->difference, 0, ',', '.') }} (LEBIH)
                    @else
                        - Rp {{ number_format(abs($dailyClosing->difference), 0, ',', '.') }} (KURANG)
                    @endif
                </p>
                <span class="text-[10px] text-slate-500">
                    {{ $dailyClosing->difference === 0 ? 'Tidak ada selisih kas fisik' : 'Diperlukan audit bukti fisik' }}
                </span>
            </div>
        </div>

        <!-- Catatan Teller -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
            <span class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Catatan dari Petugas Teller:</span>
            <p class="text-xs text-slate-700 italic">
                {{ $dailyClosing->notes ? '"' . $dailyClosing->notes . '"' : '(Tidak ada catatan khusus yang dilampirkan oleh teller)' }}
            </p>
        </div>

        <!-- Approval Action Section -->
        @if($dailyClosing->status === 'submitted')
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-500">
                    Dengan menyetujui laporan ini, uang fisik sebesar <strong class="text-slate-900 font-mono">Rp {{ number_format($dailyClosing->physical_cash, 0, ',', '.') }}</strong> dinyatakan telah diterima dan diamankan di Brankas Utama Bank Mini.
                </p>

                <form method="POST" action="{{ route('admin.daily-closings.approve', $dailyClosing->id) }}" onsubmit="return confirm('Apakah Anda yakin telah menerima fisik uang kas sebesar Rp {{ number_format($dailyClosing->physical_cash, 0, ',', '.') }} untuk dipindahkan ke Brankas Utama?')">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all hover:shadow-lg flex items-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Verifikasi & Masukkan ke Brankas</span>
                    </button>
                </form>
            </div>
        @else
            <div class="pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Proses serah terima kas fisik dan penutupan buku harian telah selesai dan terkunci.</span>
            </div>
        @endif
    </div>
</div>
@endsection
