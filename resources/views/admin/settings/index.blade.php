@extends('layouts.app')

@section('title', 'Pengaturan Sistem (Setting)')
@section('page_title', 'Pengaturan Sistem')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="h-full flex flex-col justify-between gap-4 pb-0.5">
    @csrf

    <!-- Top Navigation & Header Card -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex items-center justify-between gap-3">
        <div>
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Konfigurasi Parameter</span>
            <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                Pengaturan Sistem Bank Mini
            </h1>
        </div>

        <button type="button" onclick="triggerSettingsConfirm()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Simpan Perubahan</span>
        </button>
    </div>

    <!-- Main 2-Column Bento Grid (Memenuhi Layar, Banyak Whitespace) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 flex-1 min-h-0">
        @foreach($settings as $group => $items)
            <div class="bg-white rounded-[24px] p-6 md:p-7 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex flex-col justify-between min-h-[420px]">
                <div class="space-y-5">
                    <div class="pb-3 border-b border-slate-100 flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl {{ $group === 'banking' ? 'bg-blue-50 text-blue-600' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center shrink-0">
                            @if($group === 'banking')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @endif
                        </div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                            {{ $group === 'banking' ? 'Parameter Operasional Perbankan' : 'Data & Profil Sekolah' }}
                        </h3>
                    </div>

                    <div class="space-y-4">
                        @foreach($items as $s)
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    {{ $s->label }}
                                </label>
                                
                                @if($s->type === 'number')
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 font-mono">Rp</span>
                                        <input type="text" 
                                               inputmode="numeric"
                                               data-currency="true"
                                               name="{{ $s->key }}" 
                                               value="{{ $s->value }}" 
                                               class="w-full bg-slate-50 border border-slate-200/80 rounded-xl pl-10 pr-3.5 py-2.5 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                                    </div>
                                @elseif($s->type === 'boolean')
                                    <select name="{{ $s->key }}" class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                                        <option value="1" {{ $s->value == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $s->value == '0' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                @else
                                    <input type="text" 
                                           name="{{ $s->key }}" 
                                           value="{{ $s->value }}" 
                                           class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</form>

<!-- Modal Konfirmasi Simpan Pengaturan (Centered Backdrop Blur) -->
<div id="confirmSettingsModal" 
     class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs"
     onclick="if(event.target === this) closeSettingsConfirmModal()">
    
    <div class="bg-white rounded-[24px] max-w-md w-full p-6 border border-slate-200/80 shadow-2xl space-y-4 relative">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Konfirmasi Pengaturan</h3>
            </div>
            <button type="button" onclick="closeSettingsConfirmModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors font-bold text-sm">
                &times;
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Apakah Anda yakin ingin menerapkan perubahan konfigurasi perbankan dan data sekolah ini? Pengaturan akan langsung berlaku bagi operasional sistem.
        </p>

        <div class="flex items-center justify-end gap-2 pt-2">
            <button type="button" onclick="closeSettingsConfirmModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Periksa Kembali
            </button>
            <button type="button" onclick="submitSettingsForm()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs cursor-pointer">
                Ya, Simpan Perubahan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function triggerSettingsConfirm() {
        const form = document.querySelector('form[action="{{ route('admin.settings.update') }}"]');
        if (form && form.reportValidity()) {
            openSettingsConfirmModal();
        }
    }

    function openSettingsConfirmModal() {
        const modal = document.getElementById('confirmSettingsModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeSettingsConfirmModal() {
        const modal = document.getElementById('confirmSettingsModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitSettingsForm() {
        const form = document.querySelector('form[action="{{ route('admin.settings.update') }}"]');
        if (form) form.submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSettingsConfirmModal();
        }
    });
</script>
@endpush
@endsection
