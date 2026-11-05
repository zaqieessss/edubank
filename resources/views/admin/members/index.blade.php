@extends('layouts.app')

@section('title', 'Data Siswa & Anggota (Members)')
@section('page_title', 'Members / Siswa')

@push('styles')
<style>
    @keyframes siren-alarm {
        0%, 100% {
            box-shadow: inset 0 0 100px 30px rgba(239, 68, 68, 0.28), inset 0 0 200px 70px rgba(220, 38, 38, 0.16);
            background-color: rgba(15, 23, 42, 0.78);
        }
        50% {
            box-shadow: inset 0 0 180px 70px rgba(239, 68, 68, 0.65), inset 0 0 340px 130px rgba(185, 28, 28, 0.42);
            background-color: rgba(76, 5, 5, 0.75);
        }
    }
    .siren-active {
        animation: siren-alarm 2s ease-in-out infinite;
        backdrop-filter: blur(14px) !important;
        -webkit-backdrop-filter: blur(14px) !important;
    }
    @keyframes siren-beacon {
        0%, 100% { transform: scale(0.95); opacity: 0.4; }
        50% { transform: scale(1.15); opacity: 1; }
    }
    .siren-beacon-pulse {
        animation: siren-beacon 1.4s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Bento Stats Grid (3 Cards Minimalist & Spacious) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 shrink-0">
        <!-- Card 1: Total Siswa -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Total Anggota Siswa
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ number_format($totalMembers ?? $members->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-slate-400 font-medium">Siswa</span>
            </div>
        </div>

        <!-- Card 2: Sudah Punya Rekening -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Punya Rekening
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-emerald-600">
                    {{ number_format(\App\Models\Member::where('registration_status', 'registered')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-emerald-600/80 font-medium">Nasabah</span>
            </div>
        </div>

        <!-- Card 3: Belum Buka Rekening -->
        <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 tracking-wide">
                Belum Buka Rekening
            </span>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-amber-500">
                    {{ number_format(\App\Models\Member::where('registration_status', 'unregistered')->count(), 0, ',', '.') }}
                </span>
                <span class="text-xs text-amber-500/80 font-medium">Siswa</span>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box (Memanjang Elegan Sejajar Bawah Sidebar) -->
    <div class="bg-white rounded-[24px] p-5 md:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Action Toolbar -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-3 border-b border-slate-100 shrink-0">
                <!-- Left: Search & Filter Form -->
                <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-wrap items-center gap-2">
                    <!-- Search Input -->
                    <div class="flex items-center bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 text-xs text-slate-600 w-52 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS / Nama Siswa..." class="bg-transparent border-none outline-none text-xs w-full p-0">
                    </div>

                    <!-- Major Select -->
                    <select name="major" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Jurusan</option>
                        @foreach($majors as $m)
                            <option value="{{ $m->value }}" {{ request('major') == $m->value ? 'selected' : '' }}>{{ $m->value }}</option>
                        @endforeach
                    </select>

                    <!-- Grade Level Select -->
                    <select name="grade_level" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Tingkat</option>
                        <option value="10" {{ request('grade_level') == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>Kelas 12</option>
                        <option value="alumni" {{ request('grade_level') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>

                    <!-- Registration Status -->
                    <select name="registration_status" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200/70 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 outline-none focus:bg-white">
                        <option value="">Semua Status</option>
                        <option value="registered" {{ request('registration_status') == 'registered' ? 'selected' : '' }}>Punya Rekening</option>
                        <option value="unregistered" {{ request('registration_status') == 'unregistered' ? 'selected' : '' }}>Belum Buka Rekening</option>
                    </select>

                    @if(request()->anyFilled(['search', 'major', 'grade_level', 'registration_status']))
                        <a href="{{ route('admin.members.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                    @endif
                </form>

                <!-- Right: Action Buttons -->
                <!-- Right: Action Buttons (Sleek & Clean) -->
                <div class="flex items-center gap-2">
                    <!-- Opsi Lanjutan (Dropdown: Template CSV, Import CSV, Naik Kelas) -->
                    <div class="relative" id="batchMenuContainer">
                        <button type="button" 
                                onclick="toggleBatchMenu(event)" 
                                id="batchMenuBtn"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors"
                                title="Menu Pengelolaan Siswa & Operasi Massal">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                            <span>Opsi Lanjutan</span>
                            <svg class="w-3 h-3 text-slate-400 ml-0.5 transition-transform duration-200" id="batchMenuChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="batchMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-1.5 z-40 animate-fade-in">
                            <div class="px-3.5 py-1.5 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                Operasi Data Massal
                            </div>

                            <!-- Download Template Excel -->
                            <a href="{{ route('admin.members.template', ['format' => 'excel']) }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Download Template Excel (.xlsx)</span>
                            </a>

                            <!-- Download Template CSV -->
                            <a href="{{ route('admin.members.template', ['format' => 'csv']) }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Download Template CSV</span>
                            </a>

                            <!-- Import Siswa (Excel & CSV) -->
                            <button type="button" 
                                    onclick="closeBatchMenu(); document.getElementById('importModal').classList.remove('hidden');" 
                                    class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span>Import Siswa (Excel / CSV)</span>
                            </button>

                            <div class="border-t border-slate-100 my-1"></div>

                            <!-- Naik Kelas Massal -->
                            <button type="button" 
                                    onclick="closeBatchMenu(); openPromoteModal();" 
                                    class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-amber-700 hover:bg-amber-50 transition-colors">
                                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                <span>Naik Kelas Massal</span>
                            </button>
                        </div>
                    </div>

                    <!-- Tambah Siswa Button (Primary Action - Dedicated Page) -->
                    <a href="{{ route('admin.members.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Siswa</span>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="flex-1 overflow-x-auto mt-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                            <th class="py-2.5 px-3 font-medium">Siswa & NIS</th>
                            <th class="py-2.5 px-3 font-medium">Kelas / Jurusan</th>
                            <th class="py-2.5 px-3 font-medium">Angkatan</th>
                            <th class="py-2.5 px-3 font-medium">Gender</th>
                            <th class="py-2.5 px-3 font-medium">Kontak & Email</th>
                            <th class="py-2.5 px-3 font-medium text-center">Status Bank</th>
                            <th class="py-2.5 px-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($members as $m)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-800">{{ $m->full_name }}</div>
                                    <div class="text-[10px] font-mono text-slate-400">NIS: {{ $m->student_id_number }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-700">{{ $m->class_name }}</div>
                                    <span class="inline-block text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 mt-0.5">
                                        {{ $m->major }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-md text-[10px] font-bold">
                                        Angkatan {{ $m->cohort }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-slate-600 capitalize">
                                    {{ $m->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="text-slate-700 font-mono text-[11px]">{{ $m->email }}</div>
                                    <div class="text-slate-400 text-[10px]">{{ $m->phone_number ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($m->registration_status === 'registered')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Punya Rekening
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200/70">
                                            Belum Terdaftar
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($m->registration_status === 'unregistered')
                                            <a href="{{ route('admin.savers.create') }}?member_id={{ $m->id }}" 
                                               class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold transition-all shadow-xs" 
                                               title="Buka Rekening Tabungan Siswa">
                                                Buka Rekening
                                            </a>
                                        @else
                                            <a href="{{ route('admin.savers.show', $m->saver->id) }}" 
                                               class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-[10px] font-semibold transition-colors" 
                                               title="Lihat Tabungan">
                                                Detail
                                            </a>
                                        @endif

                                        <!-- Edit Button (Dedicated Page) -->
                                        <a href="{{ route('admin.members.edit', $m->id) }}" 
                                           class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" 
                                           title="Edit Data Siswa">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>

                                        @if($m->registration_status === 'unregistered')
                                            <!-- Delete Button (Centered Modal Confirmation) -->
                                            <button type="button" 
                                                    onclick="openDeleteMemberModal('{{ route('admin.members.destroy', $m->id) }}', '{{ addslashes($m->full_name) }}')"
                                                    class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors cursor-pointer" 
                                                    title="Hapus Siswa">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-1.5">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        <span class="text-xs font-medium">Tidak ada data siswa yang cocok</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compact Data Counter Footer (Pinned at bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $members->count() }}</span> data dari <span class="font-bold text-slate-800">{{ number_format($totalMembers ?? $members->count(), 0, ',', '.') }}</span> data
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- ========================================================================= -->
<!-- MODAL: Import CSV / Excel                                                 -->
<!-- ========================================================================= -->
<div id="importModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) document.getElementById('importModal').classList.add('hidden')">
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Import Data Siswa (Excel & CSV)</h3>
                    <p class="text-[11px] text-slate-400">Mendukung format .xlsx, .xls, dan .csv</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>
        <form method="POST" action="{{ route('admin.members.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih File Excel / CSV Siswa</label>
                <input type="file" name="import_file" accept=".xlsx,.xls,.csv,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1">
                <p class="text-[11px] text-slate-400 mt-1.5">Kolom: <code class="font-mono text-slate-700">student_id_number, full_name, grade_level, class_name, major, cohort, gender, email, phone_number, address</code>.</p>
            </div>

            <!-- Download Templates Box -->
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-600">
                <span class="font-medium text-slate-700 block mb-1">Belum punya formatnya? Unduh template:</span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.members.template', ['format' => 'excel']) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold text-[11px] transition-colors">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Template Excel (.xlsx)
                    </a>
                    <a href="{{ route('admin.members.template', ['format' => 'csv']) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 font-semibold text-[11px] transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Template CSV
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">Mulai Import</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Kenaikan Kelas Massal & Pengecualian Siswa Tinggal Kelas           -->
<!-- ========================================================================= -->
<div id="promoteModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 transition-all duration-300"
     onclick="if(event.target === this) closePromoteModal()">
    
    <div id="promoteModalCard" 
         class="bg-white rounded-[24px] max-w-lg w-full border border-slate-200/90 shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh] animate-fade-in text-slate-800"
         onclick="event.stopPropagation()">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 siren-beacon-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Kenaikan Kelas Massal</h3>
                    <p class="text-[11px] text-slate-400">Pilih siswa tinggal kelas (bila ada) sebelum eksekusi.</p>
                </div>
            </div>
            <button type="button" 
                    onclick="closePromoteModal()" 
                    class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm cursor-pointer shrink-0">
                &times;
            </button>
        </div>

        <!-- Form Body -->
        <form method="POST" action="{{ route('admin.members.promote') }}" class="flex-1 overflow-y-auto p-6 space-y-4">
            @csrf

            <!-- Search Excluded Students -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700">
                    Cari Siswa Tinggal Kelas (Opsional)
                </label>
                <div class="relative">
                    <div class="relative flex items-center">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" 
                               id="excludedStudentSearch" 
                               oninput="handleExcludedStudentSearch(event)" 
                               placeholder="Ketik nama atau NIS siswa..." 
                               autocomplete="off"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-8 py-2 text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 outline-none transition-all">
                        <div id="excludedSearchLoading" class="hidden absolute right-3">
                            <svg class="animate-spin h-3.5 w-3.5 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>

                    <!-- Dropdown Hasil Pencarian -->
                    <div id="excludedSearchResults" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white rounded-xl shadow-xl border border-slate-200 z-20 overflow-hidden">
                        <!-- Diisi via JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Daftar Siswa Tinggal Kelas -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Daftar Siswa Tinggal Kelas</span>
                    <span id="excludedCountBadge" class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-400">
                        0 dipilih
                    </span>
                </div>
                <div id="excludedStudentsList">
                    <div class="py-2.5 px-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl border border-slate-100">
                        Tidak ada siswa tinggal kelas (seluruh siswa akan naik).
                    </div>
                </div>
                <!-- Hidden inputs -->
                <div id="excludedStudentsHiddenInputs"></div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                <button type="button" 
                        onclick="closePromoteModal()" 
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer">
                    Eksekusi Naik Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Hapus Siswa (Centered Backdrop Blur Confirmation)                   -->
<!-- ========================================================================= -->
<div id="deleteMemberModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeDeleteMemberModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Hapus Siswa</h3>
            </div>
            <button type="button" onclick="closeDeleteMemberModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin menghapus data siswa <strong id="deleteMemberName" class="text-slate-800"></strong>? Tindakan ini permanen dan tidak dapat dibatalkan.
        </p>

        <form id="deleteMemberForm" method="POST" action="" class="pt-2">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="closeDeleteMemberModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                    Ya, Hapus Siswa
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteMemberModal(actionUrl, memberName) {
        const form = document.getElementById('deleteMemberForm');
        const nameEl = document.getElementById('deleteMemberName');
        const modal = document.getElementById('deleteMemberModal');

        if (form && nameEl && modal) {
            form.action = actionUrl;
            nameEl.innerText = memberName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDeleteMemberModal() {
        const modal = document.getElementById('deleteMemberModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function toggleBatchMenu(e) {
        if (e) e.stopPropagation();
        const menu = document.getElementById('batchMenu');
        const chevron = document.getElementById('batchMenuChevron');
        if (!menu) return;
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
        } else {
            closeBatchMenu();
        }
    }

    function closeBatchMenu() {
        const menu = document.getElementById('batchMenu');
        const chevron = document.getElementById('batchMenuChevron');
        if (menu) menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    }

    window.addEventListener('click', function(e) {
        const container = document.getElementById('batchMenuContainer');
        if (container && !container.contains(e.target)) {
            closeBatchMenu();
        }

        // Close search results dropdown if clicked outside
        const searchInput = document.getElementById('excludedStudentSearch');
        const searchResults = document.getElementById('excludedSearchResults');
        if (searchResults && !searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.classList.add('hidden');
        }
    });

    // =========================================================================
    // MODAL SIAGA BERBAHAYA: Kenaikan Kelas Massal & Pengecualian Siswa
    // =========================================================================
    let excludedStudents = new Map(); // id -> { id, full_name, student_id_number, class_name }
    let searchTimeout = null;

    function openPromoteModal() {
        const modal = document.getElementById('promoteModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.classList.add('siren-active');

            // Reset search input
            const input = document.getElementById('excludedStudentSearch');
            if (input) {
                input.value = '';
                setTimeout(() => input.focus(), 150);
            }
            clearSearchResults();
        }
    }

    function closePromoteModal() {
        const modal = document.getElementById('promoteModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.classList.remove('siren-active');
        }
    }

    function handleExcludedStudentSearch(e) {
        const query = e.target.value.trim();
        const resultsContainer = document.getElementById('excludedSearchResults');
        const loadingEl = document.getElementById('excludedSearchLoading');

        if (searchTimeout) clearTimeout(searchTimeout);

        if (query.length < 1) {
            clearSearchResults();
            return;
        }

        if (loadingEl) loadingEl.classList.remove('hidden');

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('admin.members.search-active') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (loadingEl) loadingEl.classList.add('hidden');
                    renderSearchResults(data);
                })
                .catch(err => {
                    if (loadingEl) loadingEl.classList.add('hidden');
                    console.error('Gagal memuat pencarian siswa:', err);
                });
        }, 220);
    }

    function clearSearchResults() {
        const container = document.getElementById('excludedSearchResults');
        if (container) {
            container.innerHTML = '';
            container.classList.add('hidden');
        }
    }

    function renderSearchResults(students) {
        const container = document.getElementById('excludedSearchResults');
        if (!container) return;

        if (!students || students.length === 0) {
            container.innerHTML = `
                <div class="p-3 text-center text-xs text-slate-400">
                    Tidak ditemukan siswa aktif yang cocok
                </div>
            `;
            container.classList.remove('hidden');
            return;
        }

        let html = '<div class="divide-y divide-slate-100 max-h-48 overflow-y-auto">';
        students.forEach(s => {
            const isExcluded = excludedStudents.has(s.id);
            html += `
                <div class="p-2.5 flex items-center justify-between gap-2 hover:bg-slate-50 transition-colors">
                    <div class="text-left">
                        <p class="text-xs font-bold text-slate-800">${escapeHtml(s.full_name)}</p>
                        <p class="text-[10px] text-slate-400 font-mono">NIS: ${escapeHtml(s.student_id_number)} &bull; ${escapeHtml(s.class_name)}</p>
                    </div>
                    <div>
                        ${isExcluded ? `
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-lg">
                                <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Tinggal Kelas
                            </span>
                        ` : `
                            <button type="button" 
                                    onclick="addExcludedStudent(${s.id}, '${escapeJs(s.full_name)}', '${escapeJs(s.student_id_number)}', '${escapeJs(s.class_name)}')" 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[10px] font-bold transition-all shadow-xs cursor-pointer">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Tinggal Kelas</span>
                            </button>
                        `}
                    </div>
                </div>
            `;
        });
        html += '</div>';

        container.innerHTML = html;
        container.classList.remove('hidden');
    }

    function addExcludedStudent(id, fullName, nis, className) {
        if (excludedStudents.has(id)) return;
        excludedStudents.set(id, { id, full_name: fullName, student_id_number: nis, class_name: className });
        renderExcludedList();

        // Kosongkan input dan langsung hilangkan dropdown saran pencarian agar tidak menghalangi
        const input = document.getElementById('excludedStudentSearch');
        if (input) {
            input.value = '';
        }
        clearSearchResults();
    }

    function removeExcludedStudent(id) {
        excludedStudents.delete(id);
        renderExcludedList();

        // Refresh dropdown state
        const input = document.getElementById('excludedStudentSearch');
        if (input && input.value.trim().length > 0) {
            input.dispatchEvent(new Event('input'));
        }
    }

    function renderExcludedList() {
        const listEl = document.getElementById('excludedStudentsList');
        const hiddenInputsEl = document.getElementById('excludedStudentsHiddenInputs');
        const badgeCountEl = document.getElementById('excludedCountBadge');

        if (badgeCountEl) {
            badgeCountEl.innerText = `${excludedStudents.size} dipilih`;
            if (excludedStudents.size > 0) {
                badgeCountEl.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700';
            } else {
                badgeCountEl.className = 'text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-400';
            }
        }

        if (hiddenInputsEl) {
            let inputs = '';
            excludedStudents.forEach((student, id) => {
                inputs += `<input type="hidden" name="excluded_member_ids[]" value="${id}">`;
            });
            hiddenInputsEl.innerHTML = inputs;
        }

        if (!listEl) return;

        if (excludedStudents.size === 0) {
            listEl.innerHTML = `
                <div class="py-2.5 px-3 text-center text-xs text-slate-400 bg-slate-50 rounded-xl border border-slate-100">
                    Tidak ada siswa tinggal kelas (seluruh siswa akan naik).
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-1.5 max-h-40 overflow-y-auto pr-0.5">';
        excludedStudents.forEach((student, id) => {
            html += `
                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/80 text-xs transition-colors">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800">${escapeHtml(student.full_name)}</span>
                        <span class="text-[11px] text-slate-400 font-mono">${escapeHtml(student.student_id_number)}</span>
                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-white border border-slate-200 rounded-md text-slate-600">${escapeHtml(student.class_name)}</span>
                    </div>
                    <button type="button" 
                            onclick="removeExcludedStudent(${id})" 
                            class="w-6 h-6 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors cursor-pointer text-sm font-bold"
                            title="Hapus dari daftar">
                        &times;
                    </button>
                </div>
            `;
        });
        html += '</div>';
        listEl.innerHTML = html;
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

    function escapeJs(text) {
        if (!text) return '';
        return String(text).replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteMemberModal();
            closeBatchMenu();
            closePromoteModal();
            const importModal = document.getElementById('importModal');
            if (importModal) importModal.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
