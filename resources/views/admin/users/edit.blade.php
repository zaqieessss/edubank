@extends('layouts.app')

@section('title', 'Edit Pengguna - ' . $user->name)
@section('page_title', 'Edit Pengguna')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Daftar Pengguna">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Perbarui Akun Pengguna</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">{{ $user->name }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($user->id === auth()->id())
                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold">Akun Anda</span>
            @endif
        </div>
    </div>

    <!-- Main Bento Form Card (Memenuhi Layar Sejajar Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        <form id="userEditForm" method="POST" action="{{ route('admin.users.update', $user->id) }}" class="flex-1 flex flex-col justify-between">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Formulir Perubahan Data Pengguna</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Perbarui nama, alamat email, kata sandi, maupun hak akses akun di bawah ini.
                    </p>
                </div>

                <!-- Grid Form Input Pengguna -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Pengguna <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('name')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Akun -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email Login <span class="text-rose-500">*</span></label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('email')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password (Optional on Edit) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kata Sandi Baru 
                            <span class="text-slate-400 font-normal text-[11px]">(Kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               minlength="6"
                               placeholder="Minimal 6 karakter kombinasi aman" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('password')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role & Status -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Role / Hak Akses <span class="text-rose-500">*</span></label>
                            <select name="role" id="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                                <option value="teller" {{ old('role', $user->role) == 'teller' ? 'selected' : '' }}>Teller Kasir</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin / Supervisor</option>
                                <option value="saver" {{ old('role', $user->role) == 'saver' ? 'selected' : '' }}>Siswa (Saver)</option>
                            </select>
                            @error('role')
                                <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Akun <span class="text-rose-500">*</span></label>
                            <select name="status" id="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons (Pinned at Bottom) -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0 mt-4">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all">
                    Batal
                </a>
                <button type="button" 
                        onclick="triggerEditUserConfirm()" 
                        class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow cursor-pointer">
                    Perbarui Akun Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Edit Pengguna (Centered Backdrop Blur) -->
<div id="confirmEditUserModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeConfirmEditUserModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Perubahan Akun</h3>
            </div>
            <button type="button" onclick="closeConfirmEditUserModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin menyimpan perubahan data untuk akun pengguna <strong>{{ $user->name }}</strong>?
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeConfirmEditUserModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" onclick="submitEditUserForm()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                Ya, Perbarui Akun
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function triggerEditUserConfirm() {
        const form = document.getElementById('userEditForm');
        if (form.reportValidity()) {
            openConfirmEditUserModal();
        }
    }

    function openConfirmEditUserModal() {
        const modal = document.getElementById('confirmEditUserModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeConfirmEditUserModal() {
        const modal = document.getElementById('confirmEditUserModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitEditUserForm() {
        document.getElementById('userEditForm').submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmEditUserModal();
        }
    });
</script>
@endpush
@endsection
