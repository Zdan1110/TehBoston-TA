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
                                <th>Status</th>
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
                                
                                    <td class="text-center">
                                    <span class="badge bg-warning text-dark me-1">Pending</span>
                                    {{-- <span class="badge bg-success">Completed</span> --}}
                                </td>
                                 
    <td class="text-center">
    <div class="d-flex justify-content-center align-items-center gap-2 action-buttons">
        <!-- Tombol Complete -->
        <button class="btn btn-sm btn-outline-success p-1 btn-complete" title="Selesaikan">
            <i class="bi bi-check-circle-fill fs-5"></i>
        </button>

        <!-- Tombol Edit -->
        <button class="btn btn-sm btn-outline-warning p-1 btn-edit" title="Edit">
            <i class="bi bi-pencil-square fs-5"></i>
        </button>

        <!-- Tombol Delete -->
        <button class="btn btn-sm btn-outline-danger p-1 btn-delete" title="Hapus">
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

<!-- Modal Edit Dummy -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="bi bi-pencil-square"></i> Edit Data Pemasukan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <!-- Form Dummy -->
                <form id="editFormDummy">
                    <div class="mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama bahan...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" class="form-control" placeholder="Masukkan jumlah...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama supplier...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <input type="text" class="form-control" placeholder="Masukkan total harga...">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSimpanEditDummy">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@section('scripts')
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

    // === TOMBOL EDIT (Dummy) ===
document.addEventListener('click', function(e) {
    const editBtn = e.target.closest('.btn-edit');
    if (!editBtn) return;

    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
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
            text: 'Data ini akan dihapus secara permanen!',
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
</script>
@endsection
