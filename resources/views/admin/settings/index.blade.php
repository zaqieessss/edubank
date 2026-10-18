@extends('layouts.app')

@section('title', 'Pengaturan Sistem (Setting)')
@section('page_title', 'Setting / Pengaturan')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 pb-8">

    <!-- Header Description -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Konfigurasi Parameter Edubank</h2>
            <p class="text-xs text-slate-500">Aturan perbankan mini, holding balance, dan parameter instansi sekolah</p>
        </div>
        <span class="text-xs font-mono text-slate-400">SMKN 1 Bantul &bull; TA 2026/2027</span>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
        @csrf

        @foreach($settings as $group => $items)
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-4">
                
                <!-- Group Header -->
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl {{ $group === 'banking' ? 'bg-blue-50 text-blue-600' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center font-bold text-xs">
                        @if($group === 'banking')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                            {{ $group === 'banking' ? 'Parameter Operasional Perbankan' : 'Data & Profil Sekolah' }}
                        </h3>
                        <p class="text-[10px] text-slate-400">Pengaturan grup: {{ $group }}</p>
                    </div>
                </div>

                <!-- Input Fields Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($items as $s)
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">
                                {{ $s->label }}
                            </label>
                            
                            @if($s->type === 'number')
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[11px] font-bold text-slate-400">Rp</span>
                                    <input type="number" 
                                           name="{{ $s->key }}" 
                                           value="{{ $s->value }}" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                </div>
                            @elseif($s->type === 'boolean')
                                <select name="{{ $s->key }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                    <option value="1" {{ $s->value == '1' ? 'selected' : '' }}>Aktif / Diaktifkan</option>
                                    <option value="0" {{ $s->value == '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            @else
                                <input type="text" 
                                       name="{{ $s->key }}" 
                                       value="{{ $s->value }}" 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            @endif

                            @if($s->description)
                                <p class="text-[10px] text-slate-400">{{ $s->description }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Save Button -->
        <div class="pt-2 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold shadow-md shadow-slate-900/20 transition-all hover:shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Seluruh Pengaturan</span>
            </button>
        </div>
    </form>
</div>
@endsection
