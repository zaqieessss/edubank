@extends('layouts.app')

@section('title', 'Buka Rekening Tabungan Siswa')
@section('page_title', 'Buka Rekening Baru')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-3 pb-2 min-h-0">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[22px] px-5 py-3 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.savers.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Daftar Nasabah">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Buka Rekening Baru</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    Registrasi Nasabah Siswa
                </h1>
            </div>
        </div>
        <span class="text-xs text-slate-400 font-mono hidden sm:inline">Bank Mini SMKN 1 Bantul</span>
    </div>

    <!-- Bento Form Card (Memenuhi Layar Sejajar Sidebar, Auto-Fit Tanpa Offside) -->
    <div class="bg-white rounded-[24px] p-5 sm:p-6 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-0 overflow-hidden">
        <form method="POST" action="{{ route('admin.savers.store') }}" class="flex-1 flex flex-col justify-between min-h-0 overflow-hidden">
            @csrf

            <div class="flex-1 overflow-y-auto pr-1.5 space-y-3.5 min-h-0">
                <!-- Header Info -->
                <div class="border-b border-slate-100 pb-2.5">
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">Form Pembukaan Rekening Tabungan Baru</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">
                        Pilih siswa dari database master. Sistem akan otomatis membuatkan Nomor Rekening resmi, mengaktifkan akun login, membuat QR Code, dan membukukan mutasi setoran awal.
                    </p>
                </div>

                <!-- 1. Pilih Siswa (Dengan Search Bar Cepat) -->
                <div class="relative" id="student_combobox_container">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-800">
                            1. Pilih Siswa (Hanya siswa yang belum memiliki rekening) <span class="text-rose-500">*</span>
                        </label>
                        <span id="student_count_badge" class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                            {{ $unregisteredMembers->count() }} siswa tersedia
                        </span>
                    </div>

                    @if($unregisteredMembers->isEmpty())
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-2xl text-xs">
                            Semua siswa di master database telah memiliki rekening tabungan! Jika ingin menambah nasabah baru, tambahkan terlebih dahulu melalui menu <a href="{{ route('admin.members.index') }}" class="font-bold underline">Members</a>.
                        </div>
                    @else
                        <!-- Real Select (Hidden) -->
                        <select name="member_id" id="member_select" required class="sr-only" tabindex="-1">
                            <option value="">-- Pilih Siswa Berdasarkan NIS / Nama Lengkap --</option>
                            @foreach($unregisteredMembers as $m)
                                <option value="{{ $m->id }}" 
                                        data-nis="{{ $m->student_id_number }}"
                                        data-name="{{ $m->full_name }}"
                                        data-major="{{ $m->major }}"
                                        data-class="{{ $m->class_name }}"
                                        data-cohort="{{ $m->cohort }}"
                                        data-email="{{ $m->email }}"
                                        {{ (old('member_id') == $m->id || request('member_id') == $m->id) ? 'selected' : '' }}>
                                    [NIS: {{ $m->student_id_number }}] {{ $m->full_name }} — {{ $m->class_name }} ({{ $m->major }})
                                </option>
                            @endforeach
                        </select>

                        <!-- Search Bar Input & Dropdown -->
                        <div class="relative">
                            <div class="relative flex items-center">
                                <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input type="text" 
                                       id="student_search_input" 
                                       autocomplete="off"
                                       placeholder="Ketik NIS, nama siswa, kelas, atau jurusan untuk mencari..."
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium transition-all">
                                <button type="button" 
                                        id="clear_search_btn" 
                                        class="hidden absolute right-3 w-5 h-5 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-600 flex items-center justify-center text-[10px] font-bold cursor-pointer transition-colors"
                                        title="Hapus pencarian">
                                    &times;
                                </button>
                            </div>

                            <!-- Dropdown List Hasil Pencarian -->
                            <div id="student_dropdown_menu" 
                                 class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden animate-fade-in">
                                <div id="student_dropdown_list" class="max-h-60 overflow-y-auto divide-y divide-slate-100 p-1.5 space-y-0.5">
                                    <!-- Items di-generate via JS -->
                                </div>
                                <div id="student_empty_state" class="hidden p-4 text-center text-xs text-slate-400">
                                    <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Tidak ada siswa yang cocok dengan kata kunci tersebut.
                                </div>
                            </div>
                        </div>
                    @endif
                    @error('member_id')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Data Siswa Terpilih (Interactive) -->
                <div id="student_preview" class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Jurusan</span>
                        <span id="prev_major" class="font-bold text-slate-800">-</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kelas</span>
                        <span id="prev_class" class="font-bold text-slate-800">-</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Angkatan</span>
                        <span id="prev_cohort" class="font-bold text-slate-800">-</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Estimasi Rekening</span>
                        <span id="prev_acc" class="font-mono font-bold text-blue-600">-</span>
                    </div>
                </div>

                <!-- Email Akun Siswa (Bisa Langsung Disesuaikan/Diubah) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="email_input" class="block text-xs font-bold text-slate-800">
                            Email Akun Siswa (Untuk Login & Notifikasi) *
                        </label>
                        <span class="text-[10px] text-blue-600 bg-blue-50 border border-blue-200/60 px-2 py-0.5 rounded-full font-medium">
                            Dapat diubah langsung tanpa ke menu Manajemen
                        </span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                            </svg>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email_input" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="contoh: siswa@edubank.web.id atau email pribadi siswa" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">
                        Secara otomatis memuat email terdaftar siswa. Anda dapat langsung mengeditnya jika email masih default atau siswa ingin memakai email aktif miliknya.
                    </p>
                    @error('email')
                        <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid Baris: Setoran Awal, PIN Keamanan & Password Akun -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- 2. Setoran Awal Tabungan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">
                            2. Setoran Awal Tunai (Wajib Minimal Rp 10.000) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 font-mono">Rp</span>
                            <input type="text" 
                                   inputmode="numeric"
                                   data-currency="true"
                                   name="initial_deposit" 
                                   id="initial_deposit" 
                                   value="{{ old('initial_deposit', 10000) }}" 
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-xs font-bold font-mono text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-[10px] text-slate-500">Pilihan Cepat:</span>
                            <button type="button" onclick="setDeposit(10000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 10.000</button>
                            <button type="button" onclick="setDeposit(20000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 20.000</button>
                            <button type="button" onclick="setDeposit(50000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 50.000</button>
                            <button type="button" onclick="setDeposit(100000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 100.000</button>
                        </div>
                        @error('initial_deposit')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 3. PIN Keamanan Rekening -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                3. Buat PIN Transaksi Nasabah (6 Digit Angka) *
                            </label>
                        </div>
                        <div class="relative">
                            <input type="password" 
                                   name="security_pin" 
                                   id="security_pin" 
                                   maxlength="6" 
                                   pattern="[0-9]{6}" 
                                   value="{{ old('security_pin') }}" 
                                   placeholder="Masukkan 6 digit angka PIN..." 
                                   autocomplete="new-password"
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs font-mono tracking-widest text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            <button type="button" 
                                    onclick="toggleInputVisibility('security_pin')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                    title="Lihat / Sembunyikan PIN">
                                <svg id="eye-icon-security_pin" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg id="eye-slash-security_pin" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">
                            PIN ini digunakan siswa untuk otorisasi transaksi tabungan & penarikan tunai di loket teller.
                        </p>
                        @error('security_pin')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 4. Password Akun Login Siswa -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                4. Kata Sandi Akun Pengguna (Password Login) *
                            </label>
                        </div>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   minlength="6" 
                                   value="{{ old('password') }}" 
                                   placeholder="Minimal 6 karakter" 
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            <button type="button" 
                                    onclick="toggleInputVisibility('password')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                    title="Lihat / Sembunyikan Password">
                                <svg id="eye-icon-password" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg id="eye-slash-password" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-[10px] text-slate-400">
                                Password akun pengguna (User) siswa untuk login ke portal Edubank.
                            </p>
                            <button type="button" 
                                    onclick="copyPinToPassword()" 
                                    class="text-[10px] font-semibold text-blue-600 hover:text-blue-700 hover:underline shrink-0 ml-2 cursor-pointer"
                                    title="Salin nilai PIN sebagai kata sandi">
                                Samakan dengan PIN
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 5. Konfirmasi Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-800">
                                5. Konfirmasi Kata Sandi Akun *
                            </label>
                        </div>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   minlength="6" 
                                   placeholder="Ulangi kata sandi akun" 
                                   required 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            <button type="button" 
                                    onclick="toggleInputVisibility('password_confirmation')" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                    title="Lihat / Sembunyikan Password">
                                <svg id="eye-icon-password_confirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg id="eye-slash-password_confirmation" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">
                            Ulangi kata sandi untuk memastikan kecocokan dan menghindari salah ketik.
                        </p>
                        @error('password_confirmation')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button (Pinned at Bottom) -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0 mt-2 bg-white">
                <a href="{{ route('admin.savers.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all cursor-pointer">
                    Batal
                </a>
                <button type="button" 
                        onclick="triggerSaverConfirm()" 
                        {{ $unregisteredMembers->isEmpty() ? 'disabled' : '' }} 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-600/20 transition-all hover:shadow-lg cursor-pointer">
                    Proses Pembukaan Rekening & Cetak Kartu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Buka Rekening (Centered Backdrop Blur) -->
<div id="confirmSaverModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeSaverConfirmModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Buka Rekening</h3>
            </div>
            <button type="button" onclick="closeSaverConfirmModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin memproses pembukaan rekening tabungan baru untuk siswa terpilih? Sistem akan langsung membuat nomor rekening resmi, akun login pengguna, QR Code, dan membukukan mutasi setoran awal.
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeSaverConfirmModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" onclick="submitSaverForm()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                Ya, Buka Rekening
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleInputVisibility(id) {
        const input = document.getElementById(id);
        const eyeIcon = document.getElementById('eye-icon-' + id);
        const eyeSlash = document.getElementById('eye-slash-' + id);
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            if (eyeIcon) eyeIcon.classList.add('hidden');
            if (eyeSlash) eyeSlash.classList.remove('hidden');
        } else {
            input.type = 'password';
            if (eyeIcon) eyeIcon.classList.remove('hidden');
            if (eyeSlash) eyeSlash.classList.add('hidden');
        }
    }

    function copyPinToPassword() {
        const pinInput = document.getElementById('security_pin');
        const pwdInput = document.getElementById('password');
        const pwdConfInput = document.getElementById('password_confirmation');
        if (pinInput && pinInput.value) {
            if (pwdInput) pwdInput.value = pinInput.value;
            if (pwdConfInput) {
                pwdConfInput.value = pinInput.value;
                pwdConfInput.setCustomValidity('');
            }
        }
    }

    function triggerSaverConfirm() {
        const form = document.querySelector('form[action="{{ route('admin.savers.store') }}"]');
        if (form && form.reportValidity()) {
            const pwd = document.getElementById('password');
            const pwdConf = document.getElementById('password_confirmation');
            if (pwd && pwdConf && pwd.value !== pwdConf.value) {
                pwdConf.setCustomValidity('Konfirmasi kata sandi tidak cocok.');
                form.reportValidity();
                return;
            } else if (pwdConf) {
                pwdConf.setCustomValidity('');
            }
            openSaverConfirmModal();
        }
    }

    const confField = document.getElementById('password_confirmation');
    if (confField) {
        confField.addEventListener('input', function() {
            this.setCustomValidity('');
        });
    }

    function openSaverConfirmModal() {
        const modal = document.getElementById('confirmSaverModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeSaverConfirmModal() {
        const modal = document.getElementById('confirmSaverModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitSaverForm() {
        const form = document.querySelector('form[action="{{ route('admin.savers.store') }}"]');
        if (form) form.submit();
    }

    function setDeposit(amount) {
        if (window.setCurrencyInputValue) {
            window.setCurrencyInputValue('initial_deposit', amount);
        } else {
            document.getElementById('initial_deposit').value = amount;
        }
    }

    const memberSelect = document.getElementById('member_select');
    const searchInput = document.getElementById('student_search_input');
    const dropdownMenu = document.getElementById('student_dropdown_menu');
    const dropdownList = document.getElementById('student_dropdown_list');
    const emptyState = document.getElementById('student_empty_state');
    const clearBtn = document.getElementById('clear_search_btn');

    if (memberSelect) {
        const emailInput = document.getElementById('email_input');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                this.dataset.manuallyEdited = 'true';
            });
        }

        function updatePreview() {
            const opt = memberSelect.options[memberSelect.selectedIndex];
            if (opt && opt.value) {
                document.getElementById('prev_major').innerText = opt.dataset.major || '-';
                document.getElementById('prev_class').innerText = opt.dataset.class || '-';
                document.getElementById('prev_cohort').innerText = opt.dataset.cohort || '-';
                document.getElementById('prev_acc').innerText = (opt.dataset.major || 'XXX') + '-' + (opt.dataset.cohort || '2026') + '-XXXX';

                if (emailInput && (!emailInput.value || !emailInput.dataset.manuallyEdited)) {
                    emailInput.value = opt.dataset.email || '';
                }
            } else {
                document.getElementById('prev_major').innerText = '-';
                document.getElementById('prev_class').innerText = '-';
                document.getElementById('prev_cohort').innerText = '-';
                document.getElementById('prev_acc').innerText = '-';
                if (emailInput && !emailInput.dataset.manuallyEdited) {
                    emailInput.value = '';
                }
            }
        }
        memberSelect.addEventListener('change', updatePreview);
        updatePreview();

        // Setup Searchable Combobox
        if (searchInput && dropdownMenu && dropdownList) {
            const studentOptions = [];
            for (let i = 1; i < memberSelect.options.length; i++) {
                const opt = memberSelect.options[i];
                studentOptions.push({
                    id: opt.value,
                    nis: opt.dataset.nis || '',
                    name: opt.dataset.name || opt.innerText.split('] ')[1]?.split(' —')[0] || opt.innerText,
                    major: opt.dataset.major || '',
                    className: opt.dataset.class || '',
                    cohort: opt.dataset.cohort || '',
                    email: opt.dataset.email || '',
                });
            }

            function renderList(filtered, totalMatches) {
                dropdownList.innerHTML = '';
                if (filtered.length === 0) {
                    emptyState.classList.remove('hidden');
                    return;
                }
                emptyState.classList.add('hidden');

                filtered.forEach(item => {
                    const isSelected = memberSelect.value === String(item.id);
                    const el = document.createElement('div');
                    el.className = `p-2.5 rounded-xl cursor-pointer transition-all flex items-center justify-between text-xs ${
                        isSelected 
                            ? 'bg-blue-50/80 border border-blue-200 text-blue-900 font-bold' 
                            : 'hover:bg-slate-50 text-slate-700'
                    }`;
                    el.innerHTML = `
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 font-extrabold flex items-center justify-center text-[10px] shrink-0">
                                ${(item.major || 'SIS').substring(0, 3)}
                            </div>
                            <div class="truncate">
                                <p class="font-bold text-slate-900 leading-tight truncate">${item.name}</p>
                                <p class="text-[10px] text-slate-400 leading-tight mt-0.5">NIS: <span class="font-mono text-slate-600 font-semibold">${item.nis}</span> • ${item.className} (${item.major})</p>
                            </div>
                        </div>
                        ${isSelected ? '<span class="text-blue-600 font-bold ml-2">✓</span>' : ''}
                    `;
                    el.addEventListener('click', () => selectStudent(item));
                    dropdownList.appendChild(el);
                });

                if (totalMatches > filtered.length) {
                    const info = document.createElement('div');
                    info.className = 'p-2 text-center text-[10px] text-slate-400 bg-slate-50 border-t border-slate-100 font-medium';
                    info.innerText = `Menampilkan ${filtered.length} dari ${totalMatches} hasil. Ketik lebih spesifik jika belum menemukan.`;
                    dropdownList.appendChild(info);
                }
            }

            function selectStudent(student) {
                memberSelect.value = student.id;
                searchInput.value = `[NIS: ${student.nis}] ${student.name} — ${student.className}`;
                if (clearBtn) clearBtn.classList.remove('hidden');
                dropdownMenu.classList.add('hidden');
                if (emailInput) {
                    emailInput.value = student.email || '';
                    delete emailInput.dataset.manuallyEdited;
                }
                memberSelect.dispatchEvent(new Event('change'));
            }

            function filterStudents() {
                const query = searchInput.value.toLowerCase().trim();
                if (clearBtn) clearBtn.classList.toggle('hidden', query.length === 0);

                // Hanya tampilkan jika pengguna mengetik minimal 1 karakter
                if (!query) {
                    dropdownMenu.classList.add('hidden');
                    dropdownList.innerHTML = '';
                    emptyState.classList.add('hidden');
                    return;
                }

                const terms = query.split(/\s+/);
                const filtered = studentOptions.filter(item => {
                    return terms.every(term => 
                        item.nis.toLowerCase().includes(term) ||
                        item.name.toLowerCase().includes(term) ||
                        item.className.toLowerCase().includes(term) ||
                        item.major.toLowerCase().includes(term)
                    );
                });

                // Batasi maksimal 25 suggestion agar performa ringan & ringkas
                renderList(filtered.slice(0, 25), filtered.length);
                dropdownMenu.classList.remove('hidden');
            }

            // Saat fokus: HANYA tampilkan suggestion jika ada teks pencarian
            searchInput.addEventListener('focus', () => {
                const q = searchInput.value.trim();
                // Jika input masih kosong, JANGAN buka dropdown ke bawah
                if (q.length > 0 && !memberSelect.value) {
                    filterStudents();
                }
            });

            // Saat mengetik huruf: Munculkan dropdown hasil filter
            searchInput.addEventListener('input', () => {
                const q = searchInput.value.trim();
                if (q.length > 0) {
                    filterStudents();
                } else {
                    dropdownMenu.classList.add('hidden');
                    if (clearBtn) clearBtn.classList.add('hidden');
                }
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    searchInput.value = '';
                    memberSelect.value = '';
                    clearBtn.classList.add('hidden');
                    memberSelect.dispatchEvent(new Event('change'));
                    filterStudents();
                    searchInput.focus();
                });
            }

            document.addEventListener('click', (e) => {
                const container = document.getElementById('student_combobox_container');
                if (container && !container.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                    const selectedOpt = memberSelect.options[memberSelect.selectedIndex];
                    if (selectedOpt && selectedOpt.value) {
                        const found = studentOptions.find(s => String(s.id) === selectedOpt.value);
                        if (found) {
                            searchInput.value = `[NIS: ${found.nis}] ${found.name} — ${found.className}`;
                            if (clearBtn) clearBtn.classList.remove('hidden');
                        }
                    } else if (!memberSelect.value) {
                        searchInput.value = '';
                        if (clearBtn) clearBtn.classList.add('hidden');
                    }
                }
            });

            // Pre-select if already chosen
            if (memberSelect.value) {
                const initial = studentOptions.find(s => String(s.id) === String(memberSelect.value));
                if (initial) {
                    searchInput.value = `[NIS: ${initial.nis}] ${initial.name} — ${initial.className}`;
                    if (clearBtn) clearBtn.classList.remove('hidden');
                }
            }
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSaverConfirmModal();
        }
    });
</script>
@endpush
@endsection
