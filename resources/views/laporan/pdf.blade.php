<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Alat</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary { margin-top: 20px; }
        .summary table { width: 300px; float: right; }
        .summary table td { border: none; padding: 4px; }
        .footer { margin-top: 50px; text-align: right; }
        .badge { padding: 3px 8px; border-radius: 4px; color: white; font-size: 10px; }
        .bg-pending { background-color: #fbbf24; }
        .bg-dipinjam { background-color: #3b82f6; }
        .bg-dikembalikan { background-color: #10b981; }
        .bg-terlambat { background-color: #ef4444; }
        .bg-ditolak { background-color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SISTEM PEMINJAMAN ALAT</h2>
        <p>Laporan Peminjaman & Pengembalian</p>
        <p>Periode: {{ $summary['period'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Nama Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->peminjam->name }}</td>
                <td>{{ $item->alat->nama }}</td>
                <td>{{ $item->tanggal_pinjam }}</td>
                <td>{{ $item->tanggal_kembali ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ strtolower($item->status) }}">
                        {{ $item->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Total Peminjaman</td>
                <td>: <strong>{{ $summary['total_peminjaman'] }}</strong></td>
            </tr>
            <tr>
                <td>Total Dikembalikan</td>
                <td>: <strong>{{ $summary['total_selesai'] }}</strong></td>
            </tr>
            <tr>
                <td>Total Terlambat</td>
                <td>: <strong>{{ $summary['total_terlambat'] }}</strong></td>
            </tr>
        </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        <br><br><br>
        <p>( ____________________ )</p>
        <p>Petugas Inventaris</p>
    </div>
</body>
</html>
