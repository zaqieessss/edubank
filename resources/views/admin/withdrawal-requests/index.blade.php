@extends('layouts.app')

@section('title', 'Persetujuan Penarikan Dana H-1')
@section('page_title', 'Pengajuan Penarikan H-1')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Bento Stats Grid (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 shrink-0">
        <!-- Card 1: Menunggu Verifikasi -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-amber-600 tracking-wide flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Menunggu Verifikasi
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ number_format($stats['pending'], 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Permohonan</span>
            </div>
        </div>

        <!-- Card 2: Siap Dicairkan Hari Ini -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-emerald-600 tracking-wide flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Disetujui Hari Ini
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">
                    {{ number_format($stats['approved_today'], 0, ',', '.') }}
                </span>
                <span class="text-xs text-emerald-600/80 font-medium">Permohonan</span>
            </div>
        </div>

        <!-- Card 3: Total Kas Fisik Diperlukan Hari Ini -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-blue-600 tracking-wide">
                Kas Terencana Hari Ini
            </span>
            <div class="mt-2 flex items-baseline gap-1">
                <span class="text-xl font-bold tracking-tight text-blue-700 font-mono">
                    Rp {{ number_format($stats['planned_amount_today'], 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Card 4: Selesai Dicairkan -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Selesai Dicairkan
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-700">
                    {{ number_format($stats['completed'], 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Transaksi</span>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Pencarian & Filter) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <form method="GET" action="{{ route('admin.withdrawal-requests.index') }}" class="flex flex-wrap items-center gap-2">
                    <!-- Search Input -->
                    <div class="flex items-center bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs text-slate-600 w-56 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / rekening..." class="bg-transparent border-none outline-none w-full text-xs placeholder:text-slate-400">
                    </div>

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai Dicairkan</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <!-- Date Filter -->
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                    @if($search || $status !== 'all' || $date)
                        <a href="{{ route('admin.withdrawal-requests.index') }}" class="px-2.5 py-1.5 text-xs text-slate-400 hover:text-slate-600 font-semibold">&times; Reset</a>
                    @endif
                </form>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto my-2 flex-1">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-3">Nasabah</th>
                            <th class="py-2.5 px-3 text-right">Nominal</th>
                            <th class="py-2.5 px-3">Tgl Rencana Ambil</th>
                            <th class="py-2.5 px-3">Alasan / Catatan</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
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
                                        Saldo saat ini: <span class="font-mono font-bold text-slate-700">Rp {{ number_format($req->saver->balance, 0, ',', '.') }}</span>
                                    </div>
                                </td>

                                <!-- Nominal -->
                                <td class="py-3 px-3 text-right">
                                    <span class="font-mono font-bold text-slate-900 text-sm">
                                        Rp {{ number_format($req->amount, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- Tanggal Ambil -->
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-800">
                                        {{ \Carbon\Carbon::parse($req->pickup_date)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        Diajukan: {{ $req->created_at->translatedFormat('d M Y, H:i') }}
                                    </div>
                                </td>

                                <!-- Alasan & Catatan -->
                                <td class="py-3 px-3 max-w-xs">
                                    <p class="text-slate-700 italic line-clamp-2">"{{ $req->reason }}"</p>
                                    @if($req->admin_notes)
                                        <div class="mt-1 text-[10px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md inline-block">
                                            Catatan: {{ $req->admin_notes }}
                                        </div>
                                    @endif
                                    @if($req->transaction)
                                        <div class="mt-0.5 text-[10px] font-mono text-emerald-600">
                                            Dicairkan: {{ $req->transaction->transaction_code }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-3 px-3 text-center">
                                    @if($req->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif($req->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @elseif($req->status === 'completed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Selesai
                                        </span>
                                    @elseif($req->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="py-3 px-3 text-right">
                                    @if($req->status === 'pending')
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button" 
                                                    onclick="triggerApproveModal('{{ route('admin.withdrawal-requests.approve', $req->id) }}', '{{ addslashes($req->saver->full_name) }}', 'Rp {{ number_format($req->amount, 0, ',', '.') }}', '{{ \Carbon\Carbon::parse($req->pickup_date)->translatedFormat('d F Y') }}')"
                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-semibold transition-all shadow-xs flex items-center gap-1 cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span>Setujui</span>
                                            </button>
                                            <button type="button" 
                                                    onclick="triggerRejectModal('{{ route('admin.withdrawal-requests.reject', $req->id) }}', '{{ addslashes($req->saver->full_name) }}', 'Rp {{ number_format($req->amount, 0, ',', '.') }}')"
                                                    class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-[10px] font-semibold transition-all border border-rose-200 flex items-center gap-1 cursor-pointer">
                                                <span>Tolak</span>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400">
                                            {{ $req->approver?->name ? 'Oleh ' . $req->approver->name : '-' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                    Tidak ada data permohonan penarikan dana sesuai filter.
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

<!-- Modal Konfirmasi Setujui Permohonan -->
<div id="approveModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" onclick="if(event.target === this) closeApproveModal()">
    <div class="w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl space-y-4 border border-slate-100 animate-slide-up">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Setujui Penarikan Dana H-1</h3>
                <p class="text-[11px] text-slate-400">Pemberian izin pencairan kas fisik di loket teller</p>
            </div>
        </div>

        <form id="approveForm" method="POST" class="space-y-3.5">
            @csrf
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs space-y-1.5">
                <div class="flex justify-between text-slate-600">
                    <span>Nama Nasabah:</span>
                    <strong id="approveSaverName" class="text-slate-900"></strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Nominal Penarikan:</span>
                    <strong id="approveAmount" class="font-mono text-emerald-600 text-sm"></strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Tanggal Pengambilan:</span>
                    <strong id="approvePickupDate" class="text-slate-900"></strong>
                </div>
            </div>

            <div>
                <label for="admin_notes" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Tambahan (Opsional)</label>
                <input type="text" name="admin_notes" id="admin_notes" value="Disetujui. Uang fisik siap disiapkan di kas loket teller." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-colors">Ya, Setujui Permohonan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tolak Permohonan -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4" onclick="if(event.target === this) closeRejectModal()">
    <div class="w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl space-y-4 border border-slate-100 animate-slide-up">
        <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Tolak Permohonan Penarikan</h3>
                <p class="text-[11px] text-slate-400">Sertakan alasan penolakan untuk nasabah</p>
            </div>
        </div>

        <form id="rejectForm" method="POST" class="space-y-3.5">
            @csrf
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 text-xs space-y-1.5">
                <div class="flex justify-between text-slate-600">
                    <span>Nama Nasabah:</span>
                    <strong id="rejectSaverName" class="text-slate-900"></strong>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Nominal Penarikan:</span>
                    <strong id="rejectAmount" class="font-mono text-rose-600 text-sm"></strong>
                </div>
            </div>

            <div>
                <label for="reject_notes" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="admin_notes" id="reject_notes" rows="2" required placeholder="Contoh: Saldo tidak mencukupi atau kas utama sedang dialokasikan untuk kegiatan lain..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-colors">Tolak Permohonan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function triggerApproveModal(actionUrl, saverName, amount, pickupDate) {
        document.getElementById('approveForm').action = actionUrl;
        document.getElementById('approveSaverName').innerText = saverName;
        document.getElementById('approveAmount').innerText = amount;
        document.getElementById('approvePickupDate').innerText = pickupDate;
        const m = document.getElementById('approveModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeApproveModal() {
        const m = document.getElementById('approveModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function triggerRejectModal(actionUrl, saverName, amount) {
        document.getElementById('rejectForm').action = actionUrl;
        document.getElementById('rejectSaverName').innerText = saverName;
        document.getElementById('rejectAmount').innerText = amount;
        const m = document.getElementById('rejectModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeRejectModal() {
        const m = document.getElementById('rejectModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
</script>
@endpush
@endsection
