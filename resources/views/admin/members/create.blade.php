@extends('layouts.app')

@section('title', 'Tambah Siswa Baru')
@section('page_title', 'Tambah Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 pb-8">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.members.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Data Siswa">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Data Master Siswa</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">Tambah Siswa Baru</h1>
            </div>
        </div>
        <span class="text-xs text-slate-400 font-mono hidden sm:inline">Bank Mini SMKN 1 Bantul</span>
    </div>

    <!-- Main Bento Form Card -->
    <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-200/80 shadow-[0_4px_30px_-5px_rgba(15,23,42,0.04)] space-y-6">
        
        <div>
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Formulir Pendaftaran Siswa</h2>
            <p class="text-xs text-slate-500 mt-1">
                Lengkapi rincian data siswa di bawah ini untuk didaftarkan ke sistem master SMKN 1 Bantul.
            </p>
        </div>

        <form id="memberForm" method="POST" action="{{ route('admin.members.store') }}" class="space-y-5">
            @csrf

            <!-- Baris 1: NIS & Angkatan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="student_id_number" 
                           id="student_id_number" 
                           value="{{ old('student_id_number') }}" 
                           required 
                           placeholder="Contoh: 20241001" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                    @error('student_id_number')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Angkatan <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="cohort" 
                           id="cohort" 
                           value="{{ old('cohort', date('Y')) }}" 
                           required 
                           placeholder="Contoh: 2026" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                    @error('cohort')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Baris 2: Nama Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="full_name" 
                       id="full_name" 
                       value="{{ old('full_name') }}" 
                       required 
                       placeholder="Contoh: Ahmad Fauzi Pratama" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                @error('full_name')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Baris 3: Tingkat & Rombel Kelas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kelas <span class="text-rose-500">*</span></label>
                    <select name="grade_level" id="grade_level" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                        <option value="10" {{ old('grade_level') == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ old('grade_level') == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ old('grade_level') == '12' ? 'selected' : '' }}>Kelas 12</option>
                        <option value="alumni" {{ old('grade_level') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                    @error('grade_level')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Rombel / Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="class_name" 
                           id="class_name" 
                           value="{{ old('class_name') }}" 
                           required 
                           placeholder="Contoh: X RPL 1" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                    @error('class_name')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Baris 4: Jurusan & Gender -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kompetensi Keahlian (Jurusan) <span class="text-rose-500">*</span></label>
                    <select name="major" id="major" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                        @foreach($majors as $m)
                            <option value="{{ $m->value }}" {{ old('major') == $m->value ? 'selected' : '' }}>{{ $m->value }}</option>
                        @endforeach
                    </select>
                    @error('major')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="gender" id="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Baris 5: Email & HP -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Siswa <span class="text-rose-500">*</span></label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="siswa@smkn1bantul.sch.id" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                    @error('email')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">No. HP / WhatsApp</label>
                    <input type="text" 
                           name="phone_number" 
                           id="phone_number" 
                           value="{{ old('phone_number') }}" 
                           placeholder="08xxxxxxxxxx" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                    @error('phone_number')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Baris 6: Alamat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Domisili</label>
                <textarea name="address" 
                          id="address" 
                          rows="3" 
                          placeholder="Alamat tempat tinggal siswa..." 
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Footer Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.members.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                    Batal
                </a>
                <button type="button" 
                        onclick="triggerAddConfirm()" 
                        class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow cursor-pointer">
                    Simpan Data Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Tambah Siswa (Centered Backdrop Blur) -->
<div id="confirmModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeConfirmModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Tambah Siswa</h3>
            </div>
            <button type="button" onclick="closeConfirmModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin data siswa yang Anda masukkan sudah benar? Siswa ini akan didaftarkan ke master database sekolah.
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" onclick="submitMemberForm()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                Ya, Simpan Siswa
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function triggerAddConfirm() {
        const form = document.getElementById('memberForm');
        if (form.reportValidity()) {
            openConfirmModal();
        }
    }

    function openConfirmModal() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitMemberForm() {
        document.getElementById('memberForm').submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
@endpush
@endsection
