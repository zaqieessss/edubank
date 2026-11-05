@extends('layouts.saver')

@section('title', 'Mutasi Transaksi - Edubank')

@php
    $currentType = request('type', 'all');
@endphp

@section('content')
<div class="px-5 pt-5 pb-6 space-y-4">

    <!-- Header Section (Identik Flutter MutationScreen AppBar) -->
    <div class="flex items-center gap-3">
        <a href="{{ route('saver.dashboard') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-center text-slate-600 hover:bg-slate-50 active:scale-95 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-lg font-extrabold text-slate-900 tracking-tight leading-tight">Mutasi Transaksi</h1>
            <p class="text-xs text-slate-400">Riwayat keluar masuk saldo tabungan</p>
        </div>
    </div>

    <!-- Monthly Summary Bento Bar -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center divide-x divide-slate-100">
        <div class="flex-1 pr-3">
            <span class="text-[10px] font-semibold text-slate-400 block uppercase">Total Masuk</span>
            <p class="text-sm font-extrabold font-mono text-emerald-600 mt-0.5">
                +Rp {{ number_format($monthlyDeposits, 0, ',', '.') }}
            </p>
        </div>
        <div class="flex-1 pl-3">
            <span class="text-[10px] font-semibold text-slate-400 block uppercase">Total Keluar</span>
            <p class="text-sm font-extrabold font-mono text-rose-600 mt-0.5">
                -Rp {{ number_format($monthlyWithdrawals, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Filter Type Pills (Semua, Setoran, Penarikan) -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
        <a href="{{ route('saver.mutation', array_merge(request()->except('type', 'page'), ['type' => 'all'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ ($currentType === 'all' || !$currentType) ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua
        </a>
        <a href="{{ route('saver.mutation', array_merge(request()->except('type', 'page'), ['type' => 'deposit'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'deposit' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Setoran
        </a>
        <a href="{{ route('saver.mutation', array_merge(request()->except('type', 'page'), ['type' => 'withdrawal'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $currentType === 'withdrawal' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Penarikan
        </a>
    </div>

    <!-- Date Filter Bar (Collapsible/Simple) -->
    <form method="GET" action="{{ route('saver.mutation') }}" class="bg-white rounded-2xl p-3 border border-slate-200/80 shadow-xs flex items-center gap-2 text-xs">
        @if(request('type'))
            <input type="hidden" name="type" value="{{ request('type') }}">
        @endif
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700">
        <span class="text-slate-400">&ndash;</span>
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-700">
        <button type="submit" class="px-3 py-1.5 bg-slate-900 text-white rounded-xl font-semibold hover:bg-slate-800 transition-all shrink-0">
            Cari
        </button>
        @if(request('start_date') || request('end_date'))
            <a href="{{ route('saver.mutation', ['type' => request('type', 'all')]) }}" class="px-2 py-1.5 bg-slate-100 text-slate-500 rounded-xl font-bold hover:bg-slate-200">&times;</a>
        @endif
    </form>

    <!-- Transaction List (Identik Flutter TransactionItemCard) -->
    <div class="space-y-2.5">
        @forelse($transactions as $t)
            @php
                $isDeposit = ($t->transaction_type === 'deposit');
            @endphp
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between gap-3 hover:border-slate-300 transition-colors cursor-pointer"
                 onclick="showReceiptModal('{{ $t->reference_number ?? ('TRX-' . str_pad($t->id, 8, '0', STR_PAD_LEFT)) }}', '{{ $isDeposit ? 'Setoran Tunai' : 'Penarikan Kas' }}', '{{ \Carbon\Carbon::parse($t->transaction_date)->translatedFormat('d F Y') }} {{ $t->created_at->format('H:i') }} WIB', 'Rp {{ number_format($t->amount, 0, ',', '.') }}', '{{ $t->teller?->name ?? 'Teller Bank Mini' }}', '{{ $isDeposit ? '+' : '-' }}')">
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
                            {{ $isDeposit ? 'Setoran Tunai' : 'Penarikan Tunai' }}
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
                    <span class="block text-[9px] text-emerald-600 font-bold capitalize">
                        Berhasil
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-10 border border-slate-200/80 text-center text-slate-400 space-y-2">
                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-xs font-bold text-slate-700">Tidak ada riwayat transaksi</p>
                <p class="text-[11px] text-slate-400">Coba ubah filter atau tanggal transaksi Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $transactions->links() }}
    </div>

</div>

<!-- Modal Struk / Detail Bukti Transaksi -->
<div id="receiptModal" class="hidden fixed inset-0 z-50 items-end md:items-center justify-center bg-slate-900/60 backdrop-blur-xs p-0 md:p-4" onclick="if(event.target === this) closeReceiptModal()">
    <div class="w-full max-w-[400px] bg-white rounded-t-[32px] md:rounded-[28px] p-6 shadow-2xl space-y-4 border border-slate-100 text-center relative">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div>
            <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase block">Bukti Transaksi Edubank</span>
            <h3 id="rcptType" class="text-base font-extrabold text-slate-800 mt-0.5">Setoran Tunai</h3>
            <p id="rcptAmount" class="text-2xl font-black font-mono text-emerald-600 mt-1">Rp 0</p>
        </div>

        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-2 text-xs text-left">
            <div class="flex items-center justify-between">
                <span class="text-slate-400">No. Referensi:</span>
                <span id="rcptRef" class="font-mono font-bold text-slate-700">TRX-00000000</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-400">Waktu:</span>
                <span id="rcptTime" class="font-medium text-slate-700">-</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-400">Petugas Loket:</span>
                <span id="rcptTeller" class="font-semibold text-slate-700">-</span>
            </div>
            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60">
                <span class="text-slate-400">Status:</span>
                <span class="text-emerald-600 font-bold">Berhasil &bull; Tervalidasi</span>
            </div>
        </div>

        <button type="button" onclick="closeReceiptModal()" class="w-full py-2.5 bg-slate-900 text-white rounded-xl text-xs font-semibold">Tutup</button>
    </div>
</div>

@push('scripts')
<script>
    function showReceiptModal(ref, type, time, amount, teller, sign) {
        document.getElementById('rcptRef').innerText = ref;
        document.getElementById('rcptType').innerText = type;
        document.getElementById('rcptTime').innerText = time;
        document.getElementById('rcptAmount').innerText = sign + amount;
        document.getElementById('rcptAmount').className = 'text-2xl font-black font-mono mt-1 ' + (sign === '+' ? 'text-emerald-600' : 'text-rose-600');
        document.getElementById('rcptTeller').innerText = teller;

        const m = document.getElementById('receiptModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeReceiptModal() {
        const m = document.getElementById('receiptModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
</script>
@endpush
@endsection
