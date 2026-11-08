@extends('layouts.app')

@section('title', 'Laporan Penutupan Kas Harian (Daily Report)')
@section('page_title', 'Daily Report / Penutupan Kas')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Bento Stats Grid (3 Cards Informatif & Spacious) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 shrink-0">
        <!-- Card 1: Total Tutup Buku -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Laporan Closing
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ number_format($closings->total(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Laporan</span>
            </div>
        </div>

        <!-- Card 2: Menunggu Persetujuan Vault -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Menunggu Verifikasi
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-amber-500">
                    {{ number_format(\App\Models\DailyClosing::where('status', 'submitted')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-amber-500/80 font-medium">Laporan</span>
            </div>
        </div>

        <!-- Card 3: Telah Disetujui (Brankas Masuk) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Telah Disetujui
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">
                    {{ number_format(\App\Models\DailyClosing::where('status', 'approved')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-emerald-600/80 font-medium">Laporan</span>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box (Memanjang Elegan Sejajar Bawah Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Judul & Filter Status) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Daftar Rekonsiliasi Kas Teller
                </h3>

                <!-- Filter Status Form -->
                <form method="GET" action="{{ route('admin.daily-closings.index') }}" class="flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Telah Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    @if(request()->filled('status'))
                        <a href="{{ route('admin.daily-closings.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                    @endif
                </form>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3 font-medium">Tanggal Closing</th>
                            <th class="py-2.5 px-3 font-medium">Petugas Teller</th>
                            <th class="py-2.5 px-3 font-medium text-right">Kas Sistem (Buku)</th>
                            <th class="py-2.5 px-3 font-medium text-right">Kas Fisik (Laci)</th>
                            <th class="py-2.5 px-3 font-medium text-center">Selisih Fisik</th>
                            <th class="py-2.5 px-3 font-medium text-center">Status Verifikasi</th>
                            <th class="py-2.5 px-3 font-medium text-right">Aksi</th>
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
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-800">{{ $c->teller?->name ?? 'Unknown' }}</div>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-slate-800 text-xs">
                                    Rp {{ number_format($c->system_balance ?? $c->system_cash, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-slate-900 text-xs">
                                    Rp {{ number_format($c->physical_cash, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($c->difference === 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Seimbang
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
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif($c->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.daily-closings.show', $c->id) }}" 
                                           class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-all flex items-center gap-1">
                                            <span>Periksa</span>
                                        </a>
                                        @if($c->status !== 'approved')
                                            <button type="button" 
                                                    onclick="triggerApproveModal('{{ route('admin.daily-closings.approve', $c->id) }}', '{{ addslashes($c->teller?->name ?? 'Teller') }}', 'Rp {{ number_format($c->physical_cash ?? $c->system_balance, 0, ',', '.') }}')"
                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-semibold transition-all shadow-xs flex items-center gap-1 cursor-pointer"
                                                    title="Terima laporan dan masukkan ke brankas">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span>Terima Laporan</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-xs font-medium">Belum ada laporan penutupan kas harian</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compact Pagination Footer (Pinned at bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $closings->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $closings->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $closings->total() }}</span> laporan
            </div>
            <div>
                {{ $closings->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Approve Laporan (Centered Backdrop Blur Sesuai Halaman Periksa) -->
<div id="approveModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeApproveModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Verifikasi & Masukkan ke Brankas</h3>
            </div>
            <button type="button" onclick="closeApproveModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm cursor-pointer">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin telah menerima dan menghitung fisik uang tunai sebesar <strong class="text-slate-800 font-mono" id="modalCashAmount">Rp 0</strong> dari teller <strong class="text-slate-800" id="modalTellerName">Teller</strong> untuk dimasukkan ke Brankas Utama (Vault)?
        </p>

        <form id="modalApproveForm" method="POST" action="" class="pt-2">
            @csrf
            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                    Periksa Lagi
                </button>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                    Ya, Verifikasi & Terima Uang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function triggerApproveModal(actionUrl, tellerName, amountText) {
        const modal = document.getElementById('approveModal');
        const form = document.getElementById('modalApproveForm');
        const nameEl = document.getElementById('modalTellerName');
        const amountEl = document.getElementById('modalCashAmount');

        if (form) form.action = actionUrl;
        if (nameEl) nameEl.textContent = tellerName;
        if (amountEl) amountEl.textContent = amountText;

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeApproveModal() {
        const modal = document.getElementById('approveModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeApproveModal();
        }
    });
</script>
@endpush
@endsection
