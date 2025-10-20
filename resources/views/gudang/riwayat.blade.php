@extends('layouts.gudang')

@section('title', 'Riwayat Pemasukan dan Pengeluaran Barang')

<style>
@media (max-width: 768px) {
    /* Bungkus tombol export agar bisa digeser kalau sempit */
    .d-flex.flex-wrap.align-items-center.gap-2.mt-2.mt-md-0 {
        flex-wrap: nowrap !important;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 5px;
    }

    /* Teks total uang keluar biar gak makan tempat */
    .d-flex.flex-wrap.align-items-center.gap-2.mt-2.mt-md-0 .text-end {
        flex: 0 0 auto;
        font-size: 0.85rem;
        margin-right: 10px !important;
    }

    /* Tombol PDF & Excel lebih kecil dan sejajar */
    .d-flex.flex-wrap.align-items-center.gap-2.mt-2.mt-md-0 .btn {
        flex: 0 0 auto;
        font-size: 0.8rem;
        padding: 4px 10px;
        white-space: nowrap;
    }
}

.modal-blur {
    opacity: 0.4;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
</style>


@section('content')
@php
    $user = Session::get('user');
    $isKasir = isset($user['type_akun']) && strtolower($user['type_akun']) === 'kasirdc';
@endphp

<div class="card mb-4">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button" role="tab" aria-controls="masuk" aria-selected="true" style="color: #cdab05ff;">Riwayat Pemasukan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar" type="button" role="tab" aria-controls="keluar" aria-selected="false" style="color: #cdab05ff;">Riwayat Pengeluaran</button>
            </li>
        </ul>
    </div>

    <!-- Tombol untuk mobile -->
    <div class="d-md-none p-3">
        <button type="button" class="btn btn-outline-primary w-100" onclick="toggleFilterTanggal()">Filter Tanggal</button>
    </div>

    <!-- Filter tanggal -->
    <div id="filterTanggalWrapper" class="mb-3 d-none d-md-block px-3">
        <form method="GET" action="{{ route('riwayat.gudang') }}{{ request()->has('tab') ? '#' . request('tab') : '#masuk' }}">
            <div class="row g-2 align-items-center">
                <input type="hidden" name="tab" id="tabInput" value="{{ request('tab', 'masuk') }}">
                <div class="col-12 col-md-auto">
                    <label for="from" class="form-label mb-0">Dari Tanggal</label>
                    <input type="date" id="from" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-12 col-md-auto">
                    <label for="to" class="form-label mb-0">Sampai</label>
                    <input type="date" id="to" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-12 col-md-auto d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('riwayat.gudang') }}{{ request()->has('tab') ? '?tab=' . request('tab') . '#' . request('tab') : '' }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="tab-content" id="historyTabContent">

            {{-- ============================ TAB PEMASUKAN ============================ --}}
            <div class="tab-pane fade show active" id="masuk" role="tabpanel" aria-labelledby="masuk-tab">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <form method="GET" action="{{ route('riwayat.gudang') }}" class="d-flex flex-wrap gap-2">
                        <input type="hidden" name="tab" value="masuk">
                        <input type="text" name="keyword_masuk" placeholder="Cari bahan atau supplier..." value="{{ request('keyword_masuk') }}" class="form-control" style="width: 250px;">
                        <button type="submit" class="btn btn-warning">Cari Masuk</button>
                    </form>

                    @if(!$isKasir)
                   <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
    <div class="text-end me-3">
        <strong>Total Uang Keluar:</strong><br>
        <span class="text-success">Rp. {{ number_format($totalUangKeluar, 0, ',', '.') }}</span>
    </div>

    {{-- Tombol Export --}}
    <a href="{{ route('riwayatmasuk.export.pdf', request()->query()) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf"></i> PDF
    </a>
    <a href="{{ route('riwayatmasuk.export.excel', request()->query()) }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel"></i> Excel
    </a>
</div>

                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan</th>
                                <th>Supplier</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Tanggal Input</th>
                                @unless($isKasir)
                                    <th>Struk</th>
                                @endunless
                                <!-- <th>Status</th> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatmasuk as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php $namaList = explode(',', $item->nama_bahan); @endphp
                                        <ol class="m-0 ps-3">
                                            @foreach ($namaList as $nama)
                                                <li>{{ trim($nama) }}</li>
                                            @endforeach
                                        </ol>
                                    </td>
                                    <td>{{ $item->nama_supplier }}</td>
                                    <td>
                                        @php
                                            $jumlahList = explode(',', $item->jumlah);
                                            $satuanList = explode(',', $item->satuan);
                                        @endphp
                                        <ul class="m-0 ps-3" style="list-style: none;">
                                            @foreach ($jumlahList as $index => $jumlah)
                                                <li>{{ $index + 1 }}) {{ trim($jumlah) }} {{ $satuanList[$index] ?? '' }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                    @unless($isKasir)
                                    <td>
                                        @if(!empty($item->struk))
                                            <a href="#" class="text-decoration-none text-primary"
                                               data-bs-toggle="modal"
                                               data-bs-target="#strukModal"
                                               data-struk="{{ asset('uploads/strukdc/' . $item->struk) }}">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i> Lihat Struk
                                            </a>
                                        @else
                                            <i class="text-muted fst-italic">tidak ada struk</i>
                                        @endif
                                    </td>
                                
                                    <!-- <td class="text-center">
                                    <span class="badge bg-warning text-dark me-1">Pending</span>
                                    {{-- <span class="badge bg-success">Completed</span> --}}
                                </td> -->
                                 
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2 action-buttons">
                                        <!-- Tombol Complete -->
                                        <!-- <button class="btn btn-sm btn-outline-success p-1 btn-complete" title="Selesaikan">
                                            <i class="bi bi-check-circle-fill fs-5"></i>
                                        </button> -->

                                        <!-- Tombol Edit -->
                                        <button 
                                            class="btn btn-sm btn-outline-warning p-1 btn-edit-masuk" 
                                            title="Edit"
                                            data-id="{{ $item->id_transaksi }}">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>

                                        <!-- Tombol Delete -->
                                        <button class="btn btn-sm btn-outline-danger p-1 btn-delete-transaksimasuk" title="Hapus" data-id="{{ $item->id_transaksi }}">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>
                                    </div>
                                </td>



                             @endunless
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isKasir ? '6' : '7' }}" class="text-center text-muted">Tidak ada data pemasukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ============================ TAB PENGELUARAN ============================ --}}
            <div class="tab-pane fade" id="keluar" role="tabpanel" aria-labelledby="keluar-tab">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <form method="GET" action="{{ route('riwayat.gudang') }}#keluar" class="d-flex flex-wrap gap-2">
                        <input type="hidden" name="tab" value="keluar">
                        <input type="text" name="keyword_keluar" placeholder="Cari bahan atau franchise..." value="{{ request('keyword_keluar') }}" class="form-control" style="width: 250px;">
                        <button type="submit" class="btn btn-warning">Cari Keluar</button>
                    </form>

                    @if(!$isKasir)
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
                        <div class="text-end me-3">
                            <strong>Total Uang Masuk:</strong><br>
                            <span class="text-primary">Rp. {{ number_format($totalUangMasuk, 0, ',', '.') }}</span>
                        </div>

                        {{-- Tombol Export --}}
                        <a href="{{ route('riwayatkeluar.export.pdf', request()->query()) }}" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                        <a href="{{ route('riwayatkeluar.export.excel', request()->query()) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                    </div>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan</th>
                                <th>Jumlah</th>
                                <th>Tujuan Pengeluaran</th>
                                <th>Total Harga</th>
                                <th>Terakhir Diperbarui</th>
                                @unless($isKasir)
                                    <th>Struk</th>
                                    <th>Action</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatkeluar as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php $namaList = explode(',', $data->nama_bahan); @endphp
                                        <ol class="m-0 ps-3">
                                            @foreach ($namaList as $nama)
                                                <li>{{ trim($nama) }}</li>
                                            @endforeach
                                        </ol>
                                    </td>
                                    <td>
                                        @php
                                            $jumlahList = explode(',', $data->jumlah);
                                            $satuanList = explode(',', $data->satuan ?? '');
                                        @endphp
                                        <div class="m-0 ps-3">
                                            @foreach ($jumlahList as $i => $jumlah)
                                                <div>{{ $i + 1 }}) {{ trim($jumlah) }} {{ $satuanList[$i] ?? '' }}</div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $namaFranchise = explode(',', $data->nama_franchise);
                                            $alamatUsaha = explode(',', $data->alamat_usaha);
                                        @endphp
                                        {{ trim($namaFranchise[0]) }} - {{ trim($alamatUsaha[0]) }}
                                    </td>
                                    <td>Rp. {{ is_numeric($data->total) ? number_format($data->total, 0, ',', '.') : $data->total }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->updated_at)->format('d/m/Y H:i') }}</td>
                                    @unless($isKasir)
                                    <td>
                                        @if(!empty($data->struk))
                                            <a href="#" class="text-decoration-none text-primary"
                                               data-bs-toggle="modal"
                                               data-bs-target="#strukModal"
                                               data-struk="{{ asset('uploads/strukdc/' . $data->struk) }}">
                                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i> Lihat Struk
                                            </a>
                                        @else
                                            <i class="text-muted fst-italic">tidak ada struk</i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2 action-buttons">
                                            <!-- Tombol Edit -->
                                            <button 
                                                class="btn btn-sm btn-outline-warning p-1 btn-edit-keluar" 
                                                title="Edit"
                                                data-id="{{ $data->id_transaksi }}">
                                                <i class="bi bi-pencil-square fs-5"></i>
                                            </button>

                                            <!-- Tombol Delete -->
                                            <button class="btn btn-sm btn-outline-danger p-1 btn-delete-transaksi" title="Hapus" data-id="{{ $data->id_transaksi }}">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>
                                        </div>
                                    </td>
                                    @endunless
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isKasir ? '6' : '7' }}" class="text-center text-muted">Tidak ada data pengeluaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@if(!$isKasir)
<!-- Modal untuk menampilkan PDF Struk -->
<div class="modal fade" id="strukModal" tabindex="-1" aria-labelledby="strukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="strukModalLabel">
                    <i class="bi bi-file-earmark-pdf"></i> Lihat Struk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="strukFrame" src="" frameborder="0" style="width:100%; height:100%;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Pemasukan -->
<div class="modal fade" id="editMasukModal" tabindex="-1" aria-labelledby="editMasukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editMasukModalLabel">
                    <i class="bi bi-pencil-square"></i> Edit Data Pemasukan

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="editMasukForm">
                    
                    <input type="hidden" id="id_transaksi" name="id_transaksi" value="">
                    <!-- 🟡 Di sinilah tempat untuk menampilkan daftar bahan dinamis -->
                    <div id="list-bahan-container-masuk"></div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnTambahBahanMasuk">
                    <i class="bi bi-plus-circle"></i> Tambah Bahan Masuk
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpanEditMasukDummy">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Pengeluaran -->
<div class="modal fade" id="editKeluarModal" tabindex="-1" aria-labelledby="editKeluarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editKeluarModalLabel">
                    <i class="bi bi-pencil-square"></i> Edit Data Pengeluaran

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="editKeluarForm">
                    
                    <input type="hidden" id="id_transaksi" name="id_transaksi" value="">
                    <!-- 🟡 Di sinilah tempat untuk menampilkan daftar bahan dinamis -->
                    <div id="list-bahan-container"></div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnTambahBahanKeluar">
                    <i class="bi bi-plus-circle"></i> Tambah Bahan Keluar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpanEditKeluarDummy">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahBahanKeluarModal" tabindex="-1" aria-labelledby="tambahBahanKeluarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahBahanKeluarModalLabel">
                    <i class="bi bi-plus-circle"></i> Tambah Bahan Keluar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahBahanKeluar">
                    @csrf
                    <div class="mb-3">
                        <label for="bahan_baku" class="form-label">Pilih Bahan Baku</label>
                        <select class="form-select" id="bahan_baku" name="bahan_baku" required>
                            <option value="" disabled selected hidden>-- Pilih Bahan Baku --</option>
                            @foreach($bahanbaku as $bahan)
                                <option value="{{ $bahan->id_bahanbaku }}" data-harga="{{ $bahan->harga_jual }}">
                                    {{ $bahan->nama_bahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input type="text" class="form-control" style="background-color:rgb(212, 212, 212);" id="total_harga" name="total_harga" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpanTambahBahanKeluar">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahBahanMasukModal" tabindex="-1" aria-labelledby="tambahBahanMasukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahBahanMasukModalLabel">
                    <i class="bi bi-plus-circle"></i> Tambah Bahan Masuk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahBahanMasuk">
                    @csrf
                    <div class="mb-3">
                        <label for="bahan_bakumasuk" class="form-label">Pilih Bahan Baku</label>
                        <select class="form-select" id="bahan_bakumasuk" name="bahan_bakumasuk" required>
                            <option value="" disabled selected hidden>-- Pilih Bahan Baku --</option>
                            @foreach($bahanbaku as $bahan)
                                <option value="{{ $bahan->id_bahanbaku }}" data-harga="{{ $bahan->harga_jual }}">
                                    {{ $bahan->nama_bahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jumlahmasuk" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlahmasuk" name="jumlahmasuk" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <input type="text" class="form-control" style="background-color:rgb(212, 212, 212);" id="total_harga_masuk" name="total_harga_masuk" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpanTambahBahanMasuk">Simpan</button>
            </div>
        </div>
    </div>
</div>


@endif
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // === TOMBOL COMPLETE ===
    document.addEventListener('click', function(e) {
        const completeBtn = e.target.closest('.btn-complete');
        if (!completeBtn) return;

        const td = completeBtn.closest('td');
        const row = completeBtn.closest('tr');
        const statusTd = row.querySelector('td:nth-last-child(2)'); // kolom status
        const actionDiv = td.querySelector('.action-buttons');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Barang sudah datang dan sesuai dengan data?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, sesuai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ubah label status
                statusTd.innerHTML = '<span class="badge bg-success">Completed</span>';

                // Ubah tombol hanya jadi hapus
                actionDiv.innerHTML = `
                    <button class="btn btn-sm btn-outline-danger p-1 btn-delete" title="Hapus">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                `;

                // Notifikasi sukses
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Status berhasil diubah menjadi Completed.',
                    icon: 'success',
                    confirmButtonColor: '#198754',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });



// === SIMPAN EDIT (Dummy) ===
document.getElementById('btnSimpanEditDummy').addEventListener('click', function() {
    Swal.fire({
        title: 'Berhasil!',
        text: 'Perubahan data berhasil disimpan (dummy).',
        icon: 'success',
        confirmButtonColor: '#198754',
        timer: 2000,
        showConfirmButton: false
    });
    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
    modal.hide();
});


    // === TOMBOL HAPUS ===
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.btn-delete');
        if (!deleteBtn) return;

        const row = deleteBtn.closest('tr');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data ini akan dihapus secara permanens!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                row.remove();

                Swal.fire({
                    title: 'Dihapus!',
                    text: 'Data berhasil dihapus.',
                    icon: 'success',
                    confirmButtonColor: '#198754',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

});
</script>


<script>
function toggleFilterTanggal() {
    document.getElementById('filterTanggalWrapper').classList.toggle('d-none');
}

document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        const tabTrigger = document.querySelector(`button[data-bs-target="#${hash}"]`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
            document.getElementById('tabInput').value = hash;
        }
    }

    document.querySelectorAll('#historyTab button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function (event) {
            const target = event.target.getAttribute('data-bs-target').replace('#', '');
            history.replaceState(null, null, '#' + target);
            document.getElementById('tabInput').value = target;
        });
    });

    if (window.innerWidth < 768 && (document.getElementById('from').value || document.getElementById('to').value)) {
        document.getElementById('filterTanggalWrapper').classList.remove('d-none');
    }

    @unless($isKasir)
    const strukModal = document.getElementById('strukModal');
    const iframe = document.getElementById('strukFrame');
    strukModal.addEventListener('show.bs.modal', function (event) {
        iframe.src = event.relatedTarget.getAttribute('data-struk');
    });
    strukModal.addEventListener('hidden.bs.modal', function () {
        iframe.src = '';
    });
    @endunless
});

// === SIMPAN EDIT PENGELUARAN (Dummy) ===
$('#btnSimpanEditKeluarDummy').off('click').on('click', function() {
    // Ambil semua bahan dari modal
    let dataUpdate = [];

    $('#list-bahan-container .border').each(function() {
        const id_pengeluaran = $(this).find('.btn-hapus-bahan').data('id');
        const jumlah = $(this).find('input[name="jumlah[]"]').val();
        const total = $(this).find('input[name="harga[]"]').val();
        const idTransaksi = $('#id_transaksi').val();

        dataUpdate.push({
            id_pengeluaran: id_pengeluaran,
            jumlah: jumlah,
            total: total,
            id_transaksi: idTransaksi
        });
    });

    // Kirim via AJAX ke controller Laravel
    $.ajax({
        url: '/gudang/riwayat/updatekeluar',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            data: dataUpdate
        },
        success: function(response) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Semua data pengeluaran berhasil diperbarui.',
                icon: 'success',
                confirmButtonColor: '#198754',
                timer: 2000,
                showConfirmButton: false
            });
            $('#editKeluarModal').modal('hide');
            setTimeout(function() {
                location.reload();
            }, 1500);
        },
        error: function(xhr) {
            Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
        }
    });
});

$(document).on('click', '.btn-edit-keluar', function() {
    const idTransaksi = $(this).data('id');

    $.ajax({
        url: `/gudang/riwayat/editkeluar/${idTransaksi}`,
        type: 'GET',
        success: function(response) {
            if (response && response.length > 0) {
                // Kosongkan kontainer sebelumnya
                $('#list-bahan-container').empty();

                $('#id_transaksi').val(idTransaksi);
                // Loop setiap data bahan
                response.forEach((item, index) => {
                    const html = `
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-bold text-warning mb-3">Bahan ke-${index + 1}</h6>
                            <div class="mb-3">
                                <label class="form-label">Nama Bahan</label>
                                <input type="text" class="form-control" style="background-color:rgb(212, 212, 212);" name="nama_bahan[]" value="${item.nama_bahan}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" class="form-control input-jual" name="jumlah[]" value="${item.jumlah}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Harga</label>
                                <input type="number" class="form-control input-harga" style="background-color:rgb(212, 212, 212);" name="harga[]" value="${item.harga_jual * item.jumlah}" data-harga-awal="${item.harga_jual}" readonly>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus-bahan" data-id="${item.id_pengeluaran}">
                                <i class="bi bi-trash"></i> Hapus Transaksi Bahan baku ke-${index + 1}
                            </button>
                        </div>
                    `;
                    $('#list-bahan-container').append(html);
                });

                // Tampilkan modal
                $('#editKeluarModal').modal('show');
            } else {
                Swal.fire('Oops!', 'Data tidak ditemukan', 'warning');
            }
        },
        error: function() {
            Swal.fire('Gagal!', 'Gagal memuat data pengeluaran.', 'error');
        }
    });
});

$(document).on('input', '.input-jual', function() {
    const parent = $(this).closest('.border');
    const hargaAwal = parseFloat(parent.find('.input-harga').data('harga-awal')) || 0;
    const tambahan = parseFloat($(this).val()) || 0;

    parent.find('.input-harga').val(hargaAwal * tambahan);
});

$(document).on('click', '.btn-hapus-bahan', function() {
    const idPengeluaran = $(this).data('id'); // ambil id_pengeluaran dari tombol
    const parentDiv = $(this).closest('.border'); // ambil elemen card bahan

    if (confirm('Yakin ingin menghapus data ini? ( Proses hapus data ini akan langsung dijalankan dan tidak bisa di kembalikan lagi! )')) {
        $.ajax({
            url: '/gudang/riwayat/hapus-bahankeluar/' + idPengeluaran, // route Laravel untuk hapus
            type: 'DELETE', // gunakan metode DELETE
            data: {
                _token: '{{ csrf_token() }}' // wajib untuk keamanan Laravel
            },
            success: function(response) {
                alert('Data berhasil dihapus!');
                parentDiv.remove(); // hapus tampilan elemen tanpa reload
            },
            error: function(xhr) {
                alert('Gagal menghapus data!');
            }
        });
    }
});

// Ketika tombol tambah bahan keluar diklik
$('#btnTambahBahanKeluar').on('click', function() {
    $('#editKeluarModal .modal-content').addClass('modal-blur');
    $('#tambahBahanKeluarModal').modal('show');
});

// Hitung total harga otomatis
$('#jumlah, #bahan_baku').on('input change', function() {
    const harga = $('#bahan_baku option:selected').data('harga') || 0;
    const jumlah = parseFloat($('#jumlah').val()) || 0;
    const total = harga * jumlah;
    $('#total_harga').val(total.toLocaleString('id-ID'));
});

$('#tambahBahanKeluarModal').on('hidden.bs.modal', function () {
    $('#editKeluarModal .modal-content').removeClass('modal-blur');
});

// Simpan bahan keluar baru langsung ke DB
$('#btnSimpanTambahBahanKeluar').on('click', function() {
    const bahanId = $('#bahan_baku').val();
    const bahanNama = $('#bahan_baku option:selected').text();
    const jumlah = $('#jumlah').val();
    const hargaSatuan = $('#bahan_baku option:selected').data('harga') || 0;
    const total = hargaSatuan * parseFloat(jumlah || 0);
    const idTransaksi = $('#id_transaksi').val();

    if (!bahanId || !jumlah) {
        Swal.fire('Peringatan!', 'Mohon lengkapi semua data.', 'warning');
        return;
    }

    // Kirim data via AJAX ke controller Laravel
    $.ajax({
        url: '/gudang/riwayat/tambah-bahankeluar', // route Laravel untuk simpan
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id_transaksi: idTransaksi,
            id_bahanbaku: bahanId,
            jumlah: jumlah,
            total: total
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Bahan keluar berhasil disimpan.',
                    icon: 'success',
                    confirmButtonColor: '#198754',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Tutup modal tambah
                    $('#tambahBahanKeluarModal').modal('hide');
                    // Refresh halaman
                    location.reload();
                });

                // Reset form
                $('#formTambahBahanKeluar')[0].reset();
            } else {
                Swal.fire('Gagal!', response.message || 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Gagal!', 'Terjadi kesalahan server saat menyimpan data.', 'error');
        }
    });
});

// Hapus bahan dari daftar
$(document).on('click', '.btn-hapus-bahan', function() {
    $(this).closest('.border').remove();
});

$(document).on('click', '.btn-delete-transaksi', function() {
    const idTransaksi = $(this).data('id');
    const row = $(this).closest('tr');

    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data transaksi dan bahan yang keluar akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/gudang/riwayat/hapus/' + idTransaksi,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Dihapus!', 'Data berhasil dihapus.', 'success');
                        row.remove(); // hapus baris di tabel tanpa reload
                    } else {
                        Swal.fire('Gagal!', response.message || 'Gagal menghapus data.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                }
            });
        }
    });
});


</script>
<script>
    $(document).on('click', '.btn-hapus-bahanmasuk', function() {
    const idPemasukan = $(this).data('id'); // ambil id_pengeluaran dari tombol
    const parentDiv = $(this).closest('.border'); // ambil elemen card bahan

    if (confirm('Yakin ingin menghapus data ini? ( Proses hapus data ini akan langsung dijalankan dan tidak bisa di kembalikan lagi! )')) {
        $.ajax({
            url: '/gudang/riwayat/hapus-bahanmasuk/' + idPemasukan, // route Laravel untuk hapus
            type: 'DELETE', // gunakan metode DELETE
            data: {
                _token: '{{ csrf_token() }}' // wajib untuk keamanan Laravel
            },
            success: function(response) {
                alert('Data berhasil dihapus!');
                parentDiv.remove(); // hapus tampilan elemen tanpa reload
            },
            error: function(xhr) {
                alert('Gagal menghapus data!');
            }
        });
    }
});

// === SIMPAN EDIT PEMASUKAN (Dummy) ===
$('#btnSimpanEditMasukDummy').off('click').on('click', function() {
    // Ambil semua bahan dari modal
    let dataUpdate = [];

    $('#list-bahan-container-masuk .border').each(function() {
        const id_pemasukan = $(this).find('.btn-hapus-bahanmasuk').data('id');
        const jumlah = $(this).find('input[name="jumlah[]"]').val();
        const total = $(this).find('input[name="harga[]"]').val();
        const idTransaksi = $('#id_transaksi').val();

        dataUpdate.push({
            id_pemasukan: id_pemasukan,
            jumlah: jumlah,
            total: total,
            id_transaksi: idTransaksi
        });
    });

    // Kirim via AJAX ke controller Laravel
    $.ajax({
        url: '/gudang/riwayat/updatemasuk',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            data: dataUpdate
        },
        success: function(response) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Semua data pemasukan berhasil diperbarui.',
                icon: 'success',
                confirmButtonColor: '#198754',
                timer: 2000,
                showConfirmButton: false
            });
            $('#editKeluarModal').modal('hide');
            setTimeout(function() {
                location.reload();
            }, 1500);
        },
        error: function(xhr) {
            Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
        }
    });
});

$('#btnTambahBahanMasuk').on('click', function() {
    $('#editMasukModal .modal-content').addClass('modal-blur');
    $('#tambahBahanMasukModal').modal('show');
});

// Hitung total harga otomatis
$('#jumlahmasuk, #bahan_bakumasuk').on('input change', function() {
    const harga = $('#bahan_bakumasuk option:selected').data('harga') || 0;
    const jumlah = parseFloat($('#jumlahmasuk').val()) || 0;
    const total = harga * jumlah;
    $('#total_harga_masuk').val(total.toLocaleString('id-ID'));
});

$('#tambahBahanMasukModal').on('hidden.bs.modal', function () {
    $('#editMasukModal .modal-content').removeClass('modal-blur');
});

// Simpan bahan masuk baru langsung ke DB
$('#btnSimpanTambahBahanMasuk').on('click', function() {
    const bahanId = $('#bahan_bakumasuk').val();
    const bahanNama = $('#bahan_bakumasuk option:selected').text();
    const jumlah = $('#jumlahmasuk').val();
    const hargaSatuan = $('#bahan_bakumasuk option:selected').data('harga') || 0;
    const total = hargaSatuan * parseFloat(jumlah || 0);
    const idTransaksi = $('#id_transaksi').val();

    if (!bahanId || !jumlah) {
        Swal.fire('Peringatan!', 'Mohon lengkapi semua data.', 'warning');
        return;
    }

    // Kirim data via AJAX ke controller Laravel
    $.ajax({
        url: '/gudang/riwayat/tambah-bahanmasuk', // route Laravel untuk simpan
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id_transaksi: idTransaksi,
            id_bahanbaku: bahanId,
            jumlah: jumlah,
            total: total
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Bahan masuk berhasil disimpan.',
                    icon: 'success',
                    confirmButtonColor: '#198754',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Tutup modal tambah
                    $('#tambahBahanMasukModal').modal('hide');
                    // Refresh halaman
                    location.reload();
                });

                // Reset form
                $('#formTambahBahanMasuk')[0].reset();
            } else {
                Swal.fire('Gagal!', response.message || 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Gagal!', 'Terjadi kesalahan server saat menyimpan data.', 'error');
        }
    });
});

    // === TOMBOL EDIT (Dummy) ===
$(document).on('click', '.btn-edit-masuk', function() {
    const idTransaksi = $(this).data('id');

    $.ajax({
        url: `/gudang/riwayat/editmasuk/${idTransaksi}`,
        type: 'GET',
        success: function(response) {
            if (response && response.length > 0) {
                // Kosongkan kontainer sebelumnya
                $('#list-bahan-container-masuk').empty();

                $('#id_transaksi').val(idTransaksi);
                // Loop setiap data bahan
                response.forEach((item, index) => {
                    const html = `
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-bold text-warning mb-3">Bahan ke-${index + 1}</h6>
                            <div class="mb-3">
                                <label class="form-label">Nama Bahan</label>
                                <input type="text" class="form-control" style="background-color:rgb(212, 212, 212);" name="nama_bahan[]" value="${item.nama_bahan}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" class="form-control input-jual" name="jumlah[]" value="${item.jumlah}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Harga</label>
                                <input type="number" class="form-control input-harga" style="background-color:rgb(212, 212, 212);" name="harga[]" value="${item.harga_jual * item.jumlah}" data-harga-awal="${item.harga_jual}" readonly>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus-bahanmasuk" data-id="${item.id_pemasukan}">
                                <i class="bi bi-trash"></i> Hapus Transaksi Bahan baku ke-${index + 1}
                            </button>
                        </div>
                    `;
                    $('#list-bahan-container-masuk').append(html);
                });

                // Tampilkan modal
                $('#editMasukModal').modal('show');
            } else {
                Swal.fire('Oops!', 'Data tidak ditemukan', 'warning');
            }
        },
        error: function() {
            Swal.fire('Gagal!', 'Gagal memuat data pengeluaran.', 'error');
        }
    });
});

$(document).on('click', '.btn-delete-transaksimasuk', function() {
    const idTransaksi = $(this).data('id');
    const row = $(this).closest('tr');

    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data transaksi dan bahan yang masuk akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/gudang/riwayatmasuk/hapus/' + idTransaksi,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Dihapus!', 'Data berhasil dihapus.', 'success');
                        row.remove(); // hapus baris di tabel tanpa reload
                    } else {
                        Swal.fire('Gagal!', response.message || 'Gagal menghapus data.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                }
            });
        }
    });
});
</script>
@endsection
