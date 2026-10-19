@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page_title', 'Tambah Pengguna')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Daftar Pengguna">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Manajemen Akses Sistem</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">Tambah Pengguna Baru</h1>
            </div>
        </div>
        <span class="text-xs text-slate-400 font-mono hidden sm:inline">Bank Mini SMKN 1 Bantul</span>
    </div>

    <!-- Main Bento Form Card (Memenuhi Layar Sejajar Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        <form id="userForm" method="POST" action="{{ route('admin.users.store') }}" class="flex-1 flex flex-col justify-between">
            @csrf

            <div class="space-y-4">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Formulir Akun Pengguna</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Buat kredensial login baru untuk Administrator, Petugas Teller Kasir, atau Akun Siswa (Saver).
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
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Contoh: Rina Melati, S.Pd." 
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
                               value="{{ old('email') }}" 
                               required 
                               placeholder="Contoh: rina.teller@smkn1bantul.sch.id" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-slate-900/10">
                        @error('email')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi (Password) <span class="text-rose-500">*</span></label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required 
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
                                <option value="teller" {{ old('role') == 'teller' ? 'selected' : '' }}>Teller Kasir</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin / Supervisor</option>
                                <option value="saver" {{ old('role') == 'saver' ? 'selected' : '' }}>Siswa (Saver)</option>
                            </select>
                            @error('role')
                                <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Akun <span class="text-rose-500">*</span></label>
                            <select name="status" id="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:bg-white">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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
                        onclick="triggerAddUserConfirm()" 
                        class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow cursor-pointer">
                    Simpan Akun Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Tambah Pengguna (Centered Backdrop Blur) -->
<div id="confirmUserModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeConfirmUserModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Tambah Pengguna</h3>
            </div>
            <button type="button" onclick="closeConfirmUserModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin mendaftarkan akun pengguna baru ini? Akun ini akan langsung dapat mengakses sistem sesuai peran (role) yang dipilih.
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeConfirmUserModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" onclick="submitUserForm()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                Ya, Simpan Akun
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function triggerAddUserConfirm() {
        const form = document.getElementById('userForm');
        if (form.reportValidity()) {
            openConfirmUserModal();
        }
    }

    function openConfirmUserModal() {
        const modal = document.getElementById('confirmUserModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeConfirmUserModal() {
        const modal = document.getElementById('confirmUserModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitUserForm() {
        document.getElementById('userForm').submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmUserModal();
        }
    });
</script>
@endpush
@endsection
