@extends('layouts.app')

@section('title', 'Periksa Closing: ' . \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('d F Y'))
@section('page_title', 'Periksa Closing Kas Harian')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

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

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.daily-closings.print', $dailyClosing->id) }}" 
               target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-xs transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak PDF</span>
            </a>

            <div class="text-left sm:text-right hidden sm:block">
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

    <!-- Main Audit Bento Card (Fluid & Expands with Content) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex flex-col gap-6">
        
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

            <!-- Daftar Transaksi Lengkap Hari Ini -->
            <div class="pt-2 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                            Daftar Transaksi Teller Hari Ini
                        </h4>
                        <p class="text-[11px] text-slate-400">
                            Total {{ $transactions->count() }} transaksi diproses pada {{ \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <span class="text-xs font-mono font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                        {{ $transactions->count() }} Trx
                    </span>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200/80 shadow-xs bg-white">
                    <table class="w-full text-left text-xs min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-500 font-bold text-[11px] uppercase tracking-wider">
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Kode Trx</th>
                                <th class="py-3 px-4">Nasabah</th>
                                <th class="py-3 px-4 text-center">Jenis</th>
                                <th class="py-3 px-4 text-right">Nominal</th>
                                <th class="py-3 px-4 text-right">Saldo Sesudah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transactions as $t)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-4 font-mono text-[11px] text-slate-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($t->transaction_date)->format('H:i:s') }}
                                    </td>
                                    <td class="py-3 px-4 font-mono font-bold text-blue-700 whitespace-nowrap">
                                        {{ $t->transaction_code }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $t->saver?->full_name ?? '-' }}</div>
                                        <div class="text-[10px] font-mono text-slate-400">{{ $t->saver?->account_number }} &bull; {{ $t->saver?->class_name }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if($t->transaction_type === 'deposit')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                Setoran
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                                Penarikan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-xs whitespace-nowrap {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-slate-900' }}">
                                        {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono text-slate-600 whitespace-nowrap">
                                        Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-xs text-slate-400">
                                        Tidak ada transaksi tunai yang diproses oleh teller pada hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        @if($dailyClosing->status !== 'approved')
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0">
                <p class="text-xs text-slate-400 max-w-xl">
                    Pastikan fisik uang tunai sebesar <strong class="text-slate-800 font-mono">Rp {{ number_format($dailyClosing->physical_cash ?? $dailyClosing->system_balance, 0, ',', '.') }}</strong> telah dihitung nyata di laci kas sebelum melakukan verifikasi brankas atau penolakan.
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
                    <button type="button" 
                            onclick="openApproveModal()" 
                            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Setujui & Masukkan ke Brankas</span>
                    </button>
                </div>
            </div>
        @else
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

    <!-- Modal Approve Laporan (Centered Backdrop Blur) -->
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
                <button type="button" onclick="closeApproveModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                    &times;
                </button>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Apakah Anda yakin telah menerima dan menghitung fisik uang tunai sebesar <strong class="text-slate-800 font-mono">Rp {{ number_format($dailyClosing->physical_cash ?? $dailyClosing->system_balance, 0, ',', '.') }}</strong> dari teller untuk dimasukkan ke Brankas Utama (Vault)?
            </p>

            <form method="POST" action="{{ route('admin.daily-closings.approve', $dailyClosing->id) }}" class="pt-2">
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

    function openApproveModal() {
        const modal = document.getElementById('approveModal');
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
            closeRejectModal();
            closeApproveModal();
        }
    });
</script>
@endpush
@endsection
