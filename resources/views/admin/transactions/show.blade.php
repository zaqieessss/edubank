@extends('layouts.app')

@section('title', 'Detail Transaksi: ' . $transaction->receipt_number)
@section('page_title', 'Detail Transaksi')

@section('content')
<div class="min-h-full flex-1 flex flex-col justify-between gap-4 pb-2">

    <!-- Top Navigation Card (Konsisten & Rapi Terbungkus Card) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.transactions.index') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
               title="Kembali ke Laporan Transaksi">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Laporan Audit Transaksi</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    Transaksi: {{ $transaction->receipt_number }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $transaction->transaction_type === 'deposit' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                <span class="w-2 h-2 rounded-full {{ $transaction->transaction_type === 'deposit' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                {{ $transaction->transaction_type === 'deposit' ? 'SETORAN TUNAI' : 'PENARIKAN TUNAI' }}
            </span>
        </div>
    </div>

    <!-- Main Bento Grid (2 Kolom: Detail Finansial & Jurnal Akuntansi Double-Entry) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 flex-1 min-h-0">
        
        <!-- Kolom Kiri: Profil Nasabah & Ringkasan Mutasi -->
        <div class="lg:col-span-5 flex flex-col justify-between gap-4">
            
            <!-- Profil Nasabah Card -->
            <div class="bg-white rounded-[26px] p-5 md:p-6 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] space-y-4">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 mb-1">
                            {{ $transaction->saver?->major ?? '-' }} &bull; Angkatan {{ $transaction->saver?->cohort ?? '-' }}
                        </span>
                        <h3 class="text-base font-extrabold text-slate-900 leading-tight">
                            {{ $transaction->saver?->full_name ?? '-' }}
                        </h3>
                        <p class="text-xs text-slate-500">Kelas: <strong class="text-slate-800">{{ $transaction->saver?->class_name ?? '-' }}</strong></p>
                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">NIS: {{ $transaction->saver?->student_id_number ?? '-' }}</p>
                    </div>

                    <div class="text-right">
                        <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">No. Rekening</span>
                        <a href="{{ route('admin.accounts.show', $transaction->account_id) }}" class="font-mono font-extrabold text-blue-700 text-sm hover:underline">
                            {{ $transaction->saver?->account_number ?? '-' }}
                        </a>
                    </div>
                </div>

                <!-- Financial Amount Display -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white space-y-3.5 shadow-md shadow-slate-900/10">
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-slate-400 block tracking-wider">
                            Nominal {{ $transaction->transaction_type === 'deposit' ? 'Setoran' : 'Penarikan' }}
                        </span>
                        <div class="text-3xl font-extrabold font-mono mt-0.5 tracking-tight {{ $transaction->transaction_type === 'deposit' ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $transaction->transaction_type === 'deposit' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-white/10 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Saldo Sebelum</span>
                            <span class="font-mono font-bold text-slate-200">Rp {{ number_format($transaction->balance_before, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Saldo Setelah</span>
                            <span class="font-mono font-bold text-white">Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Petugas Teller & Waktu Eksekusi -->
            <div class="bg-white rounded-[26px] p-5 border border-slate-200/80 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Informasi Pelayanan Loket</h4>
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Petugas Teller:</span>
                            <span class="font-bold text-slate-800">{{ $transaction->user?->name ?? 'System' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Email Teller:</span>
                            <span class="text-slate-600 font-mono">{{ $transaction->user?->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-400">Waktu Transaksi:</span>
                            <span class="font-mono font-semibold text-slate-800">{{ $transaction->transaction_date->format('d F Y, H:i:s') }} WIB</span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-slate-400">Keterangan:</span>
                            <span class="text-slate-700 italic">{{ $transaction->description ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Audit Trail Bank Mini</span>
                    <span class="font-mono">Edubank SMKN 1 Bantul</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ayat Jurnal Akuntansi Double-Entry & Detail Mutasi Kas -->
        <div class="lg:col-span-7 flex flex-col">
            <div class="bg-white rounded-[28px] p-6 md:p-8 border border-slate-200/80 shadow-[0_4px_30px_-5px_rgba(15,23,42,0.04)] flex-1 flex flex-col justify-between">
                
                <div class="space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                                    Pencatatan Buku Besar & Ayat Jurnal (Double-Entry)
                                </h3>
                                <p class="text-[11px] text-slate-400">Setiap mutasi kas siswa otomatis membentuk pasangan debet & kredit seimbang.</p>
                            </div>
                        </div>

                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Seimbang (Balanced)
                        </span>
                    </div>

                    <!-- Journal Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-semibold text-slate-400">
                                    <th class="py-2.5 px-3">Kode Akun</th>
                                    <th class="py-2.5 px-3">Nama Akun (COA)</th>
                                    <th class="py-2.5 px-3">Posisi</th>
                                    <th class="py-2.5 px-3 text-right">Debet</th>
                                    <th class="py-2.5 px-3 text-right">Kredit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($transaction->journals as $j)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3 px-3 font-mono font-bold text-blue-700 text-xs">
                                            {{ $j->account_code }}
                                        </td>
                                        <td class="py-3 px-3">
                                            <div class="font-bold text-slate-800">{{ $j->account_name }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $j->description }}</div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-mono font-bold uppercase {{ $j->position === 'debit' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                                {{ $j->position }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-right font-mono font-bold text-xs {{ $j->position === 'debit' ? 'text-slate-900' : 'text-slate-400' }}">
                                            {{ $j->position === 'debit' ? 'Rp ' . number_format($j->amount, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="py-3 px-3 text-right font-mono font-bold text-xs {{ $j->position === 'credit' ? 'text-slate-900' : 'text-slate-400' }}">
                                            {{ $j->position === 'credit' ? 'Rp ' . number_format($j->amount, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400">
                                            Tidak ada catatan jurnal double-entry ditemukan untuk transaksi ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="border-t-2 border-slate-200 font-mono font-extrabold text-xs">
                                <tr>
                                    <td colspan="3" class="py-3 px-3 text-right uppercase font-sans text-slate-700">Total Keseimbangan:</td>
                                    <td class="py-3 px-3 text-right text-slate-900">
                                        Rp {{ number_format($transaction->journals->where('position', 'debit')->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3 text-right text-slate-900">
                                        Rp {{ number_format($transaction->journals->where('position', 'credit')->sum('amount'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Thermal Receipt Simulation Box -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                Struk Fisik Diterbitkan:
                            </span>
                            <span class="font-mono text-slate-500">{{ $transaction->receipt_number }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            Struk resmi telah dicetak saat transaksi disetujui di loket teller dengan otorisasi tanda tangan nasabah (siswa) dan teller pelayan.
                        </p>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.transactions.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                        &larr; Kembali ke Daftar
                    </a>
                    <a href="{{ route('admin.accounts.show', $transaction->account_id) }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                        Buka Rekening Nasabah &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
