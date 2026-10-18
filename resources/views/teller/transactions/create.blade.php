@extends('layouts.app')

@section('title', 'Pelayanan Transaksi: ' . $saver->full_name)
@section('page_title', 'Loket Transaksi Nasabah')

@section('content')
<div class="space-y-4 pb-8">

    <!-- Top Action Toolbar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('teller.transactions.scanner') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Scan / Cari Nasabah Lain</span>
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400 font-mono">Teller: {{ Auth::user()->name }}</span>
        </div>
    </div>

    <!-- Bento Grid: 2 Columns (Student Profile vs Service Desk Tabs) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        
        <!-- Left: Student Savings Profile & Balance Specs -->
        <div class="lg:col-span-5 space-y-4">
            
            <!-- Student Header Profile Card -->
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-4">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 mb-1">
                            {{ $saver->major }} &bull; {{ $saver->cohort }}
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
                <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white space-y-3 shadow-md shadow-slate-900/10">
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block tracking-wider">Total Saldo Rekening</span>
                        <div class="text-2xl font-extrabold font-mono text-white mt-0.5">
                            Rp {{ number_format($saver->balance, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2.5 border-t border-white/10 text-xs">
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
            <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-2.5">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">5 Transaksi Terakhir</h4>
                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($recentTransactions as $t)
                        <div class="py-2 flex items-center justify-between">
                            <div>
                                <span class="font-bold {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $t->transaction_type === 'deposit' ? 'Setoran (+)' : 'Penarikan (-)' }}
                                </span>
                                <span class="text-[10px] text-slate-400 block">{{ $t->transaction_date->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-slate-800">Rp {{ number_format($t->amount, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-400 block">Sisa: Rp {{ number_format($t->balance_after, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-slate-400 text-xs">
                            Belum ada riwayat transaksi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right: Transaction Operations (Tabs: Setor Tunai vs Tarik Tunai) -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[28px] p-6 border border-slate-200/80 shadow-[0_4px_30px_-5px_rgba(15,23,42,0.04)] space-y-5">
                
                <!-- Segmented Tabs Button -->
                <div class="flex items-center p-1 bg-slate-100/90 rounded-2xl border border-slate-200/60 max-w-sm">
                    <button type="button" 
                            id="tabDepositBtn" 
                            onclick="switchTab('deposit')" 
                            class="flex-1 py-2 rounded-xl text-xs font-bold transition-all bg-white text-slate-900 shadow-sm flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        <span>Setor Tunai</span>
                    </button>
                    <button type="button" 
                            id="tabWithdrawBtn" 
                            onclick="switchTab('withdraw')" 
                            class="flex-1 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        <span>Tarik Tunai</span>
                    </button>
                </div>

                <!-- ========================================================= -->
                <!-- 1. FORM SETOR TUNAI                                       -->
                <!-- ========================================================= -->
                <form id="depositForm" method="POST" action="{{ route('teller.transactions.deposit', $saver->id) }}" class="space-y-5">
                    @csrf
                    
                    <div class="bg-emerald-50/70 border border-emerald-200/70 rounded-2xl p-3.5 text-xs text-emerald-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Setoran tunai otomatis menambah saldo rekening dan mencatat jurnal 101 Kas (D) & 201 Simpanan (K).</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                            Nominal Setoran Tunai (Minimal Rp 10.000) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input type="number" 
                                   name="amount" 
                                   id="deposit_amount" 
                                   min="10000" 
                                   step="1000" 
                                   placeholder="0" 
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3 text-lg font-mono font-extrabold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                        </div>

                        <!-- Quick Nominal Pills -->
                        <div class="flex flex-wrap items-center gap-2 mt-2.5">
                            <span class="text-[10px] text-slate-500 font-semibold">Pilihan Cepat:</span>
                            <button type="button" onclick="setAmount('deposit', 10000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">10.000</button>
                            <button type="button" onclick="setAmount('deposit', 20000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">20.000</button>
                            <button type="button" onclick="setAmount('deposit', 50000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">50.000</button>
                            <button type="button" onclick="setAmount('deposit', 100000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">100.000</button>
                            <button type="button" onclick="setAmount('deposit', 200000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">200.000</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                            Keterangan / Catatan Transaksi (Opsional)
                        </label>
                        <input type="text" 
                               name="description" 
                               value="Setoran Tunai di Loket" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-bold shadow-md shadow-emerald-600/20 transition-all hover:shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Proses Setoran Tunai & Cetak Struk</span>
                        </button>
                    </div>
                </form>

                <!-- ========================================================= -->
                <!-- 2. FORM TARIK TUNAI                                       -->
                <!-- ========================================================= -->
                <form id="withdrawForm" method="POST" action="{{ route('teller.transactions.withdraw', $saver->id) }}" class="hidden space-y-5">
                    @csrf

                    <div class="bg-rose-50/70 border border-rose-200/70 rounded-2xl p-3.5 text-xs text-rose-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Penarikan tunai wajib otorisasi 6 digit PIN Siswa dan saldo sisa tidak boleh kurang dari Rp 10.000.</span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-800">
                                Nominal Penarikan Tunai *
                            </label>
                            <span class="text-[11px] text-slate-500">
                                Maksimal ditarik: <strong class="text-emerald-700 font-mono">Rp {{ number_format($maxWithdrawable, 0, ',', '.') }}</strong>
                            </span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input type="number" 
                                   name="amount" 
                                   id="withdraw_amount" 
                                   min="10000" 
                                   max="{{ $maxWithdrawable }}" 
                                   step="1000" 
                                   placeholder="0" 
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3 text-lg font-mono font-extrabold text-slate-900 focus:bg-white focus:ring-2 focus:ring-rose-500/20">
                        </div>

                        <!-- Quick Nominal Pills -->
                        <div class="flex flex-wrap items-center gap-2 mt-2.5">
                            <span class="text-[10px] text-slate-500 font-semibold">Pilihan Cepat:</span>
                            @if($maxWithdrawable >= 10000)<button type="button" onclick="setAmount('withdraw', 10000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">10.000</button>@endif
                            @if($maxWithdrawable >= 20000)<button type="button" onclick="setAmount('withdraw', 20000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">20.000</button>@endif
                            @if($maxWithdrawable >= 50000)<button type="button" onclick="setAmount('withdraw', 50000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">50.000</button>@endif
                            @if($maxWithdrawable >= 100000)<button type="button" onclick="setAmount('withdraw', 100000)" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-mono">100.000</button>@endif
                            <button type="button" onclick="setAmount('withdraw', {{ $maxWithdrawable }})" class="px-2.5 py-1 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold font-mono">Tarik Maksimal</button>
                        </div>
                    </div>

                    <!-- PIN Keamanan Siswa -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                            PIN Keamanan Nasabah (6 Digit Angka) *
                        </label>
                        <input type="password" 
                               name="security_pin" 
                               maxlength="6" 
                               pattern="[0-9]{6}" 
                               placeholder="******" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-base font-mono tracking-widest text-slate-900 focus:bg-white focus:ring-2 focus:ring-rose-500/20">
                        <p class="text-[11px] text-slate-400 mt-1">Minta siswa untuk memasukkan PIN secara pribadi pada keypad / keyboard loket.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                            Keterangan / Catatan Transaksi (Opsional)
                        </label>
                        <input type="text" 
                               name="description" 
                               value="Penarikan Tunai di Loket" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white">
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" {{ $maxWithdrawable < 10000 ? 'disabled' : '' }} class="px-6 py-3 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white rounded-2xl text-xs font-bold shadow-md shadow-rose-600/20 transition-all hover:shadow-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Otorisasi Penarikan Tunai & Serahkan Uang</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(type) {
        const depBtn = document.getElementById('tabDepositBtn');
        const withBtn = document.getElementById('tabWithdrawBtn');
        const depForm = document.getElementById('depositForm');
        const withForm = document.getElementById('withdrawForm');

        if (type === 'deposit') {
            depBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all bg-white text-slate-900 shadow-sm flex items-center justify-center gap-1.5";
            withBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5";
            depForm.classList.remove('hidden');
            withForm.classList.add('hidden');
        } else {
            withBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all bg-white text-slate-900 shadow-sm flex items-center justify-center gap-1.5";
            depBtn.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5";
            withForm.classList.remove('hidden');
            depForm.classList.add('hidden');
        }
    }

    function setAmount(type, val) {
        if (type === 'deposit') {
            document.getElementById('deposit_amount').value = val;
        } else {
            document.getElementById('withdraw_amount').value = val;
        }
    }
</script>
@endpush
@endsection
