<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran DC</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #fff3e0; }
        h3, h4 { text-align: center; margin: 0; }
    </style>
</head>
<body>
    <h3>LAPORAN PENGELUARAN DC</h3>
    <h4>Periode: {{ $from ?? '-' }} s/d {{ $to ?? '-' }}</h4>
    <table class="table table-bordered">
        <thead class="table-light text-center">
            <tr>
                <th>No</th>
                <th>Nama Bahan</th>
                <th>Jumlah</th>
                <th>Tujuan Pengeluaran</th>
                <th>Total Harga</th>
                <th>Terakhir Diperbarui</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayatkeluar as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @php
                            $namaList = explode(',', $data->nama_bahan);
                        @endphp
                        <ol class="m-0 ps-3">
                            @foreach ($namaList as $nama)
                                <li>{{ trim($nama) }}</li>
                            @endforeach
                        </ol>
                    </td>
                    <td>
                        @php
                            $jumlahList = explode(',', $data->jumlah);
                            $satuanList = explode(',', $data->satuan ?? '');
                        @endphp
                        <div class="m-0 ps-3">
                            @foreach ($jumlahList as $i => $jumlah)
                                <div>
                                    {{ $i + 1 }}) {{ trim($jumlah) }} {{ $satuanList[$i] ?? '' }}
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        @php
                            $namaFranchise = explode(',', $data->nama_franchise);
                            $alamatUsaha = explode(',', $data->alamat_usaha);
                        @endphp
                        {{ trim($namaFranchise[0]) }} - {{ trim($alamatUsaha[0]) }}
                    </td>
                    <td>
                        Rp. {{ is_numeric($data->total) ? number_format($data->total, 0, ',', '.') : $data->total }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($data->updated_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data pengeluaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
