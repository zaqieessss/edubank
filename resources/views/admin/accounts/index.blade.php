@extends('layouts.app')

@section('title', 'Rekening Siswa (Accounts)')
@section('page_title', 'Account / Rekening')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Total Rekening -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Rekening Siswa</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($accounts->total(), 0, ',', '.') }}</h3>
                <span class="text-[10px] font-medium text-slate-500">Terdaftar di core banking</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
        </div>

        <!-- Rekening Aktif -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rekening Aktif</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">
                    {{ \App\Models\Account::where('status', 'active')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-emerald-600">Dapat bertransaksi</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Rekening Dibekukan (Frozen) -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rekening Dibekukan</p>
                <h3 class="text-xl font-extrabold text-rose-600 mt-0.5">
                    {{ \App\Models\Account::where('status', 'frozen')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Kunci sementara</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
        </div>

        <!-- Total Likuiditas Tabungan -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Saldo Terhimpun</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5 font-mono">
                    Rp {{ number_format(\App\Models\Account::sum('balance'), 0, ',', '.') }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Saldo buku besar kas</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box with Controls -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <!-- Action Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('admin.accounts.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-slate-100/90 rounded-xl px-3 py-1.5 text-xs text-slate-600 border border-slate-200/60 w-64 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                    <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Rekening, Siswa, Kelas..." class="bg-transparent border-none outline-none text-xs w-full p-0">
                </div>

                <select name="status" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none">
                    <option value="">Semua Status Rekening</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="dormant" {{ request('status') == 'dormant' ? 'selected' : '' }}>Dormant</option>
                    <option value="frozen" {{ request('status') == 'frozen' ? 'selected' : '' }}>Dibekukan (Frozen)</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup (Closed)</option>
                </select>

                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.accounts.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                @endif
            </form>

            <a href="{{ route('admin.savers.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buka Rekening Baru</span>
            </a>
        </div>

        <!-- Dense Responsive Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">Nomor Rekening</th>
                        <th class="py-2.5 px-3">Pemilik Rekening (Siswa)</th>
                        <th class="py-2.5 px-3">Kelas & Jurusan</th>
                        <th class="py-2.5 px-3 text-right">Saldo Saat Ini</th>
                        <th class="py-2.5 px-3 text-center">Status Rekening</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($accounts as $acc)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 font-mono font-bold text-blue-700 text-xs">
                                {{ $acc->account_number }}
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-800">{{ $acc->saver?->full_name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">NIS: {{ $acc->saver?->student_id_number ?? '-' }}</div>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-semibold text-slate-700">{{ $acc->saver?->class_name ?? '-' }}</div>
                                <span class="inline-block text-[10px] font-semibold px-2 py-0.2 rounded bg-slate-100 text-slate-600">
                                    {{ $acc->saver?->major ?? '-' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="font-mono font-extrabold text-slate-900 text-xs">
                                    Rp {{ number_format($acc->balance, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Tersedia: Rp {{ number_format(max(0, $acc->balance - 10000), 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($acc->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @elseif($acc->status === 'frozen')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Dibekukan
                                    </span>
                                @elseif($acc->status === 'dormant')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Dormant
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                        {{ ucfirst($acc->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.accounts.show', $acc->id) }}" 
                                       class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-[10px] font-bold transition-all shadow-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Kelola & Mutasi</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Tidak ada data rekening yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Compact Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $accounts->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $accounts->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $accounts->total() }}</span> rekening
            </div>
            <div>
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
