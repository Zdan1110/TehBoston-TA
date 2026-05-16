@extends('layouts.gudang')
@section('title', 'Tabel Pesanan Bahan Baku')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th>No</th>
                    <th>ID Transaksi</th>
                    <th>Tanggal</th>
                    <th>Franchise</th>
                    <th>Nama Paket</th>
                    <th>Bahan Baku (Jumlah Bahan Baku)</th>
                    <th>Total</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pembayaran</th>
                    <th>Status Pesanan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pesanan as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $row['id_transaksi'] }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($row['tanggal_transaksi'])->format('d-m-Y H:i') }}
                        </td>

                        <td class="text-start">
                            <strong>{{ $row['nama_franchise'] ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $row['alamat_usaha'] ?? '-' }}</small>
                        </td>

                        <td class="text-start">
                            @foreach($row['paket'] as $paket)
                                <div class="mb-2">
                                    <strong>• {{ $paket['nama_paket'] }} ( {{ $paket['jumlah_paket'] }} )</strong>
                                </div>
                            @endforeach
                        </td>

                        <td class="text-start">
                            @foreach($row['paket'] as $paket)
                                <div class="mb-3">
                                    <strong>{{ $paket['nama_paket'] }}</strong>
                                    @foreach($paket['bahan'] as $bahan)
                                        <div>
                                            • {{ $bahan['nama_bahan'] }}
                                            ({{ $bahan['jumlah_bahan'] }} {{ $bahan['satuan'] }})
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </td>

                        <td>
                            Rp {{ number_format($row['total'], 0, ',', '.') }}
                        </td>

                        <td>{{ $row['metode_pembayaran'] ?? '-' }}</td>

                        <td>
                            @if($row['metode_pembayaran'] == 'Tunai' && $row['status_pembayaran'] != 'settlement')
                                <span class="badge bg-warning text-dark">Pending</span>
                                <form action="{{ route('pesanan.lunasi', $row['id_transaksi']) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-lunas text-black rounded">
                                        Lunas
                                    </button>
                                </form>

                            @elseif($row['status_pembayaran'] == 'settlement')
                                <span class="badge bg-success">Lunas</span>

                            @elseif($row['status_pembayaran'] == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>

                            @else
                                <span class="badge bg-secondary">{{ $row['status_pembayaran'] ?? '-' }}</span>
                            @endif
                        </td>

                        <td>
                            @if($row['status_transaksi'] == 'Sedang Di Proses')
                                
                                <span class="badge bg-info text-dark">Sedang Di Proses</span>

                                <form action="{{ route('pesanan.kirim', $row['id_transaksi']) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-primary rounded" style="font-size: 10px; border: 1px solid #252525;">
                                        Kirim & Cetak Nota
                                    </button>
                                </form>

                            @elseif($row['status_transaksi'] == 'Dikirim')
                                
                                <span class="badge bg-primary">Dikirim</span>

                                <form action="{{ route('pesanan.selesai', $row['id_transaksi']) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-success">
                                        Selesai
                                    </button>
                                </form>

                            @elseif($row['status_transaksi'] == 'Selesai')

                                <span class="badge bg-success">Selesai</span>

                            @else

                                <span class="badge bg-secondary">
                                    {{ $row['status_transaksi'] ?? 'Belum Diproses' }}
                                </span>

                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-muted text-center">
                            Tidak ada data pesanan bahan baku.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection