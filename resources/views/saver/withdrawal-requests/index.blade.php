@extends('layouts.saver')

@section('title', 'Pengajuan Penarikan Dana H-1')

@section('content')
<div class="px-5 py-6 space-y-5">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('saver.dashboard') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center justify-center text-slate-600 hover:text-blue-600 active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-base font-extrabold text-slate-900 tracking-tight">Tarik Dana H-1</h1>
                <p class="text-[11px] text-slate-400 font-medium">Permohonan penarikan kas di atas Rp 1.000.000</p>
            </div>
        </div>
        <button type="button" onclick="openInfoModal()" class="w-9 h-9 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </button>
    </div>

    <!-- Saldo & Limit Card -->
    <div class="p-4 bg-gradient-to-tr from-slate-900 via-blue-950 to-blue-900 rounded-3xl text-white shadow-lg space-y-3 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-blue-500/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex items-center justify-between text-blue-200 text-xs font-semibold">
            <span>Saldo Tersedia untuk Ditarik</span>
            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded-full border border-white/10">Buku Tabungan Siswa</span>
        </div>
        <div class="flex items-baseline justify-between">
            <div>
                <p class="text-2xl font-extrabold font-mono tracking-tight text-white">
                    Rp {{ number_format($maxWithdrawable, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-blue-200/80 mt-0.5">
                    Saldo total: Rp {{ number_format($balance, 0, ',', '.') }} (Mengendap Rp {{ number_format($holdingBalance, 0, ',', '.') }})
                </p>
            </div>
        </div>
    </div>

    <!-- Form Pengajuan Baru -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
                <h2 class="text-xs font-bold text-slate-800">Buat Permohonan Baru</h2>
                <p class="text-[10px] text-slate-400">Pihak bank akan menyiapkan dana tunai di loket</p>
            </div>
        </div>

        <form action="{{ route('saver.withdrawal-requests.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <!-- Nominal -->
            <div>
                <label for="amount" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Nominal Penarikan (Rp)
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-xs text-slate-400">Rp</span>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           value="{{ old('amount') }}" 
                           min="1000001" 
                           max="{{ $maxWithdrawable }}"
                           step="10000"
                           placeholder="Contoh: 1500000"
                           required
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div class="flex items-center gap-1.5 mt-2">
                    <button type="button" onclick="setNominal(1500000)" class="px-2 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-lg text-[10px] font-mono font-bold transition-colors">1.5 Jt</button>
                    <button type="button" onclick="setNominal(2000000)" class="px-2 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-lg text-[10px] font-mono font-bold transition-colors">2.0 Jt</button>
                    <button type="button" onclick="setNominal(3000000)" class="px-2 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-lg text-[10px] font-mono font-bold transition-colors">3.0 Jt</button>
                </div>
            </div>

            <!-- Tanggal Pengambilan (H-1) -->
            <div>
                <label for="pickup_date" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Rencana Tanggal Pengambilan (Minimal H-1)
                </label>
                <input type="date" 
                       id="pickup_date" 
                       name="pickup_date" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       value="{{ old('pickup_date', date('Y-m-d', strtotime('+1 day'))) }}" 
                       required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <!-- Alasan Penarikan -->
            <div>
                <label for="reason" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Alasan / Keperluan Penarikan
                </label>
                <textarea id="reason" 
                          name="reason" 
                          rows="2" 
                          placeholder="Contoh: Keperluan biaya studi tur ke Bali / Pembayaran sertifikasi UKK" 
                          required
                          class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">{{ old('reason') }}</textarea>
            </div>

            <!-- Tombol Kirim -->
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white rounded-2xl text-xs font-bold shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <span>Ajukan Permohonan ke Admin</span>
            </button>
        </form>
    </div>

    <!-- Riwayat Pengajuan -->
    <div class="space-y-3">
        <h2 class="text-xs font-bold text-slate-800 tracking-tight">Riwayat Pengajuan Anda</h2>

        <div class="space-y-2.5">
            @forelse($requests as $req)
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs space-y-2.5 hover:border-slate-300 transition-all">
                    <!-- Row 1: Header & Status -->
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-mono font-extrabold text-slate-900">
                            Rp {{ number_format($req->amount, 0, ',', '.') }}
                        </span>
                        
                        @if($req->status === 'pending')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                Menunggu Verifikasi
                            </span>
                        @elseif($req->status === 'approved')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Siap Diambil di Teller
                            </span>
                        @elseif($req->status === 'completed')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200">
                                Selesai Dicairkan
                            </span>
                        @elseif($req->status === 'rejected')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                Ditolak
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                {{ ucfirst($req->status) }}
                            </span>
                        @endif
                    </div>

                    <!-- Row 2: Detail Tanggal & Keperluan -->
                    <div class="text-[11px] text-slate-600 space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Rencana Pengambilan:</span>
                            <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($req->pickup_date)->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-2 pt-1 border-t border-slate-200/50">
                            <span class="text-slate-400 shrink-0">Alasan:</span>
                            <span class="text-right text-slate-700 italic">"{{ $req->reason }}"</span>
                        </div>
                    </div>

                    <!-- Admin Notes if any -->
                    @if($req->admin_notes)
                        <div class="p-2 rounded-xl text-[10px] {{ $req->status === 'rejected' ? 'bg-rose-50/70 text-rose-700 border border-rose-100' : 'bg-blue-50/70 text-blue-700 border border-blue-100' }}">
                            <span class="font-bold">Catatan Pembina:</span> {{ $req->admin_notes }}
                        </div>
                    @endif

                    <!-- Row 3: Action or Info -->
                    <div class="flex items-center justify-between pt-1 text-[10px] text-slate-400">
                        <span>Diajukan: {{ $req->created_at->translatedFormat('d M Y, H:i') }}</span>
                        @if($req->status === 'pending')
                            <form action="{{ route('saver.withdrawal-requests.cancel', $req->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')">
                                @csrf
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold underline">Batalkan</button>
                            </form>
                        @elseif($req->transaction)
                            <span class="font-mono text-blue-600 font-semibold">{{ $req->transaction->transaction_code }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 border border-slate-200/80 text-center text-slate-400 space-y-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <p class="text-xs font-medium">Belum ada permohonan penarikan dana.</p>
                </div>
            @endforelse

            <div class="pt-2">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

</div>

<!-- Info Modal -->
<div id="infoModal" class="hidden fixed inset-0 z-50 items-end md:items-center justify-center bg-slate-900/60 backdrop-blur-xs p-0 md:p-4" onclick="if(event.target === this) closeInfoModal()">
    <div class="w-full max-w-[400px] bg-white rounded-t-[32px] md:rounded-[28px] p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Aturan Penarikan H-1</h3>
            <button type="button" onclick="closeInfoModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm font-bold">&times;</button>
        </div>
        <div class="space-y-2 text-xs text-slate-600 leading-relaxed">
            <p>1. Penarikan kas langsung di loket teller tanpa pengajuan dibatasi maksimal <strong>Rp 1.000.000 / hari</strong>.</p>
            <p>2. Untuk penarikan di atas Rp 1.000.000, nasabah wajib mengajukan permohonan minimal <strong>H-1 (hari sebelumnya)</strong> agar teller dan pembina bank dapat menyiapkan uang fisik di loket.</p>
            <p>3. Permohonan yang disetujui dapat dicairkan di loket teller pada tanggal yang telah Anda tentukan.</p>
        </div>
        <button type="button" onclick="closeInfoModal()" class="w-full py-2.5 bg-slate-900 text-white rounded-xl text-xs font-semibold">Mengerti</button>
    </div>
</div>

@push('scripts')
<script>
    function openInfoModal() {
        const m = document.getElementById('infoModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeInfoModal() {
        const m = document.getElementById('infoModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    function setNominal(val) {
        document.getElementById('amount').value = val;
    }
</script>
@endpush
@endsection
