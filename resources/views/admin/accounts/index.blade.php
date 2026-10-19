@extends('layouts.app')

@section('title', 'Rekening Siswa (Accounts)')
@section('page_title', 'Account / Rekening')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Bento Stats Grid (3 Cards Minimalis & Spacious) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 shrink-0">
        <!-- Card 1: Total Rekening -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Rekening Siswa
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ number_format($accounts->total(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Rekening</span>
            </div>
        </div>

        <!-- Card 2: Rekening Aktif -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Rekening Aktif
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">
                    {{ number_format(\App\Models\Account::where('status', 'active')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-emerald-600/80 font-medium">Rekening</span>
            </div>
        </div>

        <!-- Card 3: Rekening Dibekukan (Frozen) -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Rekening Dibekukan
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-rose-600">
                    {{ number_format(\App\Models\Account::where('status', 'frozen')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-rose-600/80 font-medium">Rekening</span>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box (Memanjang Elegan Sejajar Bawah Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar (Pencarian, Filter Status, Buka Rekening) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <!-- Search & Filter Form (Tingkat, Angkatan & Status) -->
                <form method="GET" action="{{ route('admin.accounts.index') }}" class="flex flex-wrap items-center gap-2">
                    <!-- Search Input -->
                    <div class="flex items-center bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs text-slate-600 w-52 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Rekening, NIS, Nama..." class="bg-transparent border-none outline-none text-xs w-full p-0">
                    </div>

                    <!-- Filter Tingkat Kelas -->
                    <select name="grade_level" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Tingkat</option>
                        <option value="10" {{ request('grade_level') == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>Kelas 12</option>
                        <option value="alumni" {{ request('grade_level') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>

                    <!-- Filter Angkatan -->
                    <select name="cohort" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Angkatan</option>
                        @foreach($cohorts as $c)
                            <option value="{{ $c }}" {{ request('cohort') == $c ? 'selected' : '' }}>Angkatan {{ $c }}</option>
                        @endforeach
                    </select>

                    <!-- Filter Status (Disederhanakan: Aktif & Dibekukan) -->
                    <select name="status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="frozen" {{ request('status') == 'frozen' ? 'selected' : '' }}>Dibekukan</option>
                    </select>

                    @if(request()->anyFilled(['search', 'grade_level', 'cohort', 'status']))
                        <a href="{{ route('admin.accounts.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                    @endif
                </form>

                <!-- Buka Rekening Baru Button -->
                <a href="{{ route('admin.savers.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Buka Rekening Baru</span>
                </a>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3 font-medium">Nomor Rekening</th>
                            <th class="py-2.5 px-3 font-medium">Pemilik Rekening (Siswa)</th>
                            <th class="py-2.5 px-3 font-medium">Kelas & Jurusan</th>
                            <th class="py-2.5 px-3 font-medium text-right">Saldo Saat Ini</th>
                            <th class="py-2.5 px-3 font-medium text-center">Status Rekening</th>
                            <th class="py-2.5 px-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($accounts as $acc)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-mono font-bold text-blue-700 text-xs">{{ $acc->account_number }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-800">{{ $acc->saver?->full_name ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">NIS: {{ $acc->saver?->student_id_number ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-700">{{ $acc->saver?->class_name ?? '-' }}</div>
                                    <span class="inline-block text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 mt-0.5">
                                        {{ $acc->saver?->major ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="font-mono font-extrabold text-slate-900 text-xs">
                                        Rp {{ number_format($acc->balance, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($acc->status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @elseif($acc->status === 'frozen')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            Dibekukan
                                        </span>
                                    @elseif($acc->status === 'dormant')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                            Dormant
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200/70">
                                            {{ ucfirst($acc->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.accounts.show', $acc->id) }}" 
                                           class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-[10px] font-semibold transition-all shadow-xs flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Kelola</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span class="text-xs font-medium">Tidak ada data rekening yang cocok dengan pencarian</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compact Pagination Footer (Pinned at bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0">
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
