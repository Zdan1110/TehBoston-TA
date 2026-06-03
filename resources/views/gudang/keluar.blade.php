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
            <span>{!! session('error') !!}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th style="white-space: nowrap;">No</th>
                    <th style="white-space: nowrap;">ID Transaksi</th>
                    <th style="white-space: nowrap;">Tanggal</th>
                    <th style="white-space: nowrap;">Franchise</th>
                    <th style="white-space: nowrap;">Nama Paket</th>
                    <th style="white-space: nowrap;">Bahan Baku (Jumlah Bahan Baku)</th>
                    <th style="white-space: nowrap;">Total</th>
                    <th style="white-space: nowrap;">Metode Pembayaran</th>
                    <th style="white-space: nowrap;">Status Pembayaran</th>
                    <th style="white-space: nowrap;">Status Pesanan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pesanan as $row)
                    <tr>
                        <td style="white-space: nowrap;">{{ $loop->iteration }}</td>

                        <td style="white-space: nowrap;">{{ $row['id_transaksi'] }}</td>

                        <td style="white-space: nowrap;">
                            {{ \Carbon\Carbon::parse($row['tanggal_transaksi'])->format('d-m-Y H:i') }}
                        </td>

                        <td class="text-start" style="white-space: nowrap;">
                            <strong>{{ $row['nama_franchise'] ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $row['alamat_usaha'] ?? '-' }}</small>
                        </td>

                        <td class="text-start" style="white-space: nowrap;">
                            @foreach($row['paket'] as $paket)
                                <div class="mb-2">
                                    <strong>• {{ $paket['nama_paket'] }} ( {{ $paket['jumlah_paket'] }} )</strong>
                                </div>
                            @endforeach
                        </td>

                        <td class="text-start" style="white-space: nowrap;">
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

                        <td style="white-space: nowrap;">
                            Rp {{ number_format($row['total'], 0, ',', '.') }}
                        </td>

                        <td style="white-space: nowrap;">{{ $row['metode_pembayaran'] ?? '-' }}</td>

                        <td style="white-space: nowrap;">
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

                        <td style="white-space: nowrap;">
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
                        <td colspan="11" class="text-muted text-center" style="white-space: nowrap;">
                            Tidak ada data pesanan bahan baku.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection