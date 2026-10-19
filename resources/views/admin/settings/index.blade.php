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

        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all shadow-xs flex items-center gap-2 cursor-pointer">
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
                                        <input type="number" 
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
@endsection
