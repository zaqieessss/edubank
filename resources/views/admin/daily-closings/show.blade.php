@extends('layouts.app')

@section('title', 'Periksa Closing: ' . \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('d F Y'))
@section('page_title', 'Periksa Closing Kas Harian')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation & Summary Card -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.daily-closings.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Daftar Closing">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laporan Tutup Kas</span>
                    <span class="text-xs text-slate-300 font-mono">&bull;</span>
                    <span class="text-xs font-mono font-bold text-slate-500">Ref: #CLS-{{ str_pad($dailyClosing->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    {{ \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('l, d F Y') }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-left sm:text-right">
                <div class="text-xs font-bold text-slate-800">{{ $dailyClosing->teller?->name ?? 'Unknown Teller' }}</div>
                <div class="text-[11px] text-slate-400">{{ $dailyClosing->teller?->email }}</div>
            </div>

            <div>
                @if($dailyClosing->status === 'approved')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Telah Disetujui
                    </span>
                @elseif($dailyClosing->status === 'rejected')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Verifikasi
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Audit Bento Card (Banyak White Space, Memanjang Sejajar Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="space-y-6">
            <!-- 3 Column Financial Audit Numbers (Clean White Space) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-slate-100">
                <!-- Kas Sistem (Buku) -->
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">Kas Menurut Sistem (Buku)</span>
                    <p class="text-2xl lg:text-3xl font-mono font-extrabold text-slate-900 mt-1.5">
                        Rp {{ number_format($dailyClosing->system_balance ?? $dailyClosing->system_cash, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Kas Fisik Nyata (Hitung Laci) -->
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">Kas Fisik Nyata (Hitung Laci)</span>
                    <p class="text-2xl lg:text-3xl font-mono font-extrabold text-slate-900 mt-1.5">
                        Rp {{ number_format($dailyClosing->physical_cash, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Selisih Kas -->
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">Kondisi Selisih Kas</span>
                    <div class="mt-1.5 flex items-baseline gap-2">
                        @if($dailyClosing->difference === 0)
                            <p class="text-2xl lg:text-3xl font-mono font-extrabold text-emerald-600">Rp 0</p>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                Seimbang
                            </span>
                        @elseif($dailyClosing->difference > 0)
                            <p class="text-2xl lg:text-3xl font-mono font-extrabold text-amber-600">
                                + Rp {{ number_format($dailyClosing->difference, 0, ',', '.') }}
                            </p>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200/80">
                                Lebih
                            </span>
                        @else
                            <p class="text-2xl lg:text-3xl font-mono font-extrabold text-rose-600">
                                - Rp {{ number_format(abs($dailyClosing->difference), 0, ',', '.') }}
                            </p>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-800 border border-rose-200/80">
                                Kurang
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mutasi Rincian Pembukuan Kas Loket -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 py-2 border-b border-slate-100">
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">Saldo Awal Kas Teller</span>
                    <p class="text-base font-mono font-bold text-slate-700 mt-1">
                        Rp {{ number_format($dailyClosing->opening_balance, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <span class="text-xs font-semibold text-slate-400 block">Total Setoran Tunai Masuk (+)</span>
                    <p class="text-base font-mono font-bold text-emerald-700 mt-1">
                        + Rp {{ number_format($dailyClosing->total_deposits, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <span class="text-xs font-semibold text-slate-400 block">Total Penarikan Keluar (-)</span>
                    <p class="text-base font-mono font-bold text-rose-700 mt-1">
                        - Rp {{ number_format($dailyClosing->total_withdrawals, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Catatan Teller -->
            <div class="pt-2">
                <span class="text-xs font-semibold text-slate-400 block mb-2">Catatan dari Petugas Teller</span>
                <div class="p-4 rounded-2xl border border-slate-100 text-xs text-slate-700 leading-relaxed bg-slate-50/30">
                    {{ $dailyClosing->notes ? $dailyClosing->notes : '(Tidak ada catatan khusus yang dilampirkan oleh teller)' }}
                </div>
            </div>

            <!-- Jika Pernah Ditolak, Tampilkan Alasan Penolakan -->
            @if($dailyClosing->status === 'rejected' && $dailyClosing->rejection_reason)
                <div class="pt-1">
                    <span class="text-xs font-semibold text-rose-600 block mb-2">Alasan Penolakan oleh Admin</span>
                    <div class="p-4 rounded-2xl border border-rose-200/80 bg-rose-50/40 text-xs text-rose-900 leading-relaxed font-medium">
                        {{ $dailyClosing->rejection_reason }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Footer (Pinned at Bottom) -->
        @if($dailyClosing->status === 'submitted' || $dailyClosing->status === 'discrepancy')
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0">
                <p class="text-xs text-slate-400 max-w-xl">
                    Pastikan fisik uang tunai sebesar <strong class="text-slate-800 font-mono">Rp {{ number_format($dailyClosing->physical_cash, 0, ',', '.') }}</strong> telah dihitung nyata di laci kas sebelum melakukan verifikasi brankas atau penolakan.
                </p>

                <div class="flex items-center gap-3 shrink-0">
                    <!-- Tombol Reject -->
                    <button type="button" 
                            onclick="openRejectModal()" 
                            class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Tolak Laporan</span>
                    </button>

                    <!-- Tombol Approve -->
                    <form method="POST" action="{{ route('admin.daily-closings.approve', $dailyClosing->id) }}" onsubmit="return confirm('Apakah Anda yakin telah menerima fisik uang kas sebesar Rp {{ number_format($dailyClosing->physical_cash, 0, ',', '.') }} untuk dimasukkan ke Brankas Utama?')">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Verifikasi & Masukkan ke Brankas</span>
                        </button>
                    </form>
                </div>
            </div>
        @elseif($dailyClosing->status === 'approved')
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0">
                <div class="flex items-center gap-2 text-xs text-emerald-700 font-medium">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Laporan telah diverifikasi dan disetujui oleh <strong>{{ $dailyClosing->admin?->name ?? 'Admin' }}</strong> pada {{ $dailyClosing->updated_at->format('d/m/Y H:i') }} WIB. Dana telah aman di brankas utama.</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Reject Laporan (Pure Vanilla JavaScript Driven) -->
    <div id="rejectModal" 
         class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
         onclick="if(event.target === this) closeRejectModal()">
        
        <div class="bg-white rounded-[24px] max-w-lg w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Tolak Laporan Closing Kas</h3>
                </div>
                <button type="button" onclick="closeRejectModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                    &times;
                </button>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Tuliskan alasan mengapa laporan closing ini ditolak. Alasan ini akan langsung muncul di panel teller agar mereka dapat melakukan perhitungan ulang atau penyesuaian kas fisik.
            </p>

            <form method="POST" action="{{ route('admin.daily-closings.reject', $dailyClosing->id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="rejection_reason" 
                              id="rejectionReasonInput"
                              rows="4" 
                              required
                              placeholder="Contoh: Terdapat selisih fisik Rp 50.000 pada pecahan Rp 50.000. Mohon hitung ulang fisik kas di laci teller..."
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal() {
        const modal = document.getElementById('rejectModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            const textarea = document.getElementById('rejectionReasonInput');
            if (textarea) setTimeout(() => textarea.focus(), 50);
        }
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRejectModal();
        }
    });
</script>
@endpush
@endsection
