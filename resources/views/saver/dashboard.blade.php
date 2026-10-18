@extends('layouts.app')

@section('title', 'Tabungan Digital Siswa - ' . $saver->full_name)
@section('page_title', 'Tabungan Saya')

@section('content')
<div class="space-y-4 pb-8">

    <!-- Bento Grid Layout: 2 Columns (Digital Card & PIN vs Mutations) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        
        <!-- Left: Student Digital Card & Balance Box -->
        <div class="lg:col-span-5 space-y-4">
            
            <!-- SMART DIGITAL STUDENT CARD -->
            <div class="relative w-full aspect-[1.586/1] rounded-[26px] bg-gradient-to-tr from-slate-950 via-slate-900 to-indigo-950 p-5 text-white shadow-xl shadow-slate-900/15 overflow-hidden flex flex-col justify-between border border-slate-700/60">
                <div class="absolute -right-16 -top-16 w-56 h-56 bg-gradient-to-br from-blue-500/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-56 h-56 bg-gradient-to-tr from-indigo-500/20 to-transparent rounded-full blur-2xl pointer-events-none"></div>

                <!-- Header -->
                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center font-bold text-white shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-extrabold tracking-wider uppercase text-white">EDUBANK MINI</h4>
                            <p class="text-[9px] text-slate-400">SMK Negeri 1 Bantul</p>
                        </div>
                    </div>
                    <!-- Chip -->
                    <div class="w-9 h-7 rounded-md bg-gradient-to-r from-amber-300 via-amber-400 to-amber-200 border border-amber-500/60 flex items-center justify-center shadow-inner">
                        <div class="w-6 h-4 border border-amber-600/40 rounded-xs flex items-center justify-center">
                            <div class="w-3 h-2 border-t border-b border-amber-600/40"></div>
                        </div>
                    </div>
                </div>

                <!-- Account Number -->
                <div class="relative z-10 my-auto text-center py-2">
                    <p class="text-[9px] uppercase tracking-widest text-slate-400 font-semibold mb-0.5">Nomor Rekening</p>
                    <p class="text-xl font-mono font-bold tracking-widest text-blue-300 drop-shadow-sm">
                        {{ $saver->account_number }}
                    </p>
                </div>

                <!-- Card Footer (Student Details & QR) -->
                <div class="relative z-10 flex items-end justify-between border-t border-slate-800/80 pt-3">
                    <div>
                        <p class="text-sm font-bold text-white uppercase tracking-tight">{{ $saver->full_name }}</p>
                        <p class="text-[10px] text-slate-300">NIS: <span class="font-mono text-slate-200">{{ $saver->student_id_number }}</span> | {{ $saver->class_name }}</p>
                        <p class="text-[9px] text-slate-400 font-medium mt-0.5">{{ $saver->major }} &bull; Angkatan {{ $saver->cohort }}</p>
                    </div>

                    <!-- Clean QR Code with crisp border -->
                    <div class="w-16 h-16 bg-white p-1 rounded-xl shadow-md shrink-0 flex items-center justify-center">
                        <img src="{{ $qrCodeDataUri }}" alt="QR Code Rekening" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>

            <!-- Balance Details Bento Card -->
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Saldo Tabungan Saat Ini</span>
                <div class="text-2xl font-extrabold font-mono text-blue-700">
                    Rp {{ number_format($saver->balance, 0, ',', '.') }}
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Saldo Mengendap (Holding):</span>
                        <span class="font-mono font-bold text-slate-700">Rp 10.000</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-200/70 text-emerald-700 font-bold">
                        <span>Dapat Ditarik Tunai:</span>
                        <span class="font-mono font-extrabold text-sm">Rp {{ number_format(max(0, $saver->balance - 10000), 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Change PIN Button -->
                <button type="button" onclick="document.getElementById('pinModal').classList.remove('hidden')" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span>Ganti 6-Digit PIN Transaksi</span>
                </button>
            </div>
        </div>

        <!-- Right: Mutations Ledger -->
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-3.5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Mutasi Rekening Terakhir</h4>
                        <p class="text-[10px] text-slate-400">Catatan transaksi setor & tarik tabungan Anda</p>
                    </div>
                    <a href="{{ route('saver.mutation') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                        Buku Tabungan Lengkap &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-100">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/60">
                                <th class="py-2.5 px-3">Tanggal</th>
                                <th class="py-2.5 px-3">Kwitansi</th>
                                <th class="py-2.5 px-3">Transaksi</th>
                                <th class="py-2.5 px-3 text-right">Nominal</th>
                                <th class="py-2.5 px-3 text-right">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentTransactions as $t)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-2.5 px-3 text-slate-500 text-[11px]">
                                        {{ $t->transaction_date->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-2.5 px-3 font-mono font-bold text-slate-700 text-[11px]">
                                        {{ $t->receipt_number }}
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if($t->transaction_type === 'deposit')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700">
                                                Setoran
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700">
                                                Penarikan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-[11px] {{ $t->transaction_type === 'deposit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $t->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 text-[11px]">
                                        Rp {{ number_format($t->balance_after, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">
                                        Belum ada catatan transaksi pada rekening Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: Ganti PIN Keamanan Siswa                                           -->
<!-- ========================================================================= -->
<div id="pinModal" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Perbarui PIN Keamanan</h3>
            <button onclick="document.getElementById('pinModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('saver.pin.update') }}" class="mt-4 space-y-3.5">
            @csrf
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">PIN Saat Ini (6 Digit) *</label>
                <input type="password" name="current_pin" maxlength="6" pattern="[0-9]{6}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-center text-base font-mono tracking-widest text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">PIN Baru (6 Digit) *</label>
                <input type="password" name="new_pin" maxlength="6" pattern="[0-9]{6}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-center text-base font-mono tracking-widest text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Konfirmasi PIN Baru *</label>
                <input type="password" name="new_pin_confirmation" maxlength="6" pattern="[0-9]{6}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-center text-base font-mono tracking-widest text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('pinModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 shadow-sm">Simpan PIN Baru</button>
            </div>
        </form>
    </div>
</div>
@endsection
