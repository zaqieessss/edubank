@extends('layouts.app')

@section('title', 'Buka Rekening Tabungan Siswa')
@section('page_title', 'Buka Rekening Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 pb-8">

    <!-- Back Button & Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.savers.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Daftar Nasabah</span>
        </a>
        <span class="text-xs text-slate-400 font-mono">Prosedur Standar Akuntansi Bank Mini</span>
    </div>

    <!-- Bento Form Card -->
    <div class="bg-white rounded-[28px] p-6 border border-slate-200/80 shadow-[0_4px_30px_-5px_rgba(15,23,42,0.04)] space-y-6">
        
        <!-- Header -->
        <div class="border-b border-slate-100 pb-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping"></span>
                Registrasi Rekening Simpanan Siswa
            </div>
            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Form Pembukaan Rekening Tabungan Baru</h2>
            <p class="text-xs text-slate-500 mt-1">
                Pilih siswa dari database master (600 siswa). Sistem akan otomatis membuatkan Nomor Rekening berformat <span class="font-mono font-bold text-slate-800">[JURUSAN]-[ANGKATAN]-[0001]</span>, mengaktifkan akun login, membuat QR Code, dan membukukan jurnal akuntansi debit-kredit.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.savers.store') }}" class="space-y-5">
            @csrf

            <!-- 1. Pilih Siswa -->
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1.5">
                    1. Pilih Siswa (Hanya menampilkan siswa yang belum memiliki rekening) *
                </label>
                @if($unregisteredMembers->isEmpty())
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-2xl text-xs">
                        Semua siswa di master database telah memiliki rekening tabungan! Jika ingin menambah nasabah baru, tambahkan terlebih dahulu melalui menu <a href="{{ route('admin.members.index') }}" class="font-bold underline">Members</a>.
                    </div>
                @else
                    <select name="member_id" id="member_select" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 font-medium">
                        <option value="">-- Pilih Siswa Berdasarkan NIS / Nama Lengkap --</option>
                        @foreach($unregisteredMembers as $m)
                            <option value="{{ $m->id }}" 
                                    data-nis="{{ $m->student_id_number }}"
                                    data-major="{{ $m->major }}"
                                    data-class="{{ $m->class_name }}"
                                    data-cohort="{{ $m->cohort }}"
                                    {{ (old('member_id') == $m->id || request('member_id') == $m->id) ? 'selected' : '' }}>
                                [NIS: {{ $m->student_id_number }}] {{ $m->full_name }} — {{ $m->class_name }} ({{ $m->major }})
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('member_id')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview Data Siswa Terpilih (Interactive) -->
            <div id="student_preview" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
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
                    <span class="text-[10px] uppercase font-bold text-slate-400 block">Estimasi Format Rekening</span>
                    <span id="prev_acc" class="font-mono font-bold text-blue-600">-</span>
                </div>
            </div>

            <!-- 2. Setoran Awal Tabungan -->
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1.5">
                    2. Setoran Awal Tunai (Wajib Minimal Rp 10.000) *
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" 
                           name="initial_deposit" 
                           id="initial_deposit" 
                           min="10000" 
                           step="1000" 
                           value="{{ old('initial_deposit', 10000) }}" 
                           required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-4 py-2.5 text-sm font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[10px] text-slate-500">Pilihan Cepat:</span>
                    <button type="button" onclick="setDeposit(10000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 10.000</button>
                    <button type="button" onclick="setDeposit(20000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 20.000</button>
                    <button type="button" onclick="setDeposit(50000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 50.000</button>
                    <button type="button" onclick="setDeposit(100000)" class="px-2.5 py-0.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold">Rp 100.000</button>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">
                    * Sesuai ketentuan Bank Mini SMKN 1 Bantul, Rp 10.000 menjadi saldo minimum mengendap (holding balance) yang tidak dapat ditarik habis.
                </p>
                @error('initial_deposit')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. PIN Keamanan Rekening -->
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1.5">
                    3. Buat PIN Keamanan Nasabah (6 Digit Angka) *
                </label>
                <input type="password" 
                       name="security_pin" 
                       id="security_pin" 
                       maxlength="6" 
                       pattern="[0-9]{6}" 
                       value="{{ old('security_pin', '123456') }}" 
                       placeholder="Misal: 123456" 
                       required 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-2.5 text-sm font-mono tracking-widest text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                <p class="text-[11px] text-slate-400 mt-1">
                    PIN ini digunakan siswa untuk otorisasi penarikan tunai di loket teller dan login mobile.
                </p>
                @error('security_pin')
                    <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.savers.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-2xl text-xs font-semibold hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit" {{ $unregisteredMembers->isEmpty() ? 'disabled' : '' }} class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white rounded-2xl text-xs font-bold shadow-md shadow-blue-600/20 transition-all hover:shadow-lg">
                    Proses Pembukaan Rekening & Cetak Kartu
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function setDeposit(amount) {
        document.getElementById('initial_deposit').value = amount;
    }

    const memberSelect = document.getElementById('member_select');
    if (memberSelect) {
        function updatePreview() {
            const opt = memberSelect.options[memberSelect.selectedIndex];
            if (opt && opt.value) {
                document.getElementById('prev_major').innerText = opt.dataset.major || '-';
                document.getElementById('prev_class').innerText = opt.dataset.class || '-';
                document.getElementById('prev_cohort').innerText = opt.dataset.cohort || '-';
                document.getElementById('prev_acc').innerText = (opt.dataset.major || 'XXX') + '-' + (opt.dataset.cohort || '2026') + '-XXXX';
            } else {
                document.getElementById('prev_major').innerText = '-';
                document.getElementById('prev_class').innerText = '-';
                document.getElementById('prev_cohort').innerText = '-';
                document.getElementById('prev_acc').innerText = '-';
            }
        }
        memberSelect.addEventListener('change', updatePreview);
        updatePreview();
    }
</script>
@endpush
@endsection
