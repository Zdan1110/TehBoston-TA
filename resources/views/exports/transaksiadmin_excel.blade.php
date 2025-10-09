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
        @foreach($transaksi as $t)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $t->id_transaksi }}</td>
            <td>{{ $t->transaksi }}</td>
            <td>{{ ucfirst($t->jenis) }}</td>
            <td>{{ number_format($t->jumlah, 0, ',', '.') }}</td>
            <td>{{ $t->keterangan ?? '-' }}</td>
            <td>{{ $t->created_at ? date('d-m-Y H:i', strtotime($t->created_at)) : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
