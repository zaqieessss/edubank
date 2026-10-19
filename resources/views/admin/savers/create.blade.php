@extends('layouts.app')

@section('title', 'Buka Rekening Tabungan Siswa')
@section('page_title', 'Buka Rekening Baru')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Tombol Kembali Dibungkus Card Rapi) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
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

    <!-- Bento Form Card (Memenuhi Layar Sejajar Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[420px]">
        <form method="POST" action="{{ route('admin.savers.store') }}" class="flex-1 flex flex-col justify-between">
            @csrf

            <div class="space-y-4">
                <!-- Header Info -->
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Form Pembukaan Rekening Tabungan Baru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Pilih siswa dari database master (600 siswa). Sistem akan otomatis membuatkan Nomor Rekening berformat <span class="font-mono font-bold text-slate-800">[JURUSAN]-[ANGKATAN]-[0001]</span>, mengaktifkan akun login, membuat QR Code, dan membukukan mutasi setoran awal.
                    </p>
                </div>

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
                        <select name="member_id" id="member_select" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 font-medium">
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

                <!-- Grid Baris: Setoran Awal & PIN Keamanan -->
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
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono tracking-widest text-slate-900 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                        <p class="text-[10px] text-slate-400 mt-1">
                            PIN ini digunakan siswa untuk otorisasi penarikan tunai di loket teller dan login mobile.
                        </p>
                        @error('security_pin')
                            <p class="text-[11px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button (Pinned at Bottom) -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0 mt-4">
                <a href="{{ route('admin.savers.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200 transition-all">
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
            Apakah Anda yakin ingin memproses pembukaan rekening tabungan baru untuk siswa terpilih? Sistem akan langsung membuat nomor rekening resmi, QR Code, dan membukukan mutasi setoran awal.
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
    function triggerSaverConfirm() {
        const form = document.querySelector('form[action="{{ route('admin.savers.store') }}"]');
        if (form && form.reportValidity()) {
            openSaverConfirmModal();
        }
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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSaverConfirmModal();
        }
    });
</script>
@endpush
@endsection
