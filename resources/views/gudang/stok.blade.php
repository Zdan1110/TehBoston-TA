@extends('layouts.gudang')

@section('title', 'Laporan Stok')

@section('content')

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Stok Bahan Baku Yang Ada</h5>
    </div>
    <div class="card-body">

    {{-- 🔍 Search dan Export di satu baris --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <form id="searchForm" method="GET" action="{{ route('laporan.stok') }}" class="d-flex flex-wrap align-items-center gap-2">
            <input 
                type="text" 
                name="keyword" 
                id="searchInput" 
                value="{{ request('keyword') }}" 
                class="form-control" 
                placeholder="Cari bahan..." 
                style="min-width: 220px;"
            >
        </form>

        <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
            <a href="{{ route('stok.export.pdf') }}" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
            <a href="{{ route('stok.export.excel') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
        </div>
    </div>

    {{-- === TABEL === --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Stok Awal</th>
                    <th>Barang Masuk</th>
                    <th>Barang Keluar</th>
                    <th>Stok Terkini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bahan as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $data->nama_bahan }}</td>
                    <td class="text-center">{{ $data->stok_awal }}</td>
                    <td class="text-success fw-bold text-center">+{{ $data->barang_masuk }}</td>
                    <td class="text-danger fw-bold text-center">-{{ $data->barang_keluar }}</td>
                    <td class="text-center" style="background-color: #fff9c4;">
                        {{ $data->stok_akhir ?? '-' }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('laporan.stok', ['edit' => $data->id_bahanbaku]) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('laporan.stok.tambah') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="id_bahanbaku" class="form-label">Bahan Baku</label>
                    <select name="id_bahanbaku" id="id_bahanbaku" class="form-select" required>
                        <option disabled selected>Pilih Bahan Baku</option>
                        @foreach(\DB::table('tb_bahanbaku')->get() as $bahan)
                            <option value="{{ $bahan->id_bahanbaku }}">{{ $bahan->nama_bahan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok Awal</label>
                    <input type="number" name="stok_awal" class="form-control" min="0" required>
                </div>

                <div class="alert alert-info">
                    <strong>Info:</strong> Barang masuk dan keluar akan dihitung otomatis dari transaksi bulan ini.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit (tampil otomatis via ?edit=ID) -->
@if(request('edit'))
@php
    dump(get_class($bahan)); // Harusnya: Illuminate\Support\Collection
@endphp

    @if($editData)
    
    <div class="modal show d-block" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
         style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('laporan.stok.update', $editData->id_bahanbaku) }}" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Stok</h5>
                    <a href="{{ route('laporan.stok') }}" class="btn-close"></a>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <input type="text" class="form-control" value="{{ $editData->nama_bahan }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok_awal" class="form-control" value="{{ $editData->stok_awal }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Barang Masuk</label>
                        <input type="number" name="barang_masuk" class="form-control" value="{{ $editData->barang_masuk }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Barang Keluar</label>
                        <input type="number" name="barang_keluar" class="form-control" value="{{ $editData->barang_keluar }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Akhir</label>
                        <input type="number" class="form-control"
                               value="{{ $editData->stok_akhir }}"
                               readonly>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('laporan.stok') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endif
@endsection