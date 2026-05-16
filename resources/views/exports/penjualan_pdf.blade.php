<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Bulan {{ $bulan ? DateTime::createFromFormat('!m', $bulan)->format('F') : 'Semua Bulan' }} {{ $tahun }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #e0f2f1; }
        h2, h4 { text-align: center; margin: 0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan DC</h2>
    <h4>
        Periode:
        {{ $bulan ? DateTime::createFromFormat('!m', $bulan)->format('F') : 'Semua Bulan' }} {{ $tahun }}
    </h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Modal</th>
                <th>Harga Jual</th>
                <th>Laba</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalModal = 0;
                $totalJual = 0;
                $totalLaba = 0;
            @endphp

            @foreach ($data as $index => $row)
                @php
                    $modal = $row->harga_modal_total;
                    $jual = $row->harga_jual_total;
                    $laba = $row->laba;
                    $totalModal += $modal;
                    $totalJual += $jual;
                    $totalLaba += $laba;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $row->nama_bahan }}</td>
                    <td>{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($modal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($jual, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($laba, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight:bold; background-color:#f1f8e9;">
                <td colspan="4">TOTAL</td>
                <td>Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalJual, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalLaba, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="text-align:right; margin-top:30px;">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </p>
</body>
</html>
