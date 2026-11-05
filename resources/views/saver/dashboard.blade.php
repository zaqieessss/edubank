@extends('layouts.saver')

@section('title', 'Beranda Tabungan Siswa')

@php
    $hour = now()->hour;
    if ($hour >= 4 && $hour < 11) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour >= 11 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }

    $rawAccount = (string) $saver->account_number;
    $spacedAccount = trim(chunk_split($rawAccount, 4, ' '));
@endphp

@section('content')
<div class="px-5 pt-5 pb-6 space-y-5">

    <!-- ========================================================================= -->
    <!-- 1. TOP USER PROFILE HEADER (Identik Flutter HomeScreen)                   -->
    <!-- ========================================================================= -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <!-- Dynamic Avatar with Royal Blue Gradient & Glow -->
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 border-2 border-white shadow-md shadow-blue-600/30 flex items-center justify-center text-white font-black text-lg select-none shrink-0">
                {{ strtoupper(substr($saver->full_name, 0, 1)) }}
            </div>
            
            <!-- User Info: Greeting & Siswa Badge -->
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-semibold text-slate-500">{{ $greeting }}</span>
                    <span class="px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[10px] font-bold">Siswa</span>
                </div>
                <h1 class="text-base font-extrabold text-slate-900 tracking-tight line-clamp-1">
                    {{ $saver->full_name }}
                </h1>
            </div>
        </div>

        <!-- Quick Action QR Button (Top Right Header) -->
        <button type="button" 
                onclick="openQrSheet()" 
                class="w-10 h-10 rounded-2xl bg-white border border-slate-200/90 shadow-sm shadow-blue-900/5 flex items-center justify-center text-blue-600 hover:bg-slate-50 active:scale-95 transition-all cursor-pointer"
                title="Tampilkan QR Rekening">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. VIRTUAL STUDENT CARD (Identik Flutter VirtualCardWidget)                -->
    <!-- ========================================================================= -->
    <div class="w-full rounded-[28px] bg-gradient-to-br from-[#1E3A8A] via-[#1D4ED8] to-[#2563EB] text-white p-6 shadow-xl shadow-blue-700/25 relative overflow-hidden select-none border border-white/10">
        
        <!-- Holographic Decorative Light Circles -->
        <div class="absolute -top-12 -right-10 w-44 h-44 rounded-full bg-blue-300/20 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-14 -left-8 w-40 h-40 rounded-full bg-blue-200/15 blur-2xl pointer-events-none"></div>

        <!-- Top Row: Logo, EMV Chip & Contactless -->
        <div class="relative z-10 flex items-center justify-between pb-3">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="w-7 h-7 object-contain drop-shadow-sm">
                <div>
                    <span class="text-xs font-black tracking-wider block leading-tight">EDUBANK</span>
                    <span class="text-[9px] font-bold text-blue-200 uppercase tracking-widest block">Platinum Saver</span>
                </div>
            </div>

            <!-- Contactless Icon & EMV Chip Illustration -->
            <div class="flex items-center gap-2.5">
                <!-- Contactless wave -->
                <svg class="w-5 h-5 text-blue-200/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10 10 0 0114.142 0M1.393 9.393a15 15 0 0121.214 0"/>
                </svg>
                <!-- Gold Chip -->
                <div class="w-8 h-6 rounded-md bg-gradient-to-tr from-amber-400 via-yellow-200 to-amber-300 border border-amber-300/60 shadow-xs relative flex items-center justify-center overflow-hidden">
                    <div class="w-full h-[1px] bg-amber-600/40 absolute top-2"></div>
                    <div class="w-full h-[1px] bg-amber-600/40 absolute bottom-2"></div>
                    <div class="w-[1px] h-full bg-amber-600/40 absolute left-3"></div>
                </div>
            </div>
        </div>

        <!-- Center Row: Saldo Tabungan with Show/Hide Eye Toggle -->
        <div class="relative z-10 py-3">
            <div class="flex items-center justify-between text-blue-100 text-[11px] font-medium mb-1">
                <span>Saldo Rekening Tabungan</span>
                <button type="button" 
                        onclick="toggleCardBalance()" 
                        class="text-blue-100 hover:text-white flex items-center gap-1 cursor-pointer">
                    <svg id="cardEyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="cardEyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    <span id="cardEyeLabel" class="text-[10px]">Sembunyikan</span>
                </button>
            </div>
            
            <p id="cardBalanceText" class="text-2xl md:text-3xl font-extrabold font-mono tracking-tight text-white drop-shadow-sm">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </p>
            <p id="cardBalanceHidden" class="text-2xl md:text-3xl font-extrabold font-mono tracking-tight text-white drop-shadow-sm hidden">
                Rp ••••••••
            </p>
        </div>

        <!-- Bottom Row: Card Details & Account Number -->
        <div class="relative z-10 pt-2 border-t border-white/15 flex items-end justify-between">
            <div>
                <!-- Nomor Rekening with Copy -->
                <div class="flex items-center gap-1.5 mb-1">
                    <span class="text-xs font-mono font-bold tracking-wider text-blue-100">{{ $spacedAccount }}</span>
                    <button type="button" onclick="copyToClipboard('{{ $saver->account_number }}')" class="text-blue-200 hover:text-white cursor-pointer" title="Salin Rekening">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </div>
                <p class="text-xs font-bold text-white tracking-wide uppercase line-clamp-1">
                    {{ $saver->full_name }}
                </p>
                <p class="text-[10px] text-blue-200">
                    {{ $saver->class_name }} &bull; {{ $saver->major }}
                </p>
            </div>

            <div class="text-right">
                <span class="text-[9px] text-blue-200 uppercase block font-semibold">Berlaku</span>
                <span class="text-[11px] font-mono font-bold text-white">06/28</span>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 3. QUICK ACTION SHORTCUTS (4 Elegan Buttons)                             -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-4 gap-2.5">
        <!-- Shortcut 1: QR Loket -->
        <button type="button" 
                onclick="openQrSheet()" 
                class="bg-white rounded-2xl p-3 border border-slate-200/80 shadow-xs flex flex-col items-center gap-1.5 hover:bg-blue-50/50 hover:border-blue-200 active:scale-95 transition-all cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
            <span class="text-[11px] font-bold text-slate-700">QR Loket</span>
        </button>

        <!-- Shortcut 2: Setor -->
        <button type="button" 
                onclick="openSetorModal()" 
                class="bg-white rounded-2xl p-3 border border-slate-200/80 shadow-xs flex flex-col items-center gap-1.5 hover:bg-emerald-50/50 hover:border-emerald-200 active:scale-95 transition-all cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </div>
            <span class="text-[11px] font-bold text-slate-700">Setor</span>
        </button>

        <!-- Shortcut 3: Tarik -->
        <button type="button" 
                onclick="openTarikModal()" 
                class="bg-white rounded-2xl p-3 border border-slate-200/80 shadow-xs flex flex-col items-center gap-1.5 hover:bg-rose-50/50 hover:border-rose-200 active:scale-95 transition-all cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            </div>
            <span class="text-[11px] font-bold text-slate-700">Tarik</span>
        </button>

        <!-- Shortcut 4: Mutasi -->
        <a href="{{ route('saver.mutation') }}" 
           class="bg-white rounded-2xl p-3 border border-slate-200/80 shadow-xs flex flex-col items-center gap-1.5 hover:bg-amber-50/50 hover:border-amber-200 active:scale-95 transition-all">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <span class="text-[11px] font-bold text-slate-700">Mutasi</span>
        </a>
    </div>

    <!-- ========================================================================= -->
    <!-- 4. FINANCIAL INSIGHTS BENTO CARD (Identik Flutter Home Bento)              -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center divide-x divide-slate-100">
        <!-- Setoran Bulan Ini -->
        <div class="flex-1 pr-3 flex flex-col">
            <div class="flex items-center gap-1.5 mb-1">
                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
                <span class="text-[11px] font-semibold text-slate-500">Setoran Bulan Ini</span>
            </div>
            <p class="text-sm font-extrabold font-mono text-emerald-600 pl-6">
                +Rp {{ number_format($monthlyDeposits, 0, ',', '.') }}
            </p>
        </div>

        <!-- Penarikan Bulan Ini -->
        <div class="flex-1 pl-3 flex flex-col">
            <div class="flex items-center gap-1.5 mb-1">
                <div class="w-5 h-5 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
                <span class="text-[11px] font-semibold text-slate-500">Penarikan Bulan Ini</span>
            </div>
            <p class="text-sm font-extrabold font-mono text-rose-600 pl-6">
                -Rp {{ number_format($monthlyWithdrawals, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 5. RECENT TRANSACTIONS (Aktivitas Terakhir)                               -->
    <!-- ========================================================================= -->
    <div class="space-y-3 pt-1">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 tracking-tight">Aktivitas Terakhir</h2>
            <a href="{{ route('saver.mutation') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-0.5">
                <span>Lihat Semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="space-y-2.5">
            @forelse($recentTransactions as $t)
                @php
                    $isDeposit = ($t->transaction_type === 'deposit');
                @endphp
                <div class="bg-white rounded-2xl p-3.5 border border-slate-200/70 shadow-xs flex items-center justify-between gap-3 hover:border-slate-300 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $isDeposit ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                @if($isDeposit)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 line-clamp-1">
                                {{ $isDeposit ? 'Setoran Tunai di Loket' : 'Penarikan Kas Tabungan' }}
                            </p>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ \Carbon\Carbon::parse($t->transaction_date)->translatedFormat('d M Y') }} &bull; {{ $t->created_at->format('H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <span class="text-xs font-extrabold font-mono {{ $isDeposit ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $isDeposit ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                        </span>
                        <span class="block text-[9px] text-slate-400 font-medium capitalize">
                            {{ $t->status ?? 'Sukses' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 border border-slate-200/80 text-center text-slate-400 space-y-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-xs font-medium">Belum ada aktivitas transaksi.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Modal: Petunjuk Setoran Tunai -->
<div id="setorModal" class="hidden fixed inset-0 z-50 items-end md:items-center justify-center bg-slate-900/60 backdrop-blur-xs p-0 md:p-4" onclick="if(event.target === this) closeSetorModal()">
    <div class="w-full max-w-[400px] bg-white rounded-t-[32px] md:rounded-[28px] p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Petunjuk Setor Tunai</h3>
            </div>
            <button type="button" onclick="closeSetorModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>
        <div class="space-y-3 text-xs text-slate-600">
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 font-bold text-[10px] flex items-center justify-center shrink-0">1</span>
                <span>Bawa uang tunai fisik yang ingin disetorkan ke loket Bank Mini SMKN 1 Bantul.</span>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 font-bold text-[10px] flex items-center justify-center shrink-0">2</span>
                <span>Tunjukkan <strong>QR Rekening</strong> Anda kepada petugas Teller untuk pemindaian otomatis.</span>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 font-bold text-[10px] flex items-center justify-center shrink-0">3</span>
                <span>Teller memverifikasi dan mencetak struk transaksi. Saldo langsung bertambah secara instan!</span>
            </div>
        </div>
        <button type="button" onclick="closeSetorModal()" class="w-full py-2.5 bg-slate-900 text-white rounded-xl text-xs font-semibold">Mengerti</button>
    </div>
</div>

<!-- Modal: Petunjuk Penarikan Tunai & Saldo Mengendap -->
<div id="tarikModal" class="hidden fixed inset-0 z-50 items-end md:items-center justify-center bg-slate-900/60 backdrop-blur-xs p-0 md:p-4" onclick="if(event.target === this) closeTarikModal()">
    <div class="w-full max-w-[400px] bg-white rounded-t-[32px] md:rounded-[28px] p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Batas Penarikan Tunai</h3>
            </div>
            <button type="button" onclick="closeTarikModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>
        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 space-y-1.5 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Maksimal Dapat Ditarik:</span>
                <span class="font-mono font-bold text-emerald-600 text-sm">Rp {{ number_format($maxWithdrawable, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Saldo Wajib Mengendap:</span>
                <span class="font-mono font-bold text-slate-700">Rp {{ number_format($holdingBalance, 0, ',', '.') }}</span>
            </div>
        </div>
        <p class="text-xs text-slate-500 leading-relaxed">
            Sesuai regulasi Bank Mini, saldo minimum Rp 10.000 wajib tersimpan di rekening sebagai saldo aktif buku tabungan.
        </p>
        <button type="button" onclick="closeTarikModal()" class="w-full py-2.5 bg-slate-900 text-white rounded-xl text-xs font-semibold">Mengerti</button>
    </div>
</div>

@push('scripts')
<script>
    let isBalanceHidden = false;
    function toggleCardBalance() {
        isBalanceHidden = !isBalanceHidden;
        const balText = document.getElementById('cardBalanceText');
        const balHidden = document.getElementById('cardBalanceHidden');
        const eyeOpen = document.getElementById('cardEyeOpen');
        const eyeClosed = document.getElementById('cardEyeClosed');
        const eyeLabel = document.getElementById('cardEyeLabel');

        if (isBalanceHidden) {
            balText.classList.add('hidden');
            balHidden.classList.remove('hidden');
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
            eyeLabel.innerText = 'Tampilkan';
        } else {
            balText.classList.remove('hidden');
            balHidden.classList.add('hidden');
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
            eyeLabel.innerText = 'Sembunyikan';
        }
    }

    function openSetorModal() {
        const m = document.getElementById('setorModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeSetorModal() {
        const m = document.getElementById('setorModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function openTarikModal() {
        const m = document.getElementById('tarikModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeTarikModal() {
        const m = document.getElementById('tarikModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
</script>
@endpush
@endsection
