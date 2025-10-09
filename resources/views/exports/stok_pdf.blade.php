<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Bahan Baku</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        h2 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin: 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>
<body>

    <h2>LAPORAN STOK BAHAN BAKU</h2>
    <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>

    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Stok Awal</th>
                <th>Barang Masuk</th>
                <th>Barang Keluar</th>
                <th>Stok Terkini</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bahan as $index => $data)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $data->nama_bahan }}</td>
                <td class="text-center">{{ $data->stok_awal }}</td>
                <td class="text-success fw-bold text-center" style="color: green;">+{{ $data->barang_masuk }}</td>
                <td class="text-danger fw-bold text-center" style="color: red;">-{{ $data->barang_keluar }}</td>
                <td class="text-center" style="background-color: #fff9c4;">
                    {{ $data->stok_akhir ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada data ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Sistem Distribution Center Teh Boston
    </div>

</body>
</html>
