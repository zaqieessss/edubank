@extends('layouts.app')

@section('title', 'Form Tutup Kas Harian (Daily Closing)')
@section('page_title', 'Tutup Kas Harian')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Konsisten & Rapi Terbungkus Card) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('teller.daily-closings.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Riwayat Closing">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Formulir Rekonsiliasi</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    Tutup Kas Harian (Daily Closing)
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($summary['date'])->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    @if($todayClosing)
        @if($todayClosing->status === 'rejected')
            <!-- Warning Banner: Rejected by Admin -->
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-rose-800 space-y-2 shrink-0">
                <div class="flex items-center gap-2 text-xs font-bold text-rose-700">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Laporan Tutup Kas Anda Ditolak oleh Admin</span>
                </div>
                <div class="text-xs leading-relaxed space-y-1.5">
                    <div class="p-2.5 bg-white rounded-xl border border-rose-200 text-rose-900 font-medium">
                        <strong class="block text-[10px] uppercase font-bold text-rose-500 mb-0.5 tracking-wider">Catatan Alasan Penolakan:</strong>
                        {{ $todayClosing->rejection_reason ?? 'Mohon periksa kembali kesesuaian fisik uang di laci loket Anda.' }}
                    </div>
                    <p class="text-rose-700 font-medium text-[11px]">
                        Silakan hitung kembali fisik uang di laci kas Anda, perbarui nominal uang fisik pada form di bawah ini, dan klik <strong>Ajukan Penutupan Kas Harian</strong> untuk mengirimkan revisi ke Admin.
                    </p>
                </div>
            </div>
        @elseif($todayClosing->status === 'approved')
            <!-- Success Banner: Approved -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-emerald-800 space-y-1.5 shrink-0">
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-700">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Laporan Tutup Kas Telah Disetujui</span>
                </div>
                <p class="text-xs leading-relaxed text-emerald-900">
                    Laporan kas untuk hari ini (Rp {{ number_format($todayClosing->physical_cash, 0, ',', '.') }}) telah diverifikasi dan dana telah diserahterimakan ke brankas utama. Buku harian telah ditutup.
                </p>
            </div>
        @else
            <!-- Warning Banner: Already Closed Today & Waiting -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-amber-800 space-y-1.5 shrink-0">
                <div class="flex items-center gap-2 text-xs font-bold">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Pengajuan Tutup Kas Sedang Menunggu Verifikasi</span>
                </div>
                <p class="text-xs leading-relaxed">
                    Laporan penutupan kas untuk tanggal <strong>{{ \Carbon\Carbon::parse($summary['date'])->translatedFormat('d F Y') }}</strong> sudah diajukan (Kas Fisik: Rp {{ number_format($todayClosing->physical_cash, 0, ',', '.') }}). Laporan sedang menunggu verifikasi serah terima brankas oleh Administrator.
                </p>
            </div>
        @endif
    @endif

    <!-- Main Reconciliation Bento Form (Fluid & Sejajar Bawah Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[440px]">
        
        <div class="space-y-5">
            <!-- 3 Bento Stat Boxes: Opening + Deposits - Withdrawals = System Cash -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/70">
                    <span class="text-[10px] uppercase font-bold text-emerald-800 block">Total Setoran Tunai Masuk</span>
                    <p class="text-xl font-mono font-extrabold text-emerald-700 mt-1">
                        + Rp {{ number_format($summary['total_deposits'], 0, ',', '.') }}
                    </p>
                    <span class="text-[10px] text-emerald-600 font-medium">Uang masuk ke laci kas</span>
                </div>

                <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200/70">
                    <span class="text-[10px] uppercase font-bold text-rose-800 block">Total Penarikan Keluar</span>
                    <p class="text-xl font-mono font-extrabold text-rose-700 mt-1">
                        - Rp {{ number_format($summary['total_withdrawals'], 0, ',', '.') }}
                    </p>
                    <span class="text-[10px] text-rose-600 font-medium">Uang diserahkan ke siswa</span>
                </div>

                <div class="p-4 rounded-2xl bg-slate-900 text-white shadow-md shadow-slate-900/10">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Kas Menurut Sistem (Buku)</span>
                    <p class="text-xl font-mono font-extrabold text-blue-300 mt-1" id="systemCashDisplay" data-value="{{ $summary['closing_cash'] }}">
                        Rp {{ number_format($summary['closing_cash'], 0, ',', '.') }}
                    </p>
                    <span class="text-[10px] text-slate-300">Wajib sama dengan kas fisik</span>
                </div>
            </div>

            <!-- Input Form -->
            <form method="POST" action="{{ route('teller.daily-closings.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kas Fisik Laci Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                            Nominal Uang Fisik Hasil Perhitungan Nyata di Laci (Rp) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input type="text" 
                                   inputmode="numeric"
                                   data-currency="true"
                                   name="physical_cash" 
                                   id="physicalCashInput" 
                                   value="{{ old('physical_cash', $todayClosing?->physical_cash ?? $summary['closing_cash']) }}" 
                                   {{ $todayClosing && $todayClosing->status === 'approved' ? 'disabled' : 'required' }} 
                                   oninput="calculateDifference()" 
                                   class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl pl-12 pr-4 py-3 text-lg font-mono font-extrabold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 disabled:opacity-60 disabled:bg-slate-100">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Hitung seluruh lembaran uang tunai dan koin di laci meja teller Anda saat ini.</p>
                        @error('physical_cash')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Live Difference Calculation Box -->
                    <div id="diffBox" class="p-4 rounded-2xl border transition-all duration-300 bg-slate-50 border-slate-200/80 flex flex-col justify-center">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Status Selisih Kas Nyata</span>
                                <div id="diffTitle" class="text-sm font-extrabold text-slate-800 mt-0.5">Menghitung...</div>
                            </div>
                            <div id="diffAmount" class="text-base font-mono font-extrabold text-slate-800">
                                Rp 0
                            </div>
                        </div>
                        <p id="diffDesc" class="text-[11px] text-slate-500 mt-1">
                            Kas fisik sesuai persis dengan pembukuan sistem.
                        </p>
                    </div>
                </div>

                <!-- Catatan Teller -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">
                        Catatan Rekonsiliasi (Wajib diisi jika terdapat selisih uang fisik)
                    </label>
                    <textarea name="notes" 
                              id="closingNotes" 
                              rows="2" 
                              {{ $todayClosing && $todayClosing->status === 'approved' ? 'disabled' : '' }}
                              placeholder="Tuliskan catatan kondisi fisik uang, pecahan, atau keterangan selisih jika ada..." 
                              class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 disabled:opacity-60 disabled:bg-slate-100">{{ old('notes', $todayClosing?->notes) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('teller.daily-closings.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-colors">
                        {{ $todayClosing && $todayClosing->status === 'approved' ? 'Kembali' : 'Batal' }}
                    </a>
                    @if(!$todayClosing || $todayClosing->status !== 'approved')
                        <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-md shadow-slate-900/20 transition-all hover:shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $todayClosing && $todayClosing->status === 'rejected' ? 'Kirim Revisi Penutupan Kas' : 'Ajukan Penutupan Kas Harian' }}</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Subtle Footer Alignment -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
            <span>Rekonsiliasi Kas Laci Loket</span>
            <span class="font-mono">Edubank SMKN 1 Bantul</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function calculateDifference() {
        const systemCash = parseInt(document.getElementById('systemCashDisplay').dataset.value) || 0;
        const physicalCashInput = document.getElementById('physicalCashInput');
        const rawVal = physicalCashInput ? String(physicalCashInput.value).replace(/[^0-9]/g, '') : '0';
        const physicalCash = parseInt(rawVal) || 0;
        const difference = physicalCash - systemCash;

        const diffBox = document.getElementById('diffBox');
        const diffTitle = document.getElementById('diffTitle');
        const diffAmount = document.getElementById('diffAmount');
        const diffDesc = document.getElementById('diffDesc');

        if (difference === 0) {
            diffBox.className = "p-4 rounded-2xl border transition-all duration-300 bg-emerald-50/70 border-emerald-200 text-emerald-900";
            diffTitle.innerHTML = `<span class="flex items-center gap-1.5 text-emerald-700 font-extrabold"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Kas Seimbang (Pas)</span>`;
            diffAmount.innerText = "Rp 0";
            diffAmount.className = "text-base font-mono font-extrabold text-emerald-800";
            diffDesc.innerText = "Uang fisik di laci cocok 100% dengan saldo buku kas mutasi.";
        } else if (difference > 0) {
            diffBox.className = "p-4 rounded-2xl border transition-all duration-300 bg-amber-50/70 border-amber-200 text-amber-900";
            diffTitle.innerHTML = `<span class="text-amber-800 font-extrabold">Terdeteksi Uang Fisik Lebih</span>`;
            diffAmount.innerText = "+ Rp " + new Intl.NumberFormat('id-ID').format(difference);
            diffAmount.className = "text-base font-mono font-extrabold text-amber-800";
            diffDesc.innerText = "Uang fisik di laci lebih banyak dari saldo buku. Harap lampirkan keterangan pada catatan.";
        } else {
            diffBox.className = "p-4 rounded-2xl border transition-all duration-300 bg-rose-50/70 border-rose-200 text-rose-900";
            diffTitle.innerHTML = `<span class="text-rose-800 font-extrabold">Terdeteksi Uang Fisik Kurang</span>`;
            diffAmount.innerText = "- Rp " + new Intl.NumberFormat('id-ID').format(Math.abs(difference));
            diffAmount.className = "text-base font-mono font-extrabold text-rose-800";
            diffDesc.innerText = "Uang fisik di laci kurang dari saldo buku. Laporan akan ditandai selisih untuk audit supervisor.";
        }
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', calculateDifference);
</script>
@endpush
@endsection
