@extends('layouts.app')

@section('title', 'Data Siswa & Anggota (Members)')
@section('page_title', 'Members / Siswa')

@section('content')
<div class="space-y-3.5 pb-6">

    <!-- Top Bento Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Card 1: Total Siswa -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Anggota Siswa</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($members->total(), 0, ',', '.') }}</h3>
                <span class="text-[10px] font-medium text-slate-500">Master database sekolah</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Card 2: Sudah Punya Rekening -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Punya Rekening (Savers)</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">
                    {{ \App\Models\Member::where('registration_status', 'registered')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-emerald-600/90">Nasabah aktif Edubank</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 3: Belum Buka Rekening -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Belum Buka Rekening</p>
                <h3 class="text-xl font-extrabold text-amber-500 mt-0.5">
                    {{ \App\Models\Member::where('registration_status', 'unregistered')->count() }}
                </h3>
                <span class="text-[10px] font-medium text-amber-600/90">Calon nasabah</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card 4: Jurusan Tercover -->
        <div class="bg-white rounded-[22px] p-3.5 border border-slate-200/80 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kompetensi Keahlian</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5">8 Jurusan</h3>
                <span class="text-[10px] font-medium text-slate-500">SMKN 1 Bantul</span>
            </div>
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Bento Box with Controls -->
    <div class="bg-white rounded-[26px] p-4 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
        
        <!-- Action Toolbar -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Left: Search & Filter Form -->
            <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-wrap items-center gap-2">
                <!-- Search Input -->
                <div class="flex items-center bg-slate-100/90 rounded-xl px-3 py-1.5 text-xs text-slate-600 border border-slate-200/60 w-52 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-500/20">
                    <svg class="w-3.5 h-3.5 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS / Nama Siswa..." class="bg-transparent border-none outline-none text-xs w-full p-0">
                </div>

                <!-- Major Select -->
                <select name="major" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none focus:bg-white">
                    <option value="">Semua Jurusan (8)</option>
                    @foreach($majors as $m)
                        <option value="{{ $m->value }}" {{ request('major') == $m->value ? 'selected' : '' }}>{{ $m->value }}</option>
                    @endforeach
                </select>

                <!-- Grade Level Select -->
                <select name="grade_level" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none focus:bg-white">
                    <option value="">Semua Tingkat</option>
                    <option value="10" {{ request('grade_level') == '10' ? 'selected' : '' }}>Kelas 10</option>
                    <option value="11" {{ request('grade_level') == '11' ? 'selected' : '' }}>Kelas 11</option>
                    <option value="12" {{ request('grade_level') == '12' ? 'selected' : '' }}>Kelas 12</option>
                    <option value="alumni" {{ request('grade_level') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                </select>

                <!-- Registration Status -->
                <select name="registration_status" onchange="this.form.submit()" class="bg-slate-100/90 rounded-xl px-2.5 py-1.5 text-xs text-slate-700 border border-slate-200/60 outline-none focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="registered" {{ request('registration_status') == 'registered' ? 'selected' : '' }}>Terdaftar (Savers)</option>
                    <option value="unregistered" {{ request('registration_status') == 'unregistered' ? 'selected' : '' }}>Belum Buka Rekening</option>
                </select>

                @if(request()->anyFilled(['search', 'major', 'grade_level', 'registration_status']))
                    <a href="{{ route('admin.members.index') }}" class="text-[11px] text-rose-500 hover:text-rose-700 font-semibold px-2 py-1">Reset</a>
                @endif
            </form>

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2">
                <!-- Download Template -->
                <a href="{{ route('admin.members.template') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors" title="Download Format CSV">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Template CSV</span>
                </a>

                <!-- Import CSV Button -->
                <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span>Import CSV</span>
                </button>

                <!-- Naik Kelas Massal -->
                <button type="button" onclick="document.getElementById('promoteModal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span>Naik Kelas Massal</span>
                </button>

                <!-- Tambah Siswa Button -->
                <button type="button" onclick="openMemberModal()" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Siswa</span>
                </button>
            </div>
        </div>

        <!-- Dense Modern Responsive Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                        <th class="py-2.5 px-3">Siswa & NIS</th>
                        <th class="py-2.5 px-3">Kelas / Jurusan</th>
                        <th class="py-2.5 px-3">Angkatan</th>
                        <th class="py-2.5 px-3">Gender</th>
                        <th class="py-2.5 px-3">Kontak & Email</th>
                        <th class="py-2.5 px-3 text-center">Status Bank</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $m)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3">
                                <div class="font-bold text-slate-800">{{ $m->full_name }}</div>
                                <div class="text-[10px] font-mono text-slate-400">NIS: {{ $m->student_id_number }}</div>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="font-semibold text-slate-700">{{ $m->class_name }}</div>
                                <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                    {{ $m->major }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 font-medium text-slate-600">
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-md text-[10px] font-bold">
                                    {{ $m->cohort }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-slate-600 capitalize">
                                {{ $m->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="text-slate-700 font-mono text-[11px]">{{ $m->email }}</div>
                                <div class="text-slate-400 text-[10px]">{{ $m->phone_number ?? '-' }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($m->registration_status === 'registered')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Rekening Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Belum Buka Rekening
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-right">
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
                                            Detail Tabungan
                                        </a>
                                    @endif

                                    <!-- Edit Button -->
                                    <button type="button" 
                                            onclick='editMember(@json($m))' 
                                            class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" 
                                            title="Edit Data Siswa">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('admin.members.destroy', $m->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors" 
                                                title="Hapus Siswa">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Tidak ada data siswa yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Compact Pagination -->
        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
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
</script>
@endpush
@endsection
