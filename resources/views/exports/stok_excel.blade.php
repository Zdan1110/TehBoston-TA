<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align:center; font-weight:bold; font-size:14px;">
                LAPORAN STOK BAHAN BAKU
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align:center;">Dicetak pada: {{ now()->format('d M Y H:i') }}</th>
        </tr>
        <tr></tr>
        <tr style="background-color:#f5f5f5;">
            <th>No</th>
            <th>Nama Bahan</th>
            <th>Stok Awal</th>
            <th>Barang Masuk</th>
            <th>Barang Keluar</th>
            <th>Stok Akhir</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bahan as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->nama_bahan }}</td>
                <td>{{ $row->stok_awal ?? 0 }}</td>
                <td>{{ $row->barang_masuk ?? 0 }}</td>
                <td>{{ $row->barang_keluar ?? 0 }}</td>
                <td>{{ $row->stok_akhir ?? $row->stok ?? 0 }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;">Tidak ada data stok ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>
