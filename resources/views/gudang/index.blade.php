@extends('layouts.gudang')

@section('title', 'Dashboard Gudang')

@section('content')
<div class="row">
@php
    $selectedBahan = request()->query('bahan') ?? 'serbuk';
@endphp

<div class="mb-4 d-flex flex-wrap gap-2">
    <h6 class="text-uppercase text-muted fw-bold small">Filter Jenis Bahan Barang : </h6>

    <a href="{{ route('gudang.index') }}"
       class="btn {{ $selectedBahan == 'serbuk' ? 'btn-success' : 'btn-outline-success' }}">
        Serbuk
    </a>

    <a href="{{ route('gudang.index', ['bahan' => 'Sirup']) }}"
       class="btn {{ $selectedBahan == 'Sirup' ? 'btn-warning' : 'btn-outline-warning' }}">
        Sirup
    </a>

    <a href="{{ route('gudang.index', ['bahan' => 'lain-lain']) }}"
       class="btn {{ $selectedBahan == 'lain-lain' ? 'btn-danger' : 'btn-outline-danger' }}">
        Lainnya
    </a>
</div>
    <!-- Stat Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-primary border-5 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small">Stok Terendah</h6>
                        <h8 class="text-uppercase text-muted small">{{ $stok_terendah->nama_bahan ?? '-' }}</h8>
                        <h3 class="mb-0 fw-bold">{{ $stok_terendah->stok ?? '0' }} <small class="text-muted fs-6">{{ $stok_terendah->satuan ?? '-' }}</small></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-arrow-down text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-success border-5 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small">Stok Tertinggi</h6>
                        <h8 class="text-uppercase text-muted small">{{ $stok_tertinggi->nama_bahan ?? '-' }}</h8>
                        <h3 class="mb-0 fw-bold">{{ $stok_tertinggi->stok ?? '0' }} <small class="text-muted fs-6">{{ $stok_tertinggi->satuan ?? '-' }}</small></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-arrow-up text-success fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-warning border-5 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small">Jumlah Jenis Barang</h6>
                        <h8 class="text-uppercase text-muted small">{{ $data }}</h8>
                        <h3 class="mb-0 fw-bold">{{ $totalbahan ?? '0' }}<small class="text-muted fs-6"> item</small></h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-box-seam text-warning fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-info border-5 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold small">Jumlah Supplier Barang</h6>
                        <h8 class="text-uppercase text-muted small">{{ $data }}</h8>
                        <h3 class="mb-0 fw-bold">{{ $totalsupplier ?? '0' }} <small class="text-muted fs-6">Supplier</small></h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="bi bi-person text-info fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Chart - Perbaikan di sini -->
    <form method="GET" class="">
        <div class="d-flex align-items-center gap-2">
            <label for="bulan" class="form-label m-0">Filter Bulan:</label>
            <select name="bulan" id="bulan" class="form-control" style="width: 120px;">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" id="tahun" class="form-control" style="width: 100px;">
                @for ($y = now()->year; $y >= 2022; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>


    <div class="card" style="max-width: 700px; margin: auto;">
        <div div class="w-100">
            {!! $chart->container() !!}
        </div>
    </div>


    <!-- Quick Actions - Perbaikan di sini -->
    <div class="col-lg-4 mb-4">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Aktivitas Gudang</h6>
            <span class="badge bg-info">{{ $logAktivitas->count() }}</span>
        </div>
        <div class="card-body p-3" style="max-height: 400px; overflow-y: auto;">
            <ul class="list-group list-group-flush">
                @forelse($logAktivitas as $log)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $log->aksi }}</strong><br>
                                <small class="text-muted">{{ $log->keterangan }}</small>
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($log->waktu)->diffForHumans() }}</small>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Belum ada aktivitas</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>


<!-- Recent Activity -->
<div class="card">
    <!-- [Tetap sama seperti sebelumnya] -->
</div>

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{ $chart->script() }}

@endsection
@endsection