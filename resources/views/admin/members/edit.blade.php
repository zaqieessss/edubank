@extends('layouts.app')

@section('title', 'Edit Data Siswa - ' . $member->full_name)
@section('page_title', 'Edit Siswa')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.members.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Data Siswa">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Perbarui Data Siswa</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">{{ $member->full_name }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400 font-mono">NIS: {{ $member->student_id_number }}</span>
        </div>
    </div>

    <!-- Main Bento Form Card (Memenuhi Layar Sejajar Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        <form id="memberEditForm" method="POST" action="{{ route('admin.members.update', $member->id) }}" class="flex-1 flex flex-col justify-between">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Baris 1: NIS, Angkatan & Nama Lengkap -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="student_id_number" 
                               id="student_id_number" 
                               value="{{ old('student_id_number', $member->student_id_number) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('student_id_number')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Angkatan <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="cohort" 
                               id="cohort" 
                               value="{{ old('cohort', $member->cohort) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('cohort')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="full_name" 
                               id="full_name" 
                               value="{{ old('full_name', $member->full_name) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('full_name')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Baris 2: Tingkat, Rombel, Jurusan, Gender -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kelas <span class="text-rose-500">*</span></label>
                        <select name="grade_level" id="grade_level" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                            <option value="10" {{ old('grade_level', $member->grade_level?->value ?? $member->grade_level) == '10' ? 'selected' : '' }}>Kelas 10</option>
                            <option value="11" {{ old('grade_level', $member->grade_level?->value ?? $member->grade_level) == '11' ? 'selected' : '' }}>Kelas 11</option>
                            <option value="12" {{ old('grade_level', $member->grade_level?->value ?? $member->grade_level) == '12' ? 'selected' : '' }}>Kelas 12</option>
                            <option value="alumni" {{ old('grade_level', $member->grade_level?->value ?? $member->grade_level) == 'alumni' ? 'selected' : '' }}>Alumni</option>
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
                               value="{{ old('class_name', $member->class_name) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('class_name')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jurusan Resmi <span class="text-rose-500">*</span></label>
                        <select name="major" id="major" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                            @foreach($majors as $m)
                                <option value="{{ $m->value }}" {{ old('major', $member->major?->value ?? $member->major) == $m->value ? 'selected' : '' }}>{{ $m->value }}</option>
                            @endforeach
                        </select>
                        @error('major')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="gender" id="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                            <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Baris 3: Email & HP -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Siswa <span class="text-rose-500">*</span></label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $member->email) }}" 
                               required 
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
                               value="{{ old('phone_number', $member->phone_number) }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('phone_number')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Baris 4: Alamat Domisili -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Domisili Siswa</label>
                    <textarea name="address" 
                              id="address" 
                              rows="3" 
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">{{ old('address', $member->address) }}</textarea>
                    @error('address')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer Action Buttons (Pinned at Bottom) -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0 mt-4">
                <a href="{{ route('admin.members.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                    Batal
                </a>
                <button type="button" 
                        onclick="triggerEditConfirm()" 
                        class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow cursor-pointer">
                    Perbarui Data Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Edit Siswa (Centered Backdrop Blur) -->
<div id="confirmModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeConfirmModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Perubahan Siswa</h3>
            </div>
            <button type="button" onclick="closeConfirmModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin menyimpan perubahan data untuk siswa <strong>{{ $member->full_name }}</strong>? Jika siswa sudah memiliki rekening, data nasabah akan otomatis disinkronkan.
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" onclick="submitEditForm()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                Ya, Perbarui Data
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function triggerEditConfirm() {
        const form = document.getElementById('memberEditForm');
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

    function submitEditForm() {
        document.getElementById('memberEditForm').submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
@endpush
@endsection
