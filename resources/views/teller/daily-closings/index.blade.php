@extends('layouts.app')

@section('title', 'Rekonsiliasi Kas Harian Teller')
@section('page_title', 'Tutup Kas Harian (Closing)')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Action & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <!-- Card 1: Closing Form Entry CTA -->
        <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-[22px] p-4 text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tutup Buku Hari Ini</p>
                <h3 class="text-base font-extrabold text-white mt-0.5">Tutup Kas & Rekonsiliasi</h3>
                <span class="text-[10px] text-slate-300">Setorkan uang laci ke brankas</span>
            </div>
            <a href="{{ route('teller.daily-closings.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold shrink-0 shadow-sm transition-all">
                + Form Closing
            </a>
        </div>

        <!-- Card 2: Status Closing Terakhir -->
        <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Pengajuan Closing</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($closings->total(), 0, ',', '.') }}</h3>
                <span class="text-[10px] font-medium text-slate-500">Rekap kas Anda</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        <!-- Card 3: Status Brankas Disetujui -->
        <div class="bg-white rounded-[22px] p-4 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Diverifikasi Supervisor</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">
                    {{ \App\Models\DailyClosing::where('teller_id', Auth::id())->where('status', 'approved')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-emerald-600">Uang aman di brankas</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Riwayat Penutupan Kas Harian Anda</h3>
                <p class="text-[11px] text-slate-400">Laporan rekonsiliasi uang fisik laci teller dengan pembukuan sistem</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">Tanggal Closing</th>
                        <th class="py-2.5 px-3 text-right">Kas Sistem</th>
                        <th class="py-2.5 px-3 text-right">Kas Fisik Laci</th>
                        <th class="py-2.5 px-3 text-center">Selisih</th>
                        <th class="py-2.5 px-3 text-center">Status Brankas</th>
                        <th class="py-2.5 px-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($closings as $c)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($c->closing_date)->translatedFormat('d F Y') }}
                                <div class="text-[10px] text-slate-400 font-mono">{{ $c->created_at->format('H:i') }} WIB</div>
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
                                        Seimbang (Pas)
                                    </span>
                                @elseif($c->difference > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        Lebih Rp {{ number_format($c->difference, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                        Kurang Rp {{ number_format(abs($c->difference), 0, ',', '.') }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($c->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Disetujui Masuk Brankas
                                    </span>
                                @elseif($c->status === 'rejected')
                                    <div class="inline-flex flex-col items-center gap-1">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak Admin
                                        </span>
                                        <a href="{{ route('teller.daily-closings.create') }}" class="text-[10px] text-blue-600 hover:text-blue-800 font-semibold underline">
                                            Ajukan Ulang &rarr;
                                        </a>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Verifikasi Admin
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-slate-500 text-[11px]">
                                @if($c->status === 'rejected' && $c->rejection_reason)
                                    <div class="p-2 rounded-lg bg-rose-50/80 border border-rose-200/60 text-rose-800 text-[11px] font-medium leading-relaxed">
                                        <strong class="font-bold text-rose-900 block text-[10px] uppercase tracking-wider">Alasan Penolakan:</strong>
                                        {{ $c->rejection_reason }}
                                    </div>
                                @else
                                    <span class="italic text-slate-500">{{ $c->notes ? Str::limit($c->notes, 40) : '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada catatan penutupan kas harian.
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
