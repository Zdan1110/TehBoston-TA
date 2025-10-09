<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>ID Pemasukan</th>
            <th>Total Pengeluaran</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengeluaran as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                <td>{{ $row->id_pemasukan }}</td>
                <td>Rp {{ number_format($row->total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3"><strong>Total Semua Pengeluaran</strong></td>
            <td><strong>Rp {{ number_format($pengeluaran->sum('total_pengeluaran'), 0, ',', '.') }}</strong></td>
        </tr>
    </tbody>
</table>
