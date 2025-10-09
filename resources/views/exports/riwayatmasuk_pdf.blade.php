<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukan DC</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #e0f7fa; }
        h3, h4 { text-align: center; margin: 0; }
    </style>
</head>
<body>
    <h3>LAPORAN PEMASUKAN DC</h3>
    <h4>Periode: {{ $from ?? '-' }} s/d {{ $to ?? '-' }}</h4>
        <table class="table table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Supplier</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatmasuk as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @php
                                $namaList = explode(',', $item->nama_bahan);
                            @endphp
                            <ol class="m-0 ps-3">
                                @foreach ($namaList as $nama)
                                    <li>{{ trim($nama) }}</li>
                                @endforeach
                            </ol>
                        </td>
                        <td>{{ $item->nama_supplier }}</td>
                        <td>
                            @php
                                $jumlahList = explode(',', $item->jumlah);
                                $satuanList = explode(',', $item->satuan);
                            @endphp
                            <ul class="m-0 ps-3" style="list-style: none;">
                                @foreach ($jumlahList as $index => $jumlah)
                                    <li>
                                    {{ $index + 1 }}) {{ trim($jumlah) }} {{ isset($satuanList[$index]) ? trim($satuanList[$index]) : '' }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada data pemasukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
</body>
</html>
