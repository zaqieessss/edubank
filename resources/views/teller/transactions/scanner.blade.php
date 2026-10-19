@extends('layouts.app')

@section('title', 'Scan QR Kartu Tabungan Siswa')
@section('page_title', 'Scanner Kartu Siswa')

@section('content')
<div class="h-full flex flex-col justify-between gap-4 pb-0.5">

    <!-- Top Navigation Card (Konsisten & Rapi Terbungkus Card) -->
    <div class="bg-white rounded-[24px] p-5 border border-slate-200/70 shadow-[0_4px_20px_-4px_rgba(15,23,42,0.03)] shrink-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('teller.dashboard') }}" 
               class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors shrink-0"
               title="Kembali ke Dashboard Loket">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pemindai Kartu Siswa</span>
                <h1 class="text-base font-extrabold text-slate-900 mt-0.5">
                    Scan QR Tabungan Siswa
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400 font-mono">HTML5 Optical QR Reader</span>
        </div>
    </div>

    <!-- Scanner Bento Box (Sejajar & Fluid dengan Sidebar) -->
    <div class="bg-white rounded-[24px] p-6 md:p-8 border border-slate-200/70 shadow-[0_4px_25px_-5px_rgba(15,23,42,0.03)] flex-1 flex flex-col justify-between min-h-[460px]">
        
        <div class="space-y-4 max-w-xl mx-auto w-full">
            <div class="text-center space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                    Pemindai Kartu Tabungan Digital
                </div>
                <h2 class="text-lg font-extrabold text-slate-900">Arahkan Kamera ke QR Code Kartu Siswa</h2>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Posisikan QR Code di kartu tabungan atau layar smartphone siswa di dalam kotak target pemindai.
                </p>
            </div>

            <!-- Camera Viewport Container -->
            <div class="relative w-full aspect-[4/3] max-w-xs mx-auto bg-slate-950 rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 shadow-inner flex flex-col items-center justify-center">
                <div id="reader" class="w-full h-full"></div>
                
                <!-- Scan target overlay -->
                <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                    <div class="w-40 h-40 border-2 border-blue-500 rounded-2xl shadow-[0_0_0_9999px_rgba(15,23,42,0.4)] relative animate-pulse">
                        <div class="absolute -top-1 -left-1 w-4 h-4 border-t-2 border-l-2 border-white rounded-tl-lg"></div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 border-t-2 border-r-2 border-white rounded-tr-lg"></div>
                        <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-2 border-l-2 border-white rounded-bl-lg"></div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-2 border-r-2 border-white rounded-br-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Scan Status Feedback -->
            <div id="scan-status" class="text-center text-xs text-slate-500 font-medium">
                Kamera pemindai aktif. Menunggu kode QR...
            </div>
        </div>

        <!-- Fallback Manual Input -->
        <div class="pt-4 border-t border-slate-100 max-w-xl mx-auto w-full">
            <p class="text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                Atau Masukkan Nomor Rekening / NIS Secara Manual
            </p>
            <form id="manualForm" method="POST" action="{{ route('teller.transactions.search') }}" class="flex items-center gap-2 max-w-md mx-auto">
                @csrf
                <div class="relative flex-1">
                    <input type="text" 
                           id="queryInput" 
                           name="query" 
                           placeholder="Contoh: RPL-2026-0001 atau NIS: 20260101" 
                           required 
                           class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-2.5 text-xs text-slate-800 font-mono font-bold focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition-all hover:shadow shrink-0">
                    Buka Loket
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let isProcessing = false;

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        isProcessing = true;

        const statusEl = document.getElementById('scan-status');
        statusEl.innerHTML = `<span class="text-emerald-600 font-bold">QR Berhasil Terbaca: ${decodedText}. Memuat profil siswa...</span>`;
        
        // Populate manual input and submit
        document.getElementById('queryInput').value = decodedText;
        document.getElementById('manualForm').submit();
    }

    function onScanFailure(error) {
        // Quiet failure for frame-by-frame scanning
    }

    try {
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { 
                fps: 10, 
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.333333
            },
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    } catch (e) {
        document.getElementById('scan-status').innerHTML = '<span class="text-amber-600">Pemindai web camera tidak aktif. Silakan gunakan input manual di bawah.</span>';
    }
</script>
@endpush
@endsection
