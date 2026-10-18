@extends('layouts.app')

@section('title', 'Data Nasabah Tabungan (Savers)')
@section('page_title', 'Savers / Nasabah')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Total Savers -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Nasabah Aktif</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($savers->total(), 0, ',', '.') }}</h3>
                <span class="text-[10px] font-medium text-emerald-600">Siswa ber-rekening</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>

        <!-- Total Saldo Nasabah -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Simpanan Nasabah</p>
                <h3 class="text-xl font-extrabold text-blue-600 mt-0.5">
                    Rp {{ number_format(\App\Models\Account::sum('balance'), 0, ',', '.') }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Saldo dana siswa</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Rata-rata Saldo -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Saldo</p>
                <h3 class="text-xl font-extrabold text-slate-800 mt-0.5">
                    @php $accCount = \App\Models\Account::count(); @endphp
                    Rp {{ $accCount > 0 ? number_format(\App\Models\Account::sum('balance') / $accCount, 0, ',', '.') : '0' }}
                </h3>
                <span class="text-[10px] font-medium text-slate-500">Per rekening siswa</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>

        <!-- Rekening Terblokir / Dormant -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Holding Balance Rule</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5">Rp 10.000</h3>
                <span class="text-[10px] font-medium text-slate-500">Mengendap otomatis</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box with Controls -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <!-- Action Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('admin.savers.index') }}" class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-slate-100/90 rounded-xl px-3 py-1.5 text-xs text-slate-600 border border-slate-200/60 w-64 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                    <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Rekening, NIS, atau Nama..." class="bg-transparent border-none outline-none text-xs w-full p-0">
                </div>

                <select name="major" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none">
                    <option value="">Semua Jurusan</option>
                    @foreach(\App\Enums\Major::cases() as $m)
                        <option value="{{ $m->value }}" {{ request('major') == $m->value ? 'selected' : '' }}>{{ $m->value }}</option>
                    @endforeach
                </select>

                @if(request()->anyFilled(['search', 'major']))
                    <a href="{{ route('admin.savers.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                @endif
            </form>

            <!-- Buka Rekening Baru Button -->
            <a href="{{ route('admin.savers.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buka Rekening Baru</span>
            </a>
        </div>

        <!-- Dense Modern Responsive Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">No. Rekening</th>
                        <th class="py-2.5 px-3">Nama Nasabah / Siswa</th>
                        <th class="py-2.5 px-3">Kelas & Jurusan</th>
                        <th class="py-2.5 px-3 text-right">Saldo Tabungan</th>
                        <th class="py-2.5 px-3 text-center">Status Rekening</th>
                        <th class="py-2.5 px-3 text-center">User Login</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($savers as $s)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3">
                                <div class="font-mono font-bold text-blue-700 text-xs">{{ $s->account_number }}</div>
                                <div class="text-[10px] text-slate-400">NIS: {{ $s->student_id_number }}</div>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-800">{{ $s->full_name }}</div>
                                <div class="text-[10px] text-slate-400">Angkatan {{ $s->cohort }}</div>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-semibold text-slate-700">{{ $s->class_name }}</div>
                                <span class="inline-block text-[10px] font-semibold px-2 py-0.2 rounded bg-slate-100 text-slate-600">
                                    {{ $s->major }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="font-mono font-extrabold text-slate-900 text-xs">
                                    Rp {{ number_format($s->balance, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Bisa ditarik: Rp {{ number_format(max(0, $s->balance - 10000), 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @php $status = $s->account?->status ?? 'active'; @endphp
                                @if($status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @elseif($status === 'frozen')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Dibekukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                        {{ ucfirst($status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($s->user)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $s->user->email }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-400">Belum Login</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.savers.show', $s->id) }}" 
                                       class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-[10px] font-bold transition-all shadow-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail & Kartu</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Tidak ada data nasabah tabungan yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Compact Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $savers->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $savers->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $savers->total() }}</span> nasabah
            </div>
            <div>
                {{ $savers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
