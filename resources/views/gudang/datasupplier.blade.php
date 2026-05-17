@extends('layouts.gudang')

@section('title', 'Data Supplier & Bahan Baku')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <ul class="nav nav-tabs card-header-tabs" id="supplierTab" role="tablist">
            @foreach($suppliers as $key => $supplier)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $key === 0 ? 'active' : '' }}"
                    id="tab-{{ $supplier->id_supplier }}"
                    data-bs-toggle="tab"
                    data-bs-target="#content-{{ $supplier->id_supplier }}"
                    type="button"
                    role="tab"
                    aria-controls="content-{{ $supplier->id_supplier }}"
                    aria-selected="{{ $key === 0 ? 'true' : 'false' }}"
                    style="color: #cdab05ff;">
                    {{ $supplier->nama_supplier }}
                </button>
            </li>
            @endforeach
        </ul>

        <!-- Tombol Tambah Supplier -->
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahSupplierModal">
            + Tambah Supplier
        </button>
    </div>

    <div class="card-body">
        <div class="tab-content" id="supplierTabContent">
            @foreach($suppliers as $key => $supplier)
            <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}"
                id="content-{{ $supplier->id_supplier }}"
                role="tabpanel"
                aria-labelledby="tab-{{ $supplier->id_supplier }}">
                <h6 class="mb-3 text-primary">Daftar Bahan Baku dari {{ $supplier->nama_supplier }}</h6>
                <div class="mb-3">
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#tambahBahanModal-{{ $supplier->id_supplier }}">
                        + Tambah Bahan Baku
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="white-space: nowrap;">Nama Bahan</th>
                                <th style="white-space: nowrap;">Jenis Bahan</th>
                                <th style="white-space: nowrap;">Harga Modal</th>
                                <th style="white-space: nowrap;">Harga Jual</th>
                                <th style="white-space: nowrap;">Stok</th>
                                <th style="white-space: nowrap;">Satuan</th>
                                <th style="white-space: nowrap;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($bahanbaku->where('id_supplier', $supplier->id_supplier) as $b)
                            <tr>
                                <td style="white-space: nowrap;">{{ $no++ }}</td>
                                <td style="white-space: nowrap;">{{ $b->nama_bahan }}</td>
                                <td style="white-space: nowrap;">{{ $b->jenis_bahan }}</td>
                                <td style="white-space: nowrap;">Rp {{ number_format($b->harga_modal, 0, ',', '.') }}</td>
                                <td style="white-space: nowrap;">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                                <td style="white-space: nowrap;">{{ $b->stok }}</td>
                                <td style="white-space: nowrap;">{{ $b->satuan }}</td>
                                {{-- <td>{{ $b->keterangan ?? '-' }}</td> --}}
                                <td style="white-space: nowrap;" class="d-flex gap-2">
                                    <a href="/gudang/bahan/edit/{{ $b->id_bahanbaku }}/{{ $b->id_supplier }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('bahanbaku.delete', $b->id_bahanbaku) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bahan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted" style="white-space: nowrap;">Tidak ada bahan baku untuk supplier ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Tambah Bahan Baku -->
            <div class="modal fade" id="tambahBahanModal-{{ $supplier->id_supplier }}" tabindex="-1" aria-labelledby="tambahBahanLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('bahanbaku.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahBahanLabel">Tambah Bahan Baku - {{ $supplier->nama_supplier }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_supplier" value="{{ $supplier->id_supplier }}">

                                <div class="mb-2">
                                    <label for="nama_bahan" class="form-label">Nama Bahan</label>
                                    <input type="text" name="nama_bahan" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label for="jenis_bahan" class="form-label">Jenis Bahan</label>
                                    <select name="jenis_bahan" class="form-select" required>
                                        <option value="serbuk">Serbuk</option>
                                        <option value="sirup">Sirup</option>
                                        <option value="lain-lain">Lain-lain</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="harga_modal" class="form-label">Harga Modal</label>
                                    <input type="number" name="harga_modal" class="form-control" min="0" value="0" required>
                                </div>
                                <div class="mb-2">
                                    <label for="harga_jual" class="form-label">Harga Jual</label>
                                    <input type="number" name="harga_jual" class="form-control" min="0" value="0" required>
                                </div>
                                <div class="mb-2">
                                    <label for="stok" class="form-label">Stok Awal</label>
                                    <input type="number" name="stok" class="form-control" min="0" value="0" required>
                                </div>
                                <div class="mb-2">
                                    <label for="satuan" class="form-label">Satuan</label>
                                    <input type="text" name="satuan" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</div>

<!-- Modal Tambah Supplier -->
<div class="modal fade" id="tambahSupplierModal" tabindex="-1" aria-labelledby="tambahSupplierLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('supplier.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSupplierLabel">Tambah Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="nama_supplier" class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection