@extends('layouts.app')

@section('title', 'Antrean Penarikan Dana H-1')
@section('page_title', 'Penarikan Terencana (H-1)')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Bento Stats Grid (2 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 shrink-0">
        <!-- Card 1: Siap Dicairkan Hari Ini -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-blue-600 tracking-wide flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Siap Dicairkan di Loket Hari Ini
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900">
                        {{ number_format($stats['today_approved_count'], 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Nasabah</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block font-medium">Total Uang Fisik Diperlukan:</span>
                    <span class="text-lg font-bold font-mono text-blue-700">
                        Rp {{ number_format($stats['today_approved_amount'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: Sudah Dicairkan Hari Ini -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-emerald-600 tracking-wide flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Sudah Dicairkan Hari Ini
            </span>
            <div class="mt-2 flex items-baseline justify-between">
                <div>
                    <span class="text-2xl font-bold tracking-tight text-emerald-600">
                        {{ number_format($stats['today_completed_count'], 0, ',', '.') }}
                    </span>
                    <span class="text-xs text-emerald-600/80 font-medium">Transaksi</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block font-medium">Total Terbayar:</span>
                    <span class="text-lg font-bold font-mono text-slate-800">
                        Rp {{ number_format($stats['today_completed_amount'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Filter Tanggal & Status) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <form method="GET" action="{{ route('teller.withdrawal-requests.index') }}" class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-slate-600">Pilih Tanggal:</span>
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1 text-xs text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Siap Dicairkan (Approved)</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Sudah Dicairkan (Completed)</option>
                    </select>

                    @if($date !== today()->toDateString() || $status !== 'approved')
                        <a href="{{ route('teller.withdrawal-requests.index') }}" class="px-2.5 py-1 text-xs text-blue-600 font-semibold">Kembali ke Hari Ini</a>
                    @endif
                </form>

                <a href="{{ route('teller.transactions.scanner') }}" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold shadow-xs flex items-center gap-1.5 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>Scan QR Siswa</span>
                </a>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto my-2 flex-1">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-3">Nasabah</th>
                            <th class="py-2.5 px-3 text-right">Nominal Rencana</th>
                            <th class="py-2.5 px-3">Keperluan / Alasan</th>
                            <th class="py-2.5 px-3">Persetujuan Pembina</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3 text-right">Aksi Loket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($requests as $req)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Nasabah -->
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-900">{{ $req->saver->full_name }}</div>
                                    <div class="text-[11px] font-mono text-slate-500 flex items-center gap-1.5 mt-0.5">
                                        <span>{{ chunk_split($req->saver->account_number, 4, ' ') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $req->saver->class_name }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        Saldo: <span class="font-mono font-bold text-slate-700">Rp {{ number_format($req->saver->balance, 0, ',', '.') }}</span>
                                    </div>
                                </td>

                                <!-- Nominal -->
                                <td class="py-3 px-3 text-right">
                                    <span class="font-mono font-extrabold text-blue-700 text-sm">
                                        Rp {{ number_format($req->amount, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- Alasan -->
                                <td class="py-3 px-3 max-w-xs">
                                    <p class="text-slate-700 italic">"{{ $req->reason }}"</p>
                                </td>

                                <!-- Persetujuan -->
                                <td class="py-3 px-3 text-[11px] text-slate-600">
                                    <div class="font-semibold text-slate-800">{{ $req->approver->name ?? 'Admin Pembina' }}</div>
                                    <div class="text-[10px] text-slate-400">
                                        {{ $req->approved_at ? \Carbon\Carbon::parse($req->approved_at)->translatedFormat('d M, H:i') : '-' }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-3 px-3 text-center">
                                    @if($req->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Siap Diambil
                                        </span>
                                    @elseif($req->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Sudah Dicairkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi Loket -->
                                <td class="py-3 px-3 text-right">
                                    @if($req->status === 'approved')
                                        <a href="{{ route('teller.transactions.create', ['saver' => $req->saver_id, 'amount' => $req->amount]) }}" 
                                           class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[11px] font-bold shadow-xs inline-flex items-center gap-1.5 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Proses Kas</span>
                                        </a>
                                    @elseif($req->transaction)
                                        <a href="{{ route('teller.transactions.receipt', $req->transaction_id) }}" 
                                           target="_blank"
                                           class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-all inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            <span>Struk</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data penarikan terencana untuk tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pt-3 border-t border-slate-100 shrink-0">
            {{ $requests->links() }}
        </div>
    </div>

</div>
@endsection
