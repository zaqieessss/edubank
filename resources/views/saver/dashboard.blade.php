@extends('layouts.app')

@section('title', 'Tabungan Digital Siswa - ' . $saver->full_name)
@section('page_title', 'Tabungan Saya')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- ========================================================================= -->
    <!-- 1. WELCOME HERO CARD (Minimalis & Bersih)                                  -->
    <!-- ========================================================================= -->
    <div class="relative w-full rounded-[24px] bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white p-5 md:p-6 shadow-sm border border-slate-800/60 overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
        <!-- Subtle Ambient Glow -->
        <div class="absolute -left-10 -top-10 w-44 h-44 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1">
            <h2 class="text-xl md:text-2xl font-bold tracking-tight text-white">
                Selamat Datang, {{ $saver->full_name }}
            </h2>
            <p class="text-xs text-slate-300 max-w-xl leading-relaxed">
                Portal Tabungan Digital Siswa &bull; {{ $saver->class_name }} &bull; NIS: {{ $saver->student_id_number }}
            </p>
        </div>

        <!-- Tombol Aksi Cepat -->
        <div class="relative z-10 flex items-center gap-2.5 shrink-0">
            <!-- Tombol Tampilkan QR Siswa -->
            <button type="button" 
                    onclick="openQrModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold shadow-sm transition-all hover:shadow hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>QR Rekening</span>
            </button>

            <!-- Tombol Ganti PIN -->
            <button type="button" 
                    onclick="openPinModal()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/15 text-xs font-semibold backdrop-blur-md transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                <span>Ubah PIN</span>
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. TIGA BENTO CARDS UTAMA (Saldo Utama, Limit Tarik, Arus Kas Bulan Ini)    -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 shrink-0">
        
        <!-- Kartu 1: Total Saldo Utama Tabungan -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 tracking-wide">
                    Total Saldo Tabungan
                </span>
                <span class="text-[10px] font-mono font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">
                    {{ $saver->account_number }}
                </span>
            </div>
            <div class="py-2">
                <p class="text-2xl md:text-3xl font-extrabold text-blue-700 font-mono tracking-tight">
                    Rp {{ number_format($saver->balance, 0, ',', '.') }}
                </p>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-400 font-medium">
                <span>{{ $saver->major }} &bull; Angkatan {{ $saver->cohort }}</span>
                <span class="text-emerald-600 font-bold">Terverifikasi</span>
            </div>
        </div>

        <!-- Kartu 2: Saldo Mengendap & Saldo Dapat Ditarik -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Batas Penarikan Tunai di Loket
            </span>
            <div class="py-2 flex items-baseline gap-4">
                <div>
                    <span class="text-[10px] text-emerald-600 font-bold block uppercase">Siap Ditarik</span>
                    <p class="text-lg md:text-xl font-extrabold text-emerald-700 font-mono">
                        Rp {{ number_format($maxWithdrawable, 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-[10px] text-amber-600 font-bold block uppercase">Saldo Mengendap</span>
                    <p class="text-lg md:text-xl font-extrabold text-slate-800 font-mono">
                        Rp {{ number_format($holdingBalance, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Saldo minimum wajib tinggal di rekening
            </span>
        </div>

        <!-- Kartu 3: Arus Kas Tabungan Bulan Ini -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 tracking-wide">
                    Aktivitas Tabungan Bulan Ini
                </span>
                <span class="text-[10px] font-bold text-slate-500">
                    {{ now()->translatedFormat('F Y') }}
                </span>
            </div>
            <div class="py-2 flex items-baseline gap-4">
                <div>
                    <span class="text-[10px] text-emerald-600 font-bold block uppercase">Setoran Masuk</span>
                    <p class="text-lg md:text-xl font-extrabold text-emerald-700 font-mono">
                        +Rp {{ number_format($monthlyDeposits, 0, ',', '.') }}
                    </p>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-[10px] text-rose-500 font-bold block uppercase">Penarikan Keluar</span>
                    <p class="text-lg md:text-xl font-extrabold text-slate-800 font-mono">
                        -Rp {{ number_format($monthlyWithdrawals, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">
                Total {{ $totalTransactionsCount }} mutasi sejak pembukaan rekening
            </span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 3. MAIN TABLE BENTO BOX (Riwayat Mutasi Tabungan Terakhir)                 -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[380px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Judul & Tautan ke Buku Tabungan Lengkap) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        10 Riwayat Mutasi Transaksi Terakhir
                    </h3>
                    <p class="text-[11px] text-slate-400">Catatan transaksi setor & tarik tabungan Anda di loket sekolah.</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('saver.mutation') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span>Buku Tabungan Lengkap &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3">No. Kwitansi</th>
                            <th class="py-2.5 px-3">Tanggal & Waktu</th>
                            <th class="py-2.5 px-3">Jenis Transaksi</th>
                            <th class="py-2.5 px-3">Keterangan</th>
                            <th class="py-2.5 px-3 text-right">Nominal</th>
                            <th class="py-2.5 px-3 text-right">Saldo Akhir</th>
                            <th class="py-2.5 px-3">Petugas Teller</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($recentTransactions as $t)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3 font-mono font-bold text-slate-800 text-[11px]">
                                    {{ $t->receipt_number }}
                                </td>
                                <td class="py-3 px-3 text-slate-600 text-[11px]">
                                    <div class="font-medium text-slate-800">{{ $t->transaction_date->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $t->transaction_date->format('H:i:s') }} WIB</div>
                                </td>
                                <td class="py-3 px-3">
                                    @if($t->transaction_type === 'deposit')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Setoran
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Penarikan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-slate-600 text-[11px]">
                                    {{ $t->description ?: '-' }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-extrabold text-[12px] {{ $t->transaction_type === 'deposit' ? 'text-emerald-700' : 'text-rose-600' }}">
                                    {{ $t->transaction_type === 'deposit' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-slate-900 text-[11px]">
                                    Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-slate-600 text-[11px]">
                                    <div class="font-medium text-slate-800">{{ $t->user?->name ?? 'Teller 01' }}</div>
                                    <div class="text-[10px] text-slate-400">Loket SMKN 1 Bantul</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-14 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <span class="text-xs font-medium">Belum ada riwayat transaksi pada rekening tabungan Anda</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subtle Footer Alignment -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 shrink-0">
            <span>Rekening Resmi Siswa</span>
            <span class="font-mono">Edubank SMKN 1 Bantul</span>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: QR Code Rekening Ukuran Besar & Elegan (Identik Milik Admin)        -->
<!-- ========================================================================= -->
<div id="qrModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4" onclick="if(event.target === this) closeQrModal()">
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 shadow-2xl border border-slate-200/80 text-center animate-fade-in relative">
        <button type="button" onclick="closeQrModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm cursor-pointer">&times;</button>
        
        <!-- Header Identitas Bank Mini -->
        <div class="flex items-center justify-center gap-2 mb-4">
            <div class="w-7 h-7 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Edubank SMKN 1 Bantul</span>
        </div>

        <!-- Big QR Code Frame -->
        <div class="w-64 h-64 mx-auto p-4 bg-white rounded-2xl border border-slate-200/80 shadow-inner flex items-center justify-center">
            <img src="{{ $qrCodeDataUri }}" alt="QR Code Rekening" class="w-full h-full object-contain">
        </div>

        <!-- Student & Account Details -->
        <div class="mt-4 space-y-0.5">
            <p class="text-base font-bold text-slate-900 uppercase tracking-tight">{{ $saver->full_name }}</p>
            <p class="text-sm font-mono font-bold text-blue-600">{{ $saver->account_number }}</p>
            <p class="text-xs text-slate-500 font-medium">{{ $saver->class_name }} &bull; NIS: {{ $saver->student_id_number }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 mt-5">
            <a href="{{ $qrCodeDataUri }}" download="QR_{{ $saver->account_number }}.png" class="flex-1 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh QR</span>
            </a>
            <button type="button" onclick="closeQrModal()" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Ganti PIN Keamanan Siswa (Centered Backdrop Blur)                  -->
<!-- ========================================================================= -->
<div id="pinModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closePinModal()">
    
    <div class="bg-white rounded-[28px] max-w-sm w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Ubah PIN Keamanan</h3>
                    <p class="text-[10px] text-slate-400">Wajib 6 digit angka rahasia</p>
                </div>
            </div>
            <button type="button" onclick="closePinModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm cursor-pointer">
                &times;
            </button>
        </div>

        <form id="pinForm" method="POST" action="{{ route('saver.pin.update') }}" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">PIN Saat Ini (6 Digit) <span class="text-rose-500">*</span></label>
                <input type="password" 
                       name="current_pin" 
                       maxlength="6" 
                       pattern="[0-9]{6}" 
                       placeholder="******" 
                       required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-center text-base font-mono tracking-widest text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">PIN Baru (6 Digit) <span class="text-rose-500">*</span></label>
                <input type="password" 
                       name="new_pin" 
                       id="new_pin"
                       maxlength="6" 
                       pattern="[0-9]{6}" 
                       placeholder="******" 
                       required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-center text-base font-mono tracking-widest text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ulangi PIN Baru <span class="text-rose-500">*</span></label>
                <input type="password" 
                       name="new_pin_confirmation" 
                       id="new_pin_confirmation"
                       maxlength="6" 
                       pattern="[0-9]{6}" 
                       placeholder="******" 
                       required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-center text-base font-mono tracking-widest text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closePinModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold cursor-pointer">
                    Batal
                </button>
                <button type="button" onclick="triggerPinConfirm()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm cursor-pointer">
                    Simpan PIN
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Konfirmasi Simpan PIN (Centered Backdrop Blur)                     -->
<!-- ========================================================================= -->
<div id="confirmPinModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeConfirmPinModal()">
    
    <div class="bg-white rounded-[24px] max-w-sm w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Ubah PIN</h3>
            </div>
            <button type="button" onclick="closeConfirmPinModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm cursor-pointer">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin memperbarui 6 digit PIN keamanan transaksi tabungan Anda? Harap jaga kerahasiaan PIN ini dari orang lain.
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeConfirmPinModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold cursor-pointer">
                Periksa Lagi
            </button>
            <button type="button" onclick="submitPinForm()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-xs cursor-pointer">
                Ya, Perbarui PIN
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // QR Modal Handlers (Identik Admin)
    function openQrModal() {
        const modal = document.getElementById('qrModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeQrModal() {
        const modal = document.getElementById('qrModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Fallback alias
    function openQrCardModal() { openQrModal(); }
    function closeQrCardModal() { closeQrModal(); }

    // PIN Modal Handlers
    function openPinModal() {
        const modal = document.getElementById('pinModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closePinModal() {
        const modal = document.getElementById('pinModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // Confirm PIN Handlers
    function triggerPinConfirm() {
        const form = document.getElementById('pinForm');
        if (form && form.reportValidity()) {
            const newPin = document.getElementById('new_pin').value;
            const confirmPin = document.getElementById('new_pin_confirmation').value;
            if (newPin !== confirmPin) {
                alert('Konfirmasi PIN baru tidak cocok dengan PIN baru.');
                return;
            }
            openConfirmPinModal();
        }
    }

    function openConfirmPinModal() {
        const modal = document.getElementById('confirmPinModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeConfirmPinModal() {
        const modal = document.getElementById('confirmPinModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitPinForm() {
        const form = document.getElementById('pinForm');
        if (form) form.submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQrModal();
            closePinModal();
            closeConfirmPinModal();
        }
    });
</script>
@endpush
@endsection
