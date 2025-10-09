<table>
    <thead>
        <tr>
            <th>Nama Bahan</th>
            <th>Supplier</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total Transaksi</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($riwayatmasuk as $row)
            <tr>
                <td>
                    @php
                        $namaList = explode(',', $row->nama_bahan);
                    @endphp
                    <ul class="m-0 ps-3">
                        @foreach ($namaList as $nama)
                            <li>{{ trim($nama) }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $row->nama_supplier }}</td>
                <td>
                    @php
                        $jumlahList = explode(',', $row->jumlah);
                        $satuanList = explode(',', $row->satuan);
                    @endphp
                    <ul class="m-0 ps-3" style="list-style: none;">
                        @foreach ($jumlahList as $index => $jumlah)
                            <li>
                            {{ $index + 1 }}) {{ trim($jumlah) }} {{ isset($satuanList[$index]) ? trim($satuanList[$index]) : '' }}
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ number_format((float)$row->total, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
