<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi {{ $periodDate->translatedFormat('F Y') }} - Edubank SMKN 1 Bantul</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            color: #0f172a;
            background: #f8fafc;
            padding: 24px;
            font-size: 11px;
            line-height: 1.4;
        }
        .mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .container {
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
            background: #ffffff;
            padding: 28px 32px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        
        /* Action Toolbar on Screen */
        .no-print-bar {
            max-width: 210mm;
            margin: 0 auto 16px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.15s ease;
        }
        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background: #f1f5f9;
        }
        .btn-print {
            background: #0f172a;
            color: #ffffff;
        }
        .btn-print:hover {
            background: #1e293b;
        }

        /* Kop Surat Resmi Bank Mini */
        .header-kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .header-kop .brand h1 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header-kop .brand h2 {
            font-size: 13px;
            font-weight: 700;
            color: #1e3a8a;
        }
        .header-kop .brand p {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .header-kop .meta {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }
        .header-kop .meta strong {
            display: block;
            font-size: 11px;
            color: #0f172a;
        }

        /* Judul Dokumen */
        .doc-title {
            text-align: center;
            margin-bottom: 16px;
        }
        .doc-title h3 {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .doc-title span {
            display: inline-block;
            margin-top: 3px;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
        }

        /* Summary Cards Grid */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }
        .summary-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            background: #f8fafc;
        }
        .summary-card .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            display: block;
        }
        .summary-card .value {
            font-size: 13px;
            font-weight: 800;
            margin-top: 4px;
            font-family: 'JetBrains Mono', monospace;
        }
        .text-emerald { color: #047857; }
        .text-rose { color: #b91c1c; }
        .text-dark { color: #0f172a; }

        /* Data Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 20px;
        }
        th, td {
            padding: 7px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Badge Status */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-deposit { background: #dcfce7; color: #15803d; }
        .badge-withdraw { background: #ffe4e6; color: #be123c; }

        /* Signatures */
        .signature-box {
            display: flex;
            justify-content: space-between;
            margin-top: 28px;
            page-break-inside: avoid;
        }
        .signature-col {
            width: 200px;
            text-align: center;
            font-size: 10px;
        }
        .signature-col p {
            color: #475569;
            margin-bottom: 50px;
        }
        .signature-col .name {
            font-weight: 700;
            color: #0f172a;
            border-top: 1px solid #0f172a;
            padding-top: 4px;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .container {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            th {
                background-color: #e2e8f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .summary-card {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-deposit {
                background: #dcfce7 !important;
                color: #15803d !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-withdraw {
                background: #ffe4e6 !important;
                color: #be123c !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Aksi di Layar Komputer (Tidak Tercetak) -->
    <div class="no-print-bar">
        <a href="{{ route('admin.transactions.index', ['month' => $selectedMonth, 'year' => $selectedYear]) }}" class="btn btn-back">
            &larr; Kembali ke Dashboard
        </a>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF (Ctrl+P)</span>
            </button>
        </div>
    </div>

    <!-- Container Dokumen PDF -->
    <div class="container">
        
        <!-- Kop Surat -->
        <div class="header-kop">
            <div style="display: flex; align-items: center; gap: 14px;">
                <img src="{{ asset('images/logo-blue.png') }}" alt="Logo Edubank" style="width: 50px; height: 50px; object-fit: contain;">
                <div class="brand">
                    <h1>BANK MINI EDUBANK</h1>
                    <h2>SMK NEGERI 1 BANTUL</h2>
                    <p>Jl. Parangtritis No.KM.11, Sabdodadi, Bantul, D.I. Yogyakarta 55715</p>
                </div>
            </div>
            <div class="meta">
                <strong>LAPORAN REKAPITULASI RESMI</strong>
                <span>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                <span>Oleh: {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="doc-title">
            <h3>LAPORAN MUTASI KAS TRANSAKSI TABUNGAN SISWA</h3>
            <span>PERIODE BULAN: {{ strtoupper($periodDate->translatedFormat('F Y')) }}</span>
        </div>

        <!-- Rangkuman Finansial Bulan Ini -->
        <div class="summary-grid">
            <div class="summary-card">
                <span class="label">Total Transaksi</span>
                <div class="value text-dark">{{ number_format($totalTransactions, 0, ',', '.') }} Mutasi</div>
            </div>
            <div class="summary-card">
                <span class="label">Total Setoran</span>
                <div class="value text-emerald">+Rp {{ number_format($totalDepositAmount, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <span class="label">Total Penarikan</span>
                <div class="value text-rose">-Rp {{ number_format($totalWithdrawalAmount, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <span class="label">Arus Kas Bersih</span>
                <div class="value {{ $netCashFlow >= 0 ? 'text-dark' : 'text-rose' }}">
                    {{ $netCashFlow >= 0 ? '+' : '' }}Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Tabel Rekap Transaksi -->
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 28px;">No</th>
                    <th>No. Kwitansi</th>
                    <th>Tanggal / Waktu</th>
                    <th>Nama Nasabah (Siswa)</th>
                    <th>No. Rekening</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-right">Nominal (Rp)</th>
                    <th class="text-right">Saldo Akhir (Rp)</th>
                    <th>Petugas Teller</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $t)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="mono" style="font-weight: 600;">{{ $t->receipt_number }}</td>
                        <td class="mono">{{ $t->transaction_date->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $t->saver?->full_name ?? '-' }}</strong>
                            <div style="font-size: 8.5px; color: #64748b;">{{ $t->saver?->class_name ?? '-' }} &bull; NIS: {{ $t->saver?->student_id_number ?? '-' }}</div>
                        </td>
                        <td class="mono" style="color: #1d4ed8; font-weight: 600;">{{ $t->saver?->account_number ?? '-' }}</td>
                        <td class="text-center">
                            @if($t->transaction_type === 'deposit')
                                <span class="badge badge-deposit">Setor</span>
                            @else
                                <span class="badge badge-withdraw">Tarik</span>
                            @endif
                        </td>
                        <td class="text-right mono" style="font-weight: 700; color: {{ $t->transaction_type === 'deposit' ? '#047857' : '#b91c1c' }};">
                            {{ $t->transaction_type === 'deposit' ? '+' : '-' }}{{ number_format($t->amount, 0, ',', '.') }}
                        </td>
                        <td class="text-right mono" style="font-weight: 600;">
                            {{ number_format($t->balance_after, 0, ',', '.') }}
                        </td>
                        <td>{{ $t->user?->name ?? 'System' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 24px; color: #64748b;">
                            Tidak ada transaksi yang tercatat pada periode {{ $periodDate->translatedFormat('F Y') }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 700;">
                    <td colspan="6" class="text-right" style="text-transform: uppercase;">Akumulasi Setoran Masuk:</td>
                    <td class="text-right mono text-emerald">+Rp {{ number_format($totalDepositAmount, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
                <tr style="background: #f8fafc; font-weight: 700;">
                    <td colspan="6" class="text-right" style="text-transform: uppercase;">Akumulasi Penarikan Keluar:</td>
                    <td class="text-right mono text-rose">-Rp {{ number_format($totalWithdrawalAmount, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
                <tr style="background: #f1f5f9; font-weight: 800; border-top: 2px solid #0f172a;">
                    <td colspan="6" class="text-right" style="text-transform: uppercase;">Selisih Arus Kas Bersih (Net Cash):</td>
                    <td class="text-right mono {{ $netCashFlow >= 0 ? 'text-dark' : 'text-rose' }}">
                        {{ $netCashFlow >= 0 ? '+' : '' }}Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        <!-- Tanda Tangan Pengesahan -->
        <div class="signature-box">
            <div class="signature-col">
                <p>Mengetahui,<br>Kepala Bank Mini SMKN 1 Bantul</p>
                <div class="name">Dra. Hj. Sri Lestari, M.Pd.</div>
                <div style="font-size: 8.5px; color: #64748b;">NIP. 19720514 199802 2 001</div>
            </div>

            <div class="signature-col">
                <p>Bantul, {{ now()->translatedFormat('d F Y') }}<br>Supervisor / Administrator</p>
                <div class="name">{{ Auth::user()->name }}</div>
                <div style="font-size: 8.5px; color: #64748b;">ID Petugas: {{ Auth::user()->id }}</div>
            </div>
        </div>

    </div>

</body>
</html>
