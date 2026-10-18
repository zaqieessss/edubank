@extends('layouts.app')

@section('title', 'Laporan Penutupan Kas Harian (Daily Report)')
@section('page_title', 'Daily Report / Penutupan Kas')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Card 1: Total Pengajuan Kas -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Tutup Buku</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($closings->total(), 0, ',', '.') }}</h3>
                <span class="text-[10px] font-medium text-slate-500">Laporan rekonsiliasi teller</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <!-- Card 2: Menunggu Persetujuan Brankas -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Perlu Verifikasi Vault</p>
                <h3 class="text-xl font-extrabold text-amber-600 mt-0.5">
                    {{ \App\Models\DailyClosing::where('status', 'submitted')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Menunggu serah terima brankas</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 3: Telah Diverifikasi Brankas -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Disetujui / Brankas Masuk</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">
                    {{ \App\Models\DailyClosing::where('status', 'approved')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-emerald-600">Selesai rekonsiliasi</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 4: Discrepancy Warnings -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Laporan Selisih Kas</p>
                <h3 class="text-xl font-extrabold text-rose-600 mt-0.5">
                    {{ \App\Models\DailyClosing::where('difference', '!=', 0)->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Audit investigasi fisik</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Daftar Rekonsiliasi Kas Teller (Closing)</h3>
                <p class="text-[11px] text-slate-400">Verifikasi fisik uang kas sebelum dipindahkan ke brankas utama</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">Tanggal Closing</th>
                        <th class="py-2.5 px-3">Petugas Teller</th>
                        <th class="py-2.5 px-3 text-right">Kas Sistem (Buku)</th>
                        <th class="py-2.5 px-3 text-right">Kas Fisik (Laci)</th>
                        <th class="py-2.5 px-3 text-center">Selisih Fisik</th>
                        <th class="py-2.5 px-3 text-center">Status Verifikasi</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($closings as $c)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($c->closing_date)->translatedFormat('d F Y') }}
                                <div class="text-[10px] text-slate-400">{{ $c->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-800">{{ $c->teller?->name ?? 'Unknown' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $c->teller?->email }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-800">
                                Rp {{ number_format($c->system_cash, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                Rp {{ number_format($c->physical_cash, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($c->difference === 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Seimbang (Pas)
                                    </span>
                                @elseif($c->difference > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        Lebih Rp {{ number_format($c->difference, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                        Kurang Rp {{ number_format(abs($c->difference), 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($c->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Disetujui Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Persetujuan
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.daily-closings.show', $c->id) }}" 
                                       class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-[10px] font-bold transition-all shadow-xs flex items-center gap-1">
                                        <span>Periksa & Approval</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada laporan penutupan kas harian yang diajukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $closings->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $closings->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $closings->total() }}</span> laporan
            </div>
            <div>
                {{ $closings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
