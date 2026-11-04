@extends('layouts.app')

@section('title', 'Pelayanan Transaksi: ' . $saver->full_name)
@section('page_title', 'Loket Transaksi Nasabah')

@section('content')
<div class="min-h-full flex flex-col gap-4 pb-6">

    <!-- Top Navigation Card (Konsisten & Rapi Terbungkus Card) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('teller.transactions.scanner') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Scanner / Cari Siswa Lain">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Loket Pelayanan Transaksi</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    Transaksi: {{ $saver->full_name }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-left sm:text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Nomor Rekening</span>
                <span class="font-mono font-extrabold text-blue-700 text-sm">{{ $saver->account_number }}</span>
            </div>
            <a href="{{ route('teller.transactions.scanner') }}" 
               class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>Scan Siswa Lain</span>
            </a>
        </div>
    </div>

    <!-- Bento Grid: 2 Columns (Student Profile vs Service Desk Tabs) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        
        <!-- Left: Student Savings Profile & Balance Specs -->
        <div class="lg:col-span-5 flex flex-col gap-4">
            
            <!-- Student Header Profile Card -->
            <div class="bg-white rounded-[26px] p-5 md:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-4">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 mb-1">
                            {{ $saver->major }} &bull; Angkatan {{ $saver->cohort }}
                        </span>
                        <h3 class="text-base font-extrabold text-slate-900 leading-tight">{{ $saver->full_name }}</h3>
                        <p class="text-xs text-slate-500">Kelas: <strong class="text-slate-800">{{ $saver->class_name }}</strong></p>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">NIS: {{ $saver->student_id_number }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">No. Rekening</span>
                        <span class="font-mono font-extrabold text-blue-700 text-sm">{{ $saver->account_number }}</span>
                    </div>
                </div>

                <!-- Balance Breakdown Bento Box -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white space-y-3.5 shadow-md shadow-slate-900/10">
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block tracking-wider">Total Saldo Rekening</span>
                        <div class="text-3xl font-extrabold font-mono text-white mt-0.5 tracking-tight">
                            Rp {{ number_format($saver->balance, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-white/10 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Saldo Mengendap</span>
                            <span class="font-mono font-bold text-amber-300">Rp 10.000</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Maksimal Ditarik</span>
                            <span class="font-mono font-bold text-emerald-400">Rp {{ number_format($maxWithdrawable, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent 5 Transactions Mini Ledger -->
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex flex-col gap-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">5 Riwayat Transaksi Terakhir</h4>
                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($recentTransactions as $t)
                            <div class="py-2.5 flex items-center justify-between">
                                <div>
                                    <span class="font-bold {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $t->transaction_type === 'deposit' ? 'Setoran (+)' : 'Penarikan (-)' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block font-mono">{{ $t->transaction_date->format('d/m/Y H:i') }} WIB</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono font-bold text-slate-800">Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-slate-400 block font-mono">Sisa: Rp {{ number_format($t->balance_after, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-slate-400 text-xs">
                                Belum ada riwayat transaksi pada rekening ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Status Rekening: <strong class="text-emerald-600">Aktif</strong></span>
                    <span class="font-mono">Bank Mini Edubank</span>
                </div>
            </div>
        </div>

        <!-- Right: Transaction Operations (Tabs: Setor Tunai vs Tarik Tunai) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[28px] p-5 sm:p-6 md:p-8 border border-slate-200/80 shadow-[0_4px_30px_-5px_rgba(15,23,42,0.04)] flex flex-col gap-6">
                
                <div class="space-y-5">
                    <!-- Segmented Tabs Button -->
                    <div class="flex items-center p-1 bg-slate-100/90 rounded-2xl border border-slate-200/60 max-w-sm">
                        <button type="button" 
                                id="tabDepositBtn" 
                                onclick="switchTab('deposit')" 
                                class="flex-1 py-2 rounded-xl text-xs font-bold transition-all bg-white text-slate-900 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            <span>Setor Tunai</span>
                        </button>
                        <button type="button" 
                                id="tabWithdrawBtn" 
                                onclick="switchTab('withdraw')" 
                                class="flex-1 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            <span>Tarik Tunai</span>
                        </button>
                    </div>

                    <!-- ========================================================= -->
                    <!-- 1. FORM SETOR TUNAI                                       -->
                    <!-- ========================================================= -->
                    <form id="depositForm" method="POST" action="{{ route('teller.transactions.deposit', $saver->id) }}" class="space-y-4">
                        @csrf
                        
                        <div class="bg-emerald-50/70 border border-emerald-200/70 rounded-2xl p-3.5 text-xs text-emerald-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Setoran tunai otomatis menambah saldo rekening dan mencatat jurnal 101 Kas (D) & 201 Simpanan (K).</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                Nominal Setoran Tunai (Minimal Rp 10.000) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                                <input type="text" 
                                       inputmode="numeric"
                                       data-currency="true"
                                       name="amount" 
                                       id="deposit_amount" 
                                       placeholder="0" 
                                       required 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3 text-lg font-mono font-extrabold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                            </div>

                            <!-- Quick Nominal Pills -->
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="text-[10px] text-slate-500 font-semibold">Pilihan Cepat:</span>
                                <button type="button" onclick="setAmount('deposit', 10000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">10.000</button>
                                <button type="button" onclick="setAmount('deposit', 20000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">20.000</button>
                                <button type="button" onclick="setAmount('deposit', 50000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">50.000</button>
                                <button type="button" onclick="setAmount('deposit', 100000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">100.000</button>
                                <button type="button" onclick="setAmount('deposit', 200000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">200.000</button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                Keterangan / Catatan Transaksi (Opsional)
                            </label>
                            <input type="text" 
                                   name="description" 
                                   id="deposit_description"
                                   value="Setoran Tunai di Loket" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                            <button type="button" 
                                    onclick="triggerDepositConfirm()" 
                                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all hover:shadow-lg flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Proses Setoran Tunai & Cetak Struk</span>
                            </button>
                        </div>
                    </form>

                    <!-- ========================================================= -->
                    <!-- 2. FORM TARIK TUNAI                                       -->
                    <!-- ========================================================= -->
                    <form id="withdrawForm" method="POST" action="{{ route('teller.transactions.withdraw', $saver->id) }}" class="hidden space-y-4">
                        @csrf

                        <div class="bg-rose-50/70 border border-rose-200/70 rounded-2xl p-3.5 text-xs text-rose-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Penarikan tunai wajib otorisasi 6 digit PIN Siswa dan saldo sisa tidak boleh kurang dari Rp 10.000.</span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-slate-800">
                                    Nominal Penarikan Tunai <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-slate-500">
                                    Maksimal ditarik: <strong class="text-emerald-700 font-mono">Rp {{ number_format($maxWithdrawable, 0, ',', '.') }}</strong>
                                </span>
                            </div>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                                <input type="text" 
                                       inputmode="numeric"
                                       data-currency="true"
                                       name="amount" 
                                       id="withdraw_amount" 
                                       placeholder="0" 
                                       required 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3 text-lg font-mono font-extrabold text-slate-900 focus:bg-white focus:ring-2 focus:ring-rose-500/20">
                            </div>

                            <!-- Quick Nominal Pills -->
                            <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                <span class="text-[10px] text-slate-500 font-semibold">Pilihan Cepat:</span>
                                @if($maxWithdrawable >= 10000)<button type="button" onclick="setAmount('withdraw', 10000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">10.000</button>@endif
                                @if($maxWithdrawable >= 20000)<button type="button" onclick="setAmount('withdraw', 20000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">20.000</button>@endif
                                @if($maxWithdrawable >= 50000)<button type="button" onclick="setAmount('withdraw', 50000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">50.000</button>@endif
                                @if($maxWithdrawable >= 100000)<button type="button" onclick="setAmount('withdraw', 100000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono cursor-pointer">100.000</button>@endif
                                <button type="button" onclick="setAmount('withdraw', {{ $maxWithdrawable }})" class="px-2.5 py-1 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold font-mono cursor-pointer">Tarik Maksimal</button>
                            </div>
                        </div>

                        <!-- Hidden Authorization Token (Terisi otomatis saat disetujui di HP nasabah) -->
                        <input type="hidden" name="authorization_token" id="withdraw_authorization_token" value="">

                        <!-- PIN Keamanan Siswa & Mobile Push Authorization -->
                        <div class="space-y-2.5">
                            <label class="block text-xs font-bold text-slate-800">
                                PIN Nasabah <span class="text-rose-500">*</span>
                            </label>

                            <!-- Primary: Push Request to Mobile App -->
                            <div id="mobileAuthCard" class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-slate-900">
                                            Minta PIN ke HP Siswa
                                        </h5>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            Kirim permintaan otorisasi langsung ke aplikasi siswa
                                        </p>
                                    </div>
                                </div>
                                <button type="button" 
                                        id="btnRequestMobilePin"
                                        onclick="requestMobilePin()" 
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 shrink-0 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span>Minta PIN</span>
                                </button>
                            </div>

                            <!-- Success Banner (Hidden by default, shown when approved) -->
                            <div id="mobileAuthSuccessBadge" class="hidden p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div>
                                        <h6 class="text-xs font-bold text-emerald-900">PIN Terverifikasi</h6>
                                        <p class="text-[10px] text-emerald-600">Disetujui nasabah via aplikasi</p>
                                    </div>
                                </div>
                                <button type="button" onclick="resetMobileAuth()" class="text-xs text-emerald-700 hover:text-emerald-900 font-semibold cursor-pointer">
                                    Ulangi
                                </button>
                            </div>

                            <!-- Manual PIN Fallback Toggle -->
                            <div class="pt-0.5">
                                <button type="button" 
                                        onclick="toggleManualPinInput()" 
                                        class="text-[11px] text-slate-400 hover:text-slate-700 font-medium flex items-center gap-1 cursor-pointer transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    <span id="manualPinToggleText">Input PIN manual di loket</span>
                                </button>

                                <div id="manualPinContainer" class="hidden mt-2">
                                    <input type="password" 
                                           name="security_pin" 
                                           id="withdraw_pin"
                                           maxlength="6" 
                                           pattern="[0-9]{6}" 
                                           placeholder="6 Digit PIN Siswa" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono tracking-widest text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">
                                Keterangan / Catatan Transaksi (Opsional)
                            </label>
                            <input type="text" 
                                   name="description" 
                                   id="withdraw_description"
                                   value="Penarikan Tunai di Loket" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white">
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                            <button type="button" 
                                    onclick="triggerWithdrawConfirm()"
                                    {{ $maxWithdrawable < 10000 ? 'disabled' : '' }} 
                                    class="px-6 py-3 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white rounded-2xl text-xs font-bold shadow-md shadow-rose-600/20 transition-all hover:shadow-lg flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Otorisasi Penarikan Tunai & Serahkan Uang</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Note -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Layanan Transaksi Kas Bank Mini Siswa</span>
                    <span class="font-mono">Edubank SMKN 1 Bantul</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Menunggu Otorisasi PIN Real-Time di HP Siswa                        -->
<!-- ========================================================================= -->
<div id="mobilePinRequestModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
     onclick="if(event.target === this) cancelMobilePinRequest()">
    
    <div class="bg-white rounded-[24px] max-w-sm w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative text-center">
        
        <!-- Radar Pulsing Graphic -->
        <div class="relative w-20 h-20 mx-auto flex items-center justify-center">
            <span id="radarPulseRing" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-20"></span>
            <span id="radarInnerRing" class="animate-pulse absolute inline-flex h-16 w-16 rounded-full bg-blue-50"></span>
            <div id="radarIconBox" class="relative w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/25 transition-all">
                <svg id="radarPhoneIcon" class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <svg id="radarCheckIcon" class="w-6 h-6 hidden text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <div>
            <h3 id="radarTitle" class="text-base font-bold text-slate-900">
                Menunggu Konfirmasi
            </h3>
            <p id="radarSubtitle" class="text-xs text-slate-500 mt-0.5">
                Permintaan PIN telah dikirim ke aplikasi siswa
            </p>
        </div>

        <!-- Transaction Details Card -->
        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/70 text-left space-y-1.5 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-400">Siswa</span>
                <span class="font-bold text-slate-800">{{ $saver->full_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Nominal</span>
                <span id="radarAmountText" class="font-mono font-bold text-slate-900">Rp 0</span>
            </div>
        </div>

        <!-- Live Countdown & Status -->
        <div class="flex items-center justify-between text-xs px-1 text-slate-500">
            <div class="flex items-center gap-1.5">
                <span id="radarStatusDot" class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                <span id="radarStatusText" class="font-medium text-blue-700">Menunggu respons...</span>
            </div>
            <div class="font-mono text-slate-400" id="radarTimer">
                120s
            </div>
        </div>

        <!-- Cancel Button -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-center">
            <button type="button" 
                    id="btnCancelRadar"
                    onclick="cancelMobilePinRequest()" 
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-all cursor-pointer">
                Batal
            </button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Konfirmasi Setor Tunai (Centered Backdrop Blur)                    -->
<!-- ========================================================================= -->
<div id="confirmDepositModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeDepositConfirmModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Setoran Tunai</h3>
            </div>
            <button type="button" onclick="closeDepositConfirmModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <div class="space-y-2.5 text-xs text-slate-600">
            <p>Apakah Anda telah menerima fisik uang tunai dengan rincian berikut?</p>
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70 space-y-1.5 font-sans">
                <div class="flex justify-between">
                    <span class="text-slate-400">Nama Siswa:</span>
                    <span class="font-bold text-slate-800">{{ $saver->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">No. Rekening:</span>
                    <span class="font-mono font-bold text-blue-700">{{ $saver->account_number }}</span>
                </div>
                <div class="flex justify-between pt-1.5 border-t border-slate-200 text-sm">
                    <span class="font-bold text-slate-700">Nominal Setor:</span>
                    <span id="confirmDepositAmount" class="font-mono font-extrabold text-emerald-600">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeDepositConfirmModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" id="btnConfirmDeposit" onclick="submitDepositForm()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer inline-flex items-center justify-center min-w-[130px]">
                Ya, Proses Setoran
            </button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Konfirmasi Tarik Tunai (Centered Backdrop Blur)                    -->
<!-- ========================================================================= -->
<div id="confirmWithdrawModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeWithdrawConfirmModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Penarikan Tunai</h3>
            </div>
            <button type="button" onclick="closeWithdrawConfirmModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <div class="space-y-2.5 text-xs text-slate-600">
            <p>Apakah data penarikan tunai dan PIN nasabah telah valid untuk diserahkan?</p>
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/70 space-y-1.5 font-sans">
                <div class="flex justify-between">
                    <span class="text-slate-400">Nama Siswa:</span>
                    <span class="font-bold text-slate-800">{{ $saver->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">No. Rekening:</span>
                    <span class="font-mono font-bold text-blue-700">{{ $saver->account_number }}</span>
                </div>
                <div class="flex justify-between pt-1.5 border-t border-slate-200 text-sm">
                    <span class="font-bold text-slate-700">Uang Yang Diserahkan:</span>
                    <span id="confirmWithdrawAmount" class="font-mono font-extrabold text-rose-600">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeWithdrawConfirmModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" id="btnConfirmWithdraw" onclick="submitWithdrawForm()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer inline-flex items-center justify-center min-w-[130px]">
                Ya, Otorisasi & Tarik
            </button>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Notifikasi / Alert Error (Elegan & Konsisten Bento)                 -->
<!-- ========================================================================= -->
<div id="tellerAlertModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
     onclick="if(event.target === this) closeTellerAlertModal()">
    <div class="bg-white rounded-[24px] max-w-sm w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 text-center">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto shadow-xs" id="tellerAlertIconBox">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="tellerAlertIcon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-900" id="tellerAlertTitle">
                Perhatian
            </h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed" id="tellerAlertMessage">
                Silakan masukkan nominal penarikan terlebih dahulu.
            </p>
        </div>
        <div class="pt-2">
            <button type="button" 
                    id="tellerAlertBtn"
                    onclick="closeTellerAlertModal()" 
                    class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all cursor-pointer shadow-xs">
                Mengerti
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let alertModalCallback = null;

    function showTellerAlert(title, message, callback = null, type = 'error') {
        document.getElementById('tellerAlertTitle').innerText = title;
        document.getElementById('tellerAlertMessage').innerText = message;
        alertModalCallback = callback;

        const iconBox = document.getElementById('tellerAlertIconBox');
        if (type === 'warning') {
            iconBox.className = 'w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto shadow-xs';
        } else if (type === 'info') {
            iconBox.className = 'w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto shadow-xs';
        } else {
            iconBox.className = 'w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto shadow-xs';
        }

        const modal = document.getElementById('tellerAlertModal');
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTellerAlertModal() {
        const modal = document.getElementById('tellerAlertModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        if (alertModalCallback) {
            const cb = alertModalCallback;
            alertModalCallback = null;
            cb();
        }
    }

    function switchTab(type) {
        const depBtn = document.getElementById('tabDepositBtn');
        const withBtn = document.getElementById('tabWithdrawBtn');
        const depForm = document.getElementById('depositForm');
        const withForm = document.getElementById('withdrawForm');

        if (type === 'deposit') {
            depBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all bg-white text-slate-900 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer";
            withBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5 cursor-pointer";
            depForm.classList.remove('hidden');
            withForm.classList.add('hidden');
        } else {
            withBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all bg-white text-slate-900 shadow-sm flex items-center justify-center gap-1.5 cursor-pointer";
            depBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5 cursor-pointer";
            withForm.classList.remove('hidden');
            depForm.classList.add('hidden');
        }
    }

    function setAmount(type, val) {
        if (type === 'deposit') {
            if (window.setCurrencyInputValue) {
                window.setCurrencyInputValue('deposit_amount', val);
            } else {
                document.getElementById('deposit_amount').value = val;
            }
        } else {
            if (window.setCurrencyInputValue) {
                window.setCurrencyInputValue('withdraw_amount', val);
            } else {
                document.getElementById('withdraw_amount').value = val;
            }
        }
    }

    // Modal Confirmation Handlers for Deposit
    function triggerDepositConfirm() {
        const amountInput = document.getElementById('deposit_amount');
        const rawAmount = String(amountInput.value || '').replace(/[^0-9]/g, '');
        const amountNum = parseInt(rawAmount, 10);

        if (!amountNum || amountNum < 10000) {
            showTellerAlert('Nominal Belum Diisi', 'Silakan masukkan nominal setoran minimal Rp 10.000.', () => {
                amountInput.focus();
            });
            return;
        }

        const formatted = new Intl.NumberFormat('id-ID').format(amountNum);
        document.getElementById('confirmDepositAmount').innerText = 'Rp ' + formatted;
        const modal = document.getElementById('confirmDepositModal');
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDepositConfirmModal() {
        const modal = document.getElementById('confirmDepositModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    let isSubmittingTransaction = false;

    function submitDepositForm() {
        if (isSubmittingTransaction) return;

        const form = document.getElementById('depositForm');
        const amountInput = document.getElementById('deposit_amount');
        const rawAmount = String(amountInput.value || '').replace(/[^0-9]/g, '');
        let hiddenAmount = form.querySelector('input[type="hidden"][name="amount"]');
        if (!hiddenAmount) {
            hiddenAmount = document.createElement('input');
            hiddenAmount.type = 'hidden';
            hiddenAmount.name = 'amount';
            form.appendChild(hiddenAmount);
        }
        hiddenAmount.value = rawAmount;

        // Anti double-entry guard: disable button immediately & show spinner
        isSubmittingTransaction = true;
        const confirmBtn = document.getElementById('btnConfirmDeposit');
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-60', 'pointer-events-none');
            confirmBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `;
        }

        form.submit();
    }

    // Modal Confirmation Handlers for Withdraw
    function triggerWithdrawConfirm() {
        const form = document.getElementById('withdrawForm');
        const amountInput = document.getElementById('withdraw_amount');
        const rawAmount = String(amountInput.value || '').replace(/[^0-9]/g, '');
        const amountNum = parseInt(rawAmount, 10);
        const maxWithdrawable = {{ $maxWithdrawable }};

        if (!amountNum || amountNum < 10000) {
            showTellerAlert('Nominal Belum Diisi', 'Silakan masukkan nominal penarikan minimal Rp 10.000.', () => {
                amountInput.focus();
            });
            return;
        }

        if (amountNum > maxWithdrawable) {
            showTellerAlert('Saldo Tidak Cukup', 'Nominal penarikan melebihi batas saldo maksimal yang dapat ditarik (Rp ' + maxWithdrawable.toLocaleString('id-ID') + ').', () => {
                amountInput.focus();
            });
            return;
        }

        const authToken = document.getElementById('withdraw_authorization_token').value;
        const manualPinInput = document.getElementById('withdraw_pin');
        const manualPin = manualPinInput ? manualPinInput.value.trim() : '';

        // Must have either mobile auth approved OR 6-digit manual PIN
        if (!authToken && (!manualPin || manualPin.length !== 6)) {
            showTellerAlert('Otorisasi PIN Diperlukan', 'Silakan klik "Minta PIN" ke HP siswa atau masukkan 6 digit PIN manual di loket.', () => {
                if (manualPinInput) {
                    document.getElementById('manualPinContainer').classList.remove('hidden');
                    manualPinInput.focus();
                }
            });
            return;
        }

        const formatted = new Intl.NumberFormat('id-ID').format(amountNum);
        document.getElementById('confirmWithdrawAmount').innerText = 'Rp ' + formatted;
        
        const modal = document.getElementById('confirmWithdrawModal');
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeWithdrawConfirmModal() {
        const modal = document.getElementById('confirmWithdrawModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitWithdrawForm() {
        if (isSubmittingTransaction) return;

        const form = document.getElementById('withdrawForm');
        const amountInput = document.getElementById('withdraw_amount');
        const rawAmount = String(amountInput.value || '').replace(/[^0-9]/g, '');
        let hiddenAmount = form.querySelector('input[type="hidden"][name="amount"]');
        if (!hiddenAmount) {
            hiddenAmount = document.createElement('input');
            hiddenAmount.type = 'hidden';
            hiddenAmount.name = 'amount';
            form.appendChild(hiddenAmount);
        }
        hiddenAmount.value = rawAmount;

        // Anti double-entry guard: disable button immediately & show spinner
        isSubmittingTransaction = true;
        const confirmBtn = document.getElementById('btnConfirmWithdraw');
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-60', 'pointer-events-none');
            confirmBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `;
        }

        form.submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTellerAlertModal();
            closeDepositConfirmModal();
            closeWithdrawConfirmModal();
            cancelMobilePinRequest();
        }
    });

    // =========================================================================
    // Real-Time Mobile Push PIN Authorization Handlers
    // =========================================================================
    let currentPinRequestId = null;
    let pinPollInterval = null;
    let pinCountdownTimer = null;
    let pinRemainingSeconds = 120;

    function toggleManualPinInput() {
        const container = document.getElementById('manualPinContainer');
        const pinInput = document.getElementById('withdraw_pin');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            if (pinInput) pinInput.focus();
        } else {
            container.classList.add('hidden');
        }
    }

    function resetMobileAuth() {
        document.getElementById('withdraw_authorization_token').value = '';
        document.getElementById('mobileAuthSuccessBadge').classList.add('hidden');
        document.getElementById('mobileAuthCard').classList.remove('hidden');
        const btn = document.getElementById('btnRequestMobilePin');
        btn.disabled = false;
        btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> <span>Minta PIN</span>`;
    }

    async function requestMobilePin() {
        const amountInput = document.getElementById('withdraw_amount');
        const rawAmount = String(amountInput.value || '').replace(/[^0-9]/g, '');
        const amountNum = parseInt(rawAmount, 10);
        const maxWithdrawable = {{ $maxWithdrawable }};

        if (!amountNum || amountNum < 10000) {
            showTellerAlert(
                'Nominal Belum Diisi',
                'Silakan masukkan nominal penarikan minimal Rp 10.000 terlebih dahulu sebelum meminta PIN ke HP siswa.',
                () => {
                    amountInput.focus();
                }
            );
            return;
        }

        if (amountNum > maxWithdrawable) {
            showTellerAlert(
                'Saldo Tidak Cukup',
                'Nominal penarikan melebihi batas saldo maksimal yang dapat ditarik (Rp ' + maxWithdrawable.toLocaleString('id-ID') + ').',
                () => {
                    amountInput.focus();
                }
            );
            return;
        }

        const btn = document.getElementById('btnRequestMobilePin');
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Menghubungi HP...</span>`;

        try {
            const res = await fetch("{{ route('teller.transactions.request-pin', $saver->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    amount: amountNum,
                    description: document.getElementById('withdraw_description').value || 'Penarikan Tunai di Loket'
                })
            });

            const data = await res.json();
            if (!data.success) {
                showTellerAlert('Gagal Meminta PIN', data.message || 'Gagal mengirim permintaan PIN ke HP siswa.');
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> <span>Minta PIN</span>`;
                return;
            }

            currentPinRequestId = data.request_id;
            pinRemainingSeconds = 120;

            // Setup and open radar modal
            const formatted = new Intl.NumberFormat('id-ID').format(amountNum);
            document.getElementById('radarAmountText').innerText = 'Rp ' + formatted;
            document.getElementById('radarStatusText').innerText = 'Menunggu siswa memasukkan PIN...';
            document.getElementById('radarStatusText').className = 'font-semibold text-blue-700';
            document.getElementById('radarTimer').innerText = '120s';
            
            // Reset modal graphics
            document.getElementById('radarIconBox').className = 'relative w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/25 transition-all';
            document.getElementById('radarPhoneIcon').classList.remove('hidden');
            document.getElementById('radarCheckIcon').classList.add('hidden');
            document.getElementById('radarPulseRing').classList.remove('hidden');
            document.getElementById('radarTitle').innerText = 'Menunggu Konfirmasi';
            document.getElementById('radarSubtitle').innerText = 'Permintaan PIN telah dikirim ke aplikasi siswa';
            document.getElementById('radarStatusText').innerText = 'Menunggu respons...';
            document.getElementById('radarStatusText').className = 'font-medium text-blue-700';

            const modal = document.getElementById('mobilePinRequestModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Start Countdown
            clearInterval(pinCountdownTimer);
            pinCountdownTimer = setInterval(() => {
                pinRemainingSeconds--;
                document.getElementById('radarTimer').innerText = pinRemainingSeconds + 's';
                if (pinRemainingSeconds <= 0) {
                    clearInterval(pinCountdownTimer);
                    handlePinTimeout();
                }
            }, 1000);

            // Start Polling every 1.5s
            clearInterval(pinPollInterval);
            pinPollInterval = setInterval(pollPinStatus, 1500);

        } catch (err) {
            console.error(err);
            showTellerAlert('Gangguan Jaringan', 'Terjadi kesalahan jaringan saat meminta PIN. Silakan periksa koneksi.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> <span>Minta PIN</span>`;
        }
    }

    async function pollPinStatus() {
        if (!currentPinRequestId) return;

        try {
            const url = `{{ url('/teller/transactions/' . $saver->id . '/pin-status') }}/${currentPinRequestId}`;
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.status === 'approved') {
                clearInterval(pinPollInterval);
                clearInterval(pinCountdownTimer);
                handlePinApproved(data.token);
            } else if (data.status === 'rejected') {
                clearInterval(pinPollInterval);
                clearInterval(pinCountdownTimer);
                handlePinRejected();
            } else if (data.status === 'expired') {
                clearInterval(pinPollInterval);
                clearInterval(pinCountdownTimer);
                handlePinTimeout();
            }
        } catch (e) {
            console.warn('Poll error:', e);
        }
    }

    function handlePinApproved(token) {
        // Success animation
        document.getElementById('radarPulseRing').classList.add('hidden');
        document.getElementById('radarInnerRing').classList.add('hidden');
        document.getElementById('radarPhoneIcon').classList.add('hidden');
        document.getElementById('radarCheckIcon').classList.remove('hidden');
        document.getElementById('radarIconBox').className = 'relative w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-500/30 transition-all scale-105';
        document.getElementById('radarTitle').innerText = 'PIN Disetujui';
        document.getElementById('radarSubtitle').innerText = 'Otorisasi nasabah berhasil';
        document.getElementById('radarStatusText').innerText = 'Selesai';
        document.getElementById('radarStatusText').className = 'font-semibold text-emerald-700';

        // Set token
        document.getElementById('withdraw_authorization_token').value = token;
        
        // Remove required from manual pin input
        const pinInput = document.getElementById('withdraw_pin');
        if (pinInput) pinInput.removeAttribute('required');

        // Close modal after 1s and show green badge
        setTimeout(() => {
            closeMobilePinRequestModal();
            document.getElementById('mobileAuthCard').classList.add('hidden');
            document.getElementById('mobileAuthSuccessBadge').classList.remove('hidden');
        }, 1000);
    }

    function handlePinRejected() {
        closeMobilePinRequestModal();
        showTellerAlert('Penarikan Ditolak', 'Nasabah menolak permintaan otorisasi di HP mereka.');
    }

    function handlePinTimeout() {
        closeMobilePinRequestModal();
        showTellerAlert('Waktu Otorisasi Habis', 'Siswa belum memasukkan PIN di HP. Silakan coba minta lagi atau input PIN manual.');
    }

    async function cancelMobilePinRequest() {
        clearInterval(pinPollInterval);
        clearInterval(pinCountdownTimer);

        if (currentPinRequestId) {
            try {
                const url = `{{ url('/teller/transactions/' . $saver->id . '/pin-cancel') }}/${currentPinRequestId}`;
                await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
            } catch (e) {}
            currentPinRequestId = null;
        }

        closeMobilePinRequestModal();
    }

    function closeMobilePinRequestModal() {
        const modal = document.getElementById('mobilePinRequestModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endpush
@endsection
