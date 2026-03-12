<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Resi Peminjaman - {{ $peminjaman->kode }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        .container {
            padding: 40px;
        }

        .header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            vertical-align: top;
            text-align: right;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0;
        }

        .trx-code {
            font-family: monospace;
            font-size: 16px;
            color: #3b82f6;
            font-weight: bold;
            margin-top: 8px;
        }

        .qr-placeholder {
            padding: 10px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: inline-block;
        }

        .item-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: table;
            width: 100%;
        }

        .item-image {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }

        .item-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #e2e8f0;
        }

        .item-details {
            display: table-cell;
            padding-left: 20px;
            vertical-align: middle;
        }

        .item-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .item-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }

        .details-grid {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }

        .details-row {
            display: table-row;
        }

        .details-col {
            display: table-cell;
            width: 50%;
            padding: 10px 0;
        }

        .label {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .value {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-dipinjam {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-terlambat {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-dikembalikan {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .signature-section {
            margin-top: 60px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }

        .signature-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 60px;
        }

        .signature-name {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            border-top: 1px solid #e2e8f0;
            display: inline-block;
            padding-top: 8px;
            min-width: 160px;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }

        .footer-text {
            margin-bottom: 4px;
        }

        .footer-time {
            font-family: monospace;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1 class="title">Resi Peminjaman</h1>
                <p class="subtitle">Sistem Informasi Peminjaman Sarana & Prasarana</p>
                <div class="trx-code">{{ $peminjaman->kode }}</div>
            </div>
            <div class="header-right">
                <div class="qr-placeholder">
                    @if(isset($qrcode))
                        <img src="data:image/svg+xml;base64, {!! base64_encode($qrcode) !!} " style="width: 80px; height: 80px;">
                    @else
                        <div style="width: 80px; height: 80px; background: #f1f5f9; display: table-cell; vertical-align: middle; text-align: center; font-size: 10px; color: #94a3b8;">QR CODE</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="item-card">
            <div class="item-image">
                @if($peminjaman->alat->foto)
                    <img src="{{ public_path('storage/alat/' . $peminjaman->alat->foto) }}" alt="Alat">
                @else
                    <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 8px;"></div>
                @endif
            </div>
            <div class="item-details">
                <div class="item-label">Alat yang Dipinjam</div>
                <h2 class="item-name">{{ $peminjaman->alat->nama }}</h2>
            </div>
        </div>

        <div class="details-grid">
            <div class="details-row">
                <div class="details-col">
                    <div class="label">Peminjam</div>
                    <div class="value">{{ $peminjaman->peminjam->name }}</div>
                </div>
                <div class="details-col">
                    <div class="label">Email</div>
                    <div class="value">{{ $peminjaman->peminjam->email }}</div>
                </div>
            </div>
            <div class="details-row">
                <div class="details-col">
                    <div class="label">Tanggal Pinjam</div>
                    <div class="value">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y H:i') }}</div>
                </div>
                <div class="details-col">
                    <div class="label">Batas Kembali</div>
                    <div class="value">
                        {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') : '-' }}
                    </div>
                </div>
            </div>
            <div class="details-row">
                <div class="details-col">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="badge badge-{{ strtolower($peminjaman->status) }}">{{ $peminjaman->status }}</span>
                    </div>
                </div>
                <div class="details-col">
                    <div class="label">Kategori</div>
                    <div class="value">{{ $peminjaman->alat->kategoriAlat->nama_kategori_alat ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Peminjam</div>
                <div class="signature-name">{{ $peminjaman->peminjam->name }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Petugas Inventaris</div>
                <div class="signature-name">{{ $peminjaman->petugas->name ?? 'Admin' }}</div>
            </div>
        </div>

        <div class="footer">
            <p class="footer-text">Harap jaga alat dengan baik dan kembalikan tepat waktu sesuai batas yang ditentukan.</p>
            <p class="footer-time">Dicetak otomatis oleh SIPINJAM pada <span class="footer-time">{{ date('d/m/Y H:i') }}</span></p>
        </div>
    </div>
</body>

</html>
