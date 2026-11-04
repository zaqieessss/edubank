@extends('layouts.app')

@section('title', 'Rekonsiliasi Kas Harian Teller - Daily Report')
@section('page_title', 'Daily Report')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Bento Stats Grid (3 Cards Informatif & Spacious) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 shrink-0">
        <!-- Card 1: Total Laporan Closing -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Laporan Closing Anda
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ number_format($closings->total(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Laporan</span>
            </div>
        </div>

        <!-- Card 2: Status Disetujui Masuk Brankas -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Telah Disetujui Masuk Brankas
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">
                    {{ number_format(\App\Models\DailyClosing::where('teller_id', Auth::id())->where('status', 'approved')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-emerald-600/80 font-medium">Laporan</span>
            </div>
        </div>

        <!-- Card 3: Menunggu Verifikasi Admin -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Menunggu Verifikasi Admin
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-amber-500">
                    {{ number_format(\App\Models\DailyClosing::where('teller_id', Auth::id())->where('status', 'submitted')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-amber-500/80 font-medium">Laporan</span>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box (Memanjang Elegan Sejajar Bawah Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Judul & Tombol Ajukan Closing) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Riwayat Rekonsiliasi Kas Laci
                </h3>

                <div class="flex items-center gap-2">
                    <a href="{{ route('teller.daily-closings.create') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Ajukan Tutup Kas (Closing)</span>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3 font-medium">Tanggal Closing</th>
                            <th class="py-2.5 px-3 font-medium text-right">Kas Sistem (Buku)</th>
                            <th class="py-2.5 px-3 font-medium text-right">Kas Fisik Laci</th>
                            <th class="py-2.5 px-3 font-medium text-center">Selisih</th>
                            <th class="py-2.5 px-3 font-medium text-center">Status Brankas</th>
                            <th class="py-2.5 px-3 font-medium">Catatan / Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($closings as $c)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-800">
                                        {{ \Carbon\Carbon::parse($c->closing_date)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $c->created_at->format('H:i:s') }} WIB</div>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-slate-800 text-xs">
                                    Rp {{ number_format($c->system_cash, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-slate-900 text-xs">
                                    Rp {{ number_format($c->physical_cash, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($c->difference === 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Seimbang
                                        </span>
                                    @elseif($c->difference > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200/80">
                                            Lebih Rp {{ number_format($c->difference, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-800 border border-rose-200/80">
                                            Kurang Rp {{ number_format(abs($c->difference), 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($c->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                        </span>
                                    @elseif($c->status === 'rejected')
                                        <div class="inline-flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak Admin
                                            </span>
                                            <a href="{{ route('teller.daily-closings.create') }}" class="text-[10px] text-blue-600 hover:text-blue-800 font-semibold underline">
                                                Ajukan Ulang &rarr;
                                            </a>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-slate-500 text-[11px]">
                                    @if($c->status === 'rejected' && $c->rejection_reason)
                                        <div class="p-2 rounded-lg bg-rose-50/80 border border-rose-200/60 text-rose-800 text-[11px] font-medium leading-relaxed">
                                            <strong class="font-bold text-rose-900 block text-[10px] uppercase tracking-wider">Alasan Penolakan:</strong>
                                            {{ $c->rejection_reason }}
                                        </div>
                                    @else
                                        <span class="text-slate-600">{{ $c->notes ? Str::limit($c->notes, 40) : '-' }}</span>
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
        </div>

        <!-- Pagination -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 shrink-0">
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
