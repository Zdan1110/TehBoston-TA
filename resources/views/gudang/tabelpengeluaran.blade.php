@extends('layouts.gudang')
@section('title', 'Tabel Pengeluaran DC')

@section('content')
<div class="container-fluid p-4">
    <h4 class="fw-bold mb-3 text-success">Tabel Pengeluaran DC</h4>

    <!-- 🔄 Filter Otomatis Bulan & Tahun -->
    <form id="filterForm" method="GET" action="{{ route('gudang.tabelpengeluaran') }}" class="row g-3 mb-4">
        <div class="col-md-5">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select" onchange="document.getElementById('filterForm').submit()">
                <option value="">Semua Bulan</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulanSekarang == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-4">
            <label for="tahun" class="form-label">Tahun</label>
            <select name="tahun" id="tahun" class="form-select" onchange="document.getElementById('filterForm').submit()">
                @for ($t = now()->year; $t >= 2022; $t--)
                    <option value="{{ $t }}" {{ $tahunSekarang == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <a href="{{ route('gudang.pengeluaran.export.pdf', ['bulan' => $bulanSekarang, 'tahun' => $tahunSekarang]) }}" 
               class="btn btn-danger w-100 me-2"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
            <a href="{{ route('gudang.pengeluaran.export.excel', ['bulan' => $bulanSekarang, 'tahun' => $tahunSekarang]) }}" 
               class="btn btn-success w-100"><i class="bi bi-file-earmark-excel"></i> Excel</a>
        </div>
    </form>

    <!-- 📊 Tabel Pengeluaran -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-warning">
                <tr>
                    <th>No</th>
                    <th>Tanggal Transaksi</th>
                    <th>ID Pemasukan</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah</th>
                    <th>Total Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPengeluaran = 0; @endphp
                @forelse($pengeluaran as $index => $row)
                    @php $totalPengeluaran += $row->total_pengeluaran; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y') }}</td>
                        <td>{{ $row->id_pemasukan }}</td>
                        <td>{{ $row->nama_bahan }}</td>
                        <td>{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->total_pengeluaran, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">Tidak ada data pengeluaran.</td>
                    </tr>
                @endforelse
            </tbody>
            @if($pengeluaran->count() > 0)
            <tfoot class="fw-bold bg-light">
                <tr>
                    <td colspan="5" class="text-end">TOTAL PENGELUARAN</td>
                    <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
