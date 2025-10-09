<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Admin</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #2E7D32;
            margin-bottom: 10px;
        }
        p {
            text-align: center;
            margin-top: 0;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #2E7D32;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h2>Laporan Transaksi Admin</h2>
    <p>
        Bulan: {{ $bulan ?? 'Semua' }} |
        Tahun: {{ $tahun ?? 'Semua' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Deskripsi</th>
                <th>Jenis</th>
                <th>Jumlah (Rp)</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($data as $t)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $t->id_transaksi }}</td>
                    <td>{{ $t->transaksi }}</td>
                    <td>{{ ucfirst($t->jenis) }}</td>
                    <td class="text-right">{{ number_format($t->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $t->keterangan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="text-align:right; margin-top:30px;">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>
