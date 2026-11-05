@extends('layouts.saver')

@section('title', 'Profil & Keamanan - Edubank')

@php
    $rawAccount = (string) $saver->account_number;
    $spacedAccount = trim(chunk_split($rawAccount, 4, ' '));
@endphp

@section('content')
<div class="px-5 pt-5 pb-8 space-y-5">

    <!-- Header Section (Identik Flutter ProfileScreen AppBar) -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-md shadow-blue-600/30 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight leading-tight">Profil Siswa</h1>
                <p class="text-xs text-slate-400">Pengaturan Akun & Keamanan</p>
            </div>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200/80">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Aktif
        </span>
    </div>

    <!-- Student Identity Card (Bento Style) -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
        <!-- Big Initial Avatar -->
        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-900 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-blue-700/25 shrink-0 border-2 border-white">
            {{ strtoupper(substr($saver->full_name, 0, 1)) }}
        </div>

        <div class="flex-1 min-w-0">
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight truncate">
                {{ $saver->full_name }}
            </h2>
            <p class="text-xs text-slate-500 font-medium">
                {{ $saver->class_name }} &bull; {{ $saver->major }}
            </p>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-mono font-bold">
                    {{ $spacedAccount }}
                </span>
                <button type="button" onclick="copyToClipboard('{{ $saver->account_number }}')" class="text-slate-400 hover:text-blue-600 cursor-pointer" title="Salin">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Account Details Group -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-3.5 text-xs">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi Rekening & Siswa</h3>

        <div class="flex items-center justify-between py-1 border-b border-slate-100">
            <span class="text-slate-500">Nomor Induk Siswa (NIS)</span>
            <span class="font-mono font-bold text-slate-800">{{ $saver->student_id_number ?? '-' }}</span>
        </div>
        <div class="flex items-center justify-between py-1 border-b border-slate-100">
            <span class="text-slate-500">Tahun Angkatan</span>
            <span class="font-bold text-slate-800">{{ $saver->cohort ?? '-' }}</span>
        </div>
        <div class="flex items-center justify-between py-1 border-b border-slate-100">
            <span class="text-slate-500">Saldo Utama</span>
            <span class="font-mono font-bold text-blue-700 text-sm">Rp {{ number_format($balance, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between py-1 border-b border-slate-100">
            <span class="text-slate-500">Batas Penarikan Tunai</span>
            <span class="font-mono font-bold text-emerald-600">Rp {{ number_format($maxWithdrawable, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between py-1">
            <span class="text-slate-500">Alamat Email</span>
            <span class="font-medium text-slate-800">{{ $user->email }}</span>
        </div>
    </div>

    <!-- Security Settings Group -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-3 text-xs">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Keamanan & Kredensial</h3>

        <!-- Ganti PIN Button -->
        <button type="button" 
                onclick="openUpdatePinModal()" 
                class="w-full p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/70 flex items-center justify-between text-left transition-all cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <span class="font-bold text-slate-800 block text-xs">Ubah PIN Transaksi 6 Digit</span>
                    <span class="text-[10px] text-slate-400">Digunakan untuk otorisasi tarik tunai di loket</span>
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Ganti Password Button -->
        <button type="button" 
                onclick="openUpdatePasswordModal()" 
                class="w-full p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/70 flex items-center justify-between text-left transition-all cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div>
                    <span class="font-bold text-slate-800 block text-xs">Ubah Kata Sandi Akun</span>
                    <span class="text-[10px] text-slate-400">Kata sandi masuk portal web & aplikasi</span>
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>

    <!-- App Info Group -->
    <div class="p-4 rounded-3xl bg-slate-50 border border-slate-200/60 text-center space-y-1 text-slate-400 text-xs">
        <p class="font-bold text-slate-600">Bank Mini SMKN 1 Bantul &bull; Edubank</p>
        <p class="text-[11px]">Versi 1.1.3 (Mobile Web Progressive)</p>
    </div>

    <!-- Tombol Logout (Rapi & Tidak Tertutup Dock) -->
    <div class="pt-2">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun tabungan?')">
            @csrf
            <button type="submit" class="w-full py-3.5 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 font-bold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Keluar dari Akun</span>
            </button>
        </form>
    </div>

</div>

<!-- Modal: Ubah PIN Transaksi 6-Digit -->
<div id="updatePinModal" class="hidden fixed inset-0 z-50 items-end md:items-center justify-center bg-slate-900/60 backdrop-blur-xs p-0 md:p-4" onclick="if(event.target === this) closeUpdatePinModal()">
    <div class="w-full max-w-[400px] bg-white rounded-t-[32px] md:rounded-[28px] p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Ubah PIN Transaksi (6 Digit)</h3>
            <button type="button" onclick="closeUpdatePinModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('saver.pin.update') }}" class="space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 mb-1">PIN Saat Ini</label>
                <input type="password" name="current_pin" maxlength="6" pattern="\d{6}" inputmode="numeric" required placeholder="••••••" class="w-full text-center tracking-widest font-mono text-base bg-slate-50 border border-slate-200 rounded-xl py-2 focus:bg-white">
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1">PIN Baru (6 Digit Angka)</label>
                <input type="password" name="new_pin" maxlength="6" pattern="\d{6}" inputmode="numeric" required placeholder="••••••" class="w-full text-center tracking-widest font-mono text-base bg-slate-50 border border-slate-200 rounded-xl py-2 focus:bg-white">
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Konfirmasi PIN Baru</label>
                <input type="password" name="new_pin_confirmation" maxlength="6" pattern="\d{6}" inputmode="numeric" required placeholder="••••••" class="w-full text-center tracking-widest font-mono text-base bg-slate-50 border border-slate-200 rounded-xl py-2 focus:bg-white">
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <button type="button" onclick="closeUpdatePinModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-xs">Simpan PIN</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Ubah Kata Sandi Akun -->
<div id="updatePasswordModal" class="hidden fixed inset-0 z-50 items-end md:items-center justify-center bg-slate-900/60 backdrop-blur-xs p-0 md:p-4" onclick="if(event.target === this) closeUpdatePasswordModal()">
    <div class="w-full max-w-[400px] bg-white rounded-t-[32px] md:rounded-[28px] p-6 shadow-2xl space-y-4 border border-slate-100">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Ubah Kata Sandi Akun</h3>
            <button type="button" onclick="closeUpdatePasswordModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 font-bold">&times;</button>
        </div>

        <form method="POST" action="{{ route('saver.password.update') }}" class="space-y-3 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white">
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Baru (Min. 6 Karakter)</label>
                <input type="password" name="new_password" minlength="6" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white">
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="new_password_confirmation" minlength="6" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 focus:bg-white">
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <button type="button" onclick="closeUpdatePasswordModal()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-xs">Simpan Sandi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openUpdatePinModal() {
        const m = document.getElementById('updatePinModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeUpdatePinModal() {
        const m = document.getElementById('updatePinModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function openUpdatePasswordModal() {
        const m = document.getElementById('updatePasswordModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeUpdatePasswordModal() {
        const m = document.getElementById('updatePasswordModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
</script>
@endpush
@endsection
