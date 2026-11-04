<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tutup Kas (Daily Closing) - {{ \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('d F Y') }}</title>
    
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
            gap: 16px;
            padding-bottom: 16px;
            border-bottom: 2.5px solid #0f172a;
            margin-bottom: 18px;
        }
        .logo-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #0f172a;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
            letter-spacing: -1px;
            flex-shrink: 0;
        }
        .kop-text h1 {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0f172a;
        }
        .kop-text h2 {
            font-size: 13px;
            font-weight: 700;
            color: #1e3a8a;
        }
        .kop-text p {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .doc-title h3 {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .doc-title p {
            font-size: 11px;
            color: #475569;
            margin-top: 2px;
        }

        /* Metadata Grid */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 18px;
        }
        .meta-item .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }
        .meta-item .val {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2px;
        }

        /* Reconciliation Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .sum-card {
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }
        .sum-card.balanced {
            border-color: #a7f3d0;
            background: #ecfdf5;
        }
        .sum-card.discrepancy {
            border-color: #fecdd3;
            background: #fff1f2;
        }
        .sum-card .sum-label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
        }
        .sum-card .sum-val {
            font-size: 16px;
            font-weight: 800;
            margin-top: 4px;
            color: #0f172a;
        }

        /* Transactions Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th, td {
            padding: 6px 10px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }
        th {
            background: #f8fafc;
            color: #475569;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #cbd5e1;
        }
        td {
            font-size: 10.5px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-deposit {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-withdraw {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 28px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
        }
        .sig-title {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 50px;
        }
        .sig-name {
            font-weight: 700;
            font-size: 11px;
            border-top: 1px solid #0f172a;
            display: inline-block;
            padding-top: 4px;
            min-width: 180px;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .container {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- On-screen Toolbar -->
    <div class="no-print-bar">
        <a href="{{ route('admin.daily-closings.show', $dailyClosing->id) }}" class="btn btn-back">
            &larr; Kembali ke Detail Closing
        </a>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn btn-print">
                Cetak Laporan / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Printable A4 Container -->
    <div class="container">
        
        <!-- Header Kop -->
        <div class="header-kop">
            <img src="{{ asset('images/logo-blue.png') }}" alt="Logo Edubank" style="width: 52px; height: 52px; object-fit: contain; flex-shrink: 0;">
            <div class="kop-text">
                <h1>Edubank SMKN 1 Bantul</h1>
                <h2>Laboratorium Perbankan & Lembaga Keuangan Mikro Sekolah</h2>
                <p>Jl. Parangtritis No.KM.11, Sabdodadi, Bantul, D.I. Yogyakarta 55715 &bull; Telp: (0274) 367156</p>
            </div>
        </div>

        <!-- Document Title -->
        <div class="doc-title">
            <h3>Berita Acara Rekonsiliasi & Tutup Kas Harian</h3>
            <p>Tanggal: <strong class="mono">{{ \Carbon\Carbon::parse($dailyClosing->closing_date)->translatedFormat('l, d F Y') }}</strong> &bull; No. Referensi: <span class="mono">#CLS-{{ str_pad($dailyClosing->id, 5, '0', STR_PAD_LEFT) }}</span></p>
        </div>

        <!-- Metadata Grid -->
        <div class="meta-grid">
            <div class="meta-item">
                <div class="label">Petugas Teller</div>
                <div class="val">{{ $dailyClosing->teller?->name ?? '-' }}</div>
            </div>
            <div class="meta-item">
                <div class="label">Waktu Pengajuan</div>
                <div class="val mono">{{ $dailyClosing->created_at->format('d/m/Y H:i') }} WIB</div>
            </div>
            <div class="meta-item">
                <div class="label">Status Verifikasi</div>
                <div class="val" style="text-transform: capitalize;">{{ $dailyClosing->status }}</div>
            </div>
            <div class="meta-item">
                <div class="label">Supervisor / Verifikator</div>
                <div class="val">{{ $dailyClosing->admin?->name ?? 'Menunggu Approval' }}</div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="sum-card">
                <div class="sum-label">Saldo Sistem (Buku)</div>
                <div class="sum-val mono">Rp {{ number_format($dailyClosing->system_balance ?? $dailyClosing->system_cash, 0, ',', '.') }}</div>
            </div>
            <div class="sum-card">
                <div class="sum-label">Uang Fisik di Laci</div>
                <div class="sum-val mono">Rp {{ number_format($dailyClosing->physical_cash, 0, ',', '.') }}</div>
            </div>
            <div class="sum-card {{ $dailyClosing->difference === 0 ? 'balanced' : 'discrepancy' }}">
                <div class="sum-label">Selisih Kas Fisik</div>
                <div class="sum-val mono" style="{{ $dailyClosing->difference === 0 ? 'color: #059669;' : 'color: #e11d48;' }}">
                    {{ $dailyClosing->difference === 0 ? 'Rp 0 (SEIMBANG)' : 'Rp ' . number_format($dailyClosing->difference, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Section: Detail Transaksi Hari Ini -->
        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-end;">
            <h4 style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #0f172a;">
                Rincian Transaksi Teller Hari Ini ({{ $transactions->count() }} Transaksi)
            </h4>
            <span class="mono" style="font-size: 10px; color: #64748b;">
                Setoran: +Rp {{ number_format($dailyClosing->total_deposits, 0, ',', '.') }} | Tarikan: -Rp {{ number_format($dailyClosing->total_withdrawals, 0, ',', '.') }}
            </span>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 35px; text-align: center;">No</th>
                    <th style="width: 75px;">Waktu</th>
                    <th style="width: 110px;">Kode Trx</th>
                    <th>Nama Siswa / Rekening</th>
                    <th style="width: 80px; text-align: center;">Jenis</th>
                    <th style="text-align: right; width: 100px;">Nominal</th>
                    <th style="text-align: right; width: 100px;">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $trx)
                    <tr>
                        <td style="text-align: center; color: #64748b;" class="mono">{{ $index + 1 }}</td>
                        <td class="mono" style="color: #475569;">{{ \Carbon\Carbon::parse($trx->transaction_date)->format('H:i:s') }}</td>
                        <td class="mono" style="font-weight: 700; color: #1e3a8a;">{{ $trx->transaction_code }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $trx->saver?->full_name ?? '-' }}</strong>
                            <div class="mono" style="font-size: 9.5px; color: #64748b;">{{ $trx->saver?->account_number }} ({{ $trx->saver?->class_name }})</div>
                        </td>
                        <td style="text-align: center;">
                            @if($trx->transaction_type === 'deposit')
                                <span class="badge badge-deposit">Setor</span>
                            @else
                                <span class="badge badge-withdraw">Tarik</span>
                            @endif
                        </td>
                        <td class="mono" style="text-align: right; font-weight: 700; {{ $trx->transaction_type === 'deposit' ? 'color: #15803d;' : 'color: #0f172a;' }}">
                            {{ $trx->transaction_type === 'deposit' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="mono" style="text-align: right; color: #475569;">
                            Rp {{ number_format($trx->balance_after, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #94a3b8;">
                            Tidak ada transaksi tunai yang diproses pada hari ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($dailyClosing->notes)
            <div style="margin-bottom: 20px; padding: 10px 14px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px;">
                <div style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Catatan Khusus Teller:</div>
                <div style="font-size: 10.5px; color: #334155; margin-top: 2px;">{{ $dailyClosing->notes }}</div>
            </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-title">Petugas Teller Loket</div>
                <div class="sig-name">{{ $dailyClosing->teller?->name ?? '........................................' }}</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Supervisor / Pengelola Brankas</div>
                <div class="sig-name">{{ $dailyClosing->admin?->name ?? '........................................' }}</div>
            </div>
        </div>

    </div>

</body>
</html>
