@extends('layouts.app')

@section('title', 'Data Siswa & Anggota (Members)')
@section('page_title', 'Members / Siswa')

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
                    {{ number_format($members->total(), 0, ',', '.') }}
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

                            <!-- Download Template -->
                            <a href="{{ route('admin.members.template') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Download Template CSV</span>
                            </a>

                            <!-- Import CSV -->
                            <button type="button" 
                                    onclick="closeBatchMenu(); document.getElementById('importModal').classList.remove('hidden');" 
                                    class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span>Import Siswa (CSV)</span>
                            </button>

                            <div class="border-t border-slate-100 my-1"></div>

                            <!-- Naik Kelas Massal -->
                            <button type="button" 
                                    onclick="closeBatchMenu(); document.getElementById('promoteModal').classList.remove('hidden');" 
                                    class="w-full text-left flex items-center gap-2.5 px-3.5 py-2 text-xs font-medium text-amber-700 hover:bg-amber-50 transition-colors">
                                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                <span>Naik Kelas Massal</span>
                            </button>
                        </div>
                    </div>

                    <!-- Tambah Siswa Button (Primary Action) -->
                    <button type="button" onclick="openMemberModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah Siswa</span>
                    </button>
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
                                        {{ $m->cohort }}
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

                                        <!-- Edit Button -->
                                        <button type="button" 
                                                onclick='editMember(@json($m))' 
                                                class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" 
                                                title="Edit Data Siswa">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
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

        <!-- Compact Pagination Footer (Pinned at bottom) -->
        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 shrink-0">
            <div>
                Menampilkan <span class="font-bold text-slate-800">{{ $members->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-800">{{ $members->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $members->total() }}</span> siswa
            </div>
            <div>
                {{ $members->links() }}
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Import CSV                                                         -->
<!-- ========================================================================= -->
<div id="importModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Import Data Siswa (CSV)</h3>
            <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.members.import') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih File CSV Siswa</label>
                <input type="file" name="csv_file" accept=".csv,text/csv" required class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1">
                <p class="text-[11px] text-slate-400 mt-1">Gunakan template CSV resmi. Header: student_id_number, full_name, grade_level, class_name, major, cohort, gender, email, phone_number, address.</p>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700">Mulai Import</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Naik Kelas Massal                                                  -->
<!-- ========================================================================= -->
<div id="promoteModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Proses Kenaikan Kelas Massal</h3>
            <button onclick="document.getElementById('promoteModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.members.promote') }}" class="mt-4 space-y-4">
            @csrf
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3 text-xs text-amber-800 leading-relaxed">
                <p class="font-bold mb-1">Perhatian Perubahan Massal:</p>
                <ul class="list-disc pl-4 space-y-0.5 text-[11px]">
                    <li>Siswa Kelas 10 otomatis dinaikkan ke Kelas 11</li>
                    <li>Siswa Kelas 11 otomatis dinaikkan ke Kelas 12</li>
                    <li>Siswa Kelas 12 otomatis lulus menjadi status <strong>Alumni</strong></li>
                </ul>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('promoteModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-xl text-xs font-bold hover:bg-amber-700 shadow-sm">Eksekusi Naik Kelas</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Tambah / Edit Siswa                                                -->
<!-- ========================================================================= -->
<div id="memberModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 id="modalTitle" class="text-sm font-bold text-slate-800">Tambah Siswa Baru</h3>
            <button onclick="closeMemberModal()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form id="memberForm" method="POST" action="{{ route('admin.members.store') }}" class="mt-4 space-y-3">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">NIS (Nomor Induk Siswa) *</label>
                    <input type="text" name="student_id_number" id="m_nis" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tahun Angkatan *</label>
                    <input type="text" name="cohort" id="m_cohort" value="{{ date('Y') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Lengkap Siswa *</label>
                <input type="text" name="full_name" id="m_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Tingkat Kelas *</label>
                    <select name="grade_level" id="m_grade" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                        <option value="10">Kelas 10</option>
                        <option value="11">Kelas 11</option>
                        <option value="12">Kelas 12</option>
                        <option value="alumni">Alumni</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Nama Rombel / Kelas *</label>
                    <input type="text" name="class_name" id="m_class" placeholder="Contoh: X RPL 1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Jurusan Resmi SMKN 1 Bantul *</label>
                    <select name="major" id="m_major" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                        @foreach($majors as $m)
                            <option value="{{ $m->value }}">{{ $m->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Jenis Kelamin *</label>
                    <select name="gender" id="m_gender" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Email Siswa *</label>
                    <input type="email" name="email" id="m_email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                    <input type="text" name="phone_number" id="m_phone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Alamat Domisili</label>
                <textarea name="address" id="m_address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:bg-white"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeMemberModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 shadow-sm">Simpan Data Siswa</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openMemberModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Siswa Baru';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('memberForm').action = "{{ route('admin.members.store') }}";
        document.getElementById('memberForm').reset();
        document.getElementById('memberModal').classList.remove('hidden');
    }

    function editMember(member) {
        document.getElementById('modalTitle').innerText = 'Edit Data Siswa: ' + member.full_name;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('memberForm').action = "/admin/members/" + member.id;
        
        document.getElementById('m_nis').value = member.student_id_number;
        document.getElementById('m_name').value = member.full_name;
        document.getElementById('m_cohort').value = member.cohort;
        document.getElementById('m_grade').value = member.grade_level;
        document.getElementById('m_class').value = member.class_name;
        document.getElementById('m_major').value = member.major;
        document.getElementById('m_gender').value = member.gender;
        document.getElementById('m_email').value = member.email;
        document.getElementById('m_phone').value = member.phone_number || '';
        document.getElementById('m_address').value = member.address || '';

        document.getElementById('memberModal').classList.remove('hidden');
    }

    function closeMemberModal() {
        document.getElementById('memberModal').classList.add('hidden');
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
    });
</script>
@endpush
@endsection
