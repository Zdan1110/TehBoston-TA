@extends('layouts.gudang')
@section('title', 'Tabel Penjualan Bahan Baku DC')

@section('content')
<div class="container-fluid p-4">

    <form id="filterForm" method="GET" action="{{ route('gudang.tabelpenjualan') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Bulan</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $bulanSekarang == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-3">
            <label for="tahun" class="form-label">Tahun</label>
            <select name="tahun" id="tahun" class="form-select" onchange="this.form.submit()">
                @for ($t = now()->year; $t >= 2022; $t--)
                    <option value="{{ $t }}" {{ $tahunSekarang == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endfor
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <a href="{{ route('gudang.penjualan.export.pdf', ['bulan' => $bulanSekarang, 'tahun' => $tahunSekarang]) }}" 
               class="btn btn-danger w-50 me-2"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
            <a href="{{ route('gudang.penjualan.export.excel', ['bulan' => $bulanSekarang, 'tahun' => $tahunSekarang]) }}" 
               class="btn btn-success w-50"><i class="bi bi-file-earmark-excel"></i> Excel</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th style="white-space: nowrap;">No</th>
                    <th style="white-space: nowrap;">Nama Bahan</th>
                    <th style="white-space: nowrap;">Jumlah</th>
                    <th style="white-space: nowrap;">Harga Modal</th>
                    <th style="white-space: nowrap;">Harga Jual</th>
                    <th style="white-space: nowrap;">Laba</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalModal = 0;
                    $totalJual = 0;
                    $totalLaba = 0;
                @endphp
                @forelse($transaksiDetail as $index => $row)
                    @php
                        $totalModal += $row->harga_modal_total;
                        $totalJual += $row->harga_jual_total;
                        $totalLaba += $row->laba;
                    @endphp
                    <tr>
                        <td style="white-space: nowrap;">{{ $index + 1 }}</td>
                        <td style="white-space: nowrap;">{{ $row->nama_bahan }}</td>
                        <td style="white-space: nowrap;">{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                        <td style="white-space: nowrap;">Rp {{ number_format($row->harga_modal_total, 0, ',', '.') }}</td>
                        <td style="white-space: nowrap;">Rp {{ number_format($row->harga_jual_total, 0, ',', '.') }}</td>
                        <td style="white-space: nowrap;">Rp {{ number_format($row->laba, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted" style="white-space: nowrap;">Tidak ada data penjualan.</td></tr>
                @endforelse
            </tbody>

            @if($transaksiDetail->count() > 0)
            <tfoot class="fw-bold bg-light">
                <tr>
                    <td colspan="3" class="text-end" style="white-space: nowrap;">TOTAL :</td>
                    <td style="white-space: nowrap;">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                    <td style="white-space: nowrap;">Rp {{ number_format($totalJual, 0, ',', '.') }}</td>
                    <td style="white-space: nowrap;">Rp {{ number_format($totalLaba, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
