@extends('layouts.gudang')
@section('title', 'Tabel Paket Bahan Baku Untuk Mitra')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-end mb-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
            <i class="bi bi-plus-circle"></i> Tambah Paket
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th style="white-space: nowrap;">No</th>
                    <th style="white-space: nowrap;">Nama Paket</th>
                    <th style="white-space: nowrap;">Bahan Baku</th>
                    <th style="white-space: nowrap;">Jumlah Bahan Baku</th>
                    <th style="white-space: nowrap;">Harga</th>
                    <th style="white-space: nowrap;">Gambar Paket</th>
                    <th style="white-space: nowrap;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paket as $index => $row)
                    <tr>
                        <td style="white-space: nowrap;">{{ $index + 1 }}</td>
                        <td style="white-space: nowrap;">{{ $row->nama_paket }}</td>
                        <td class="text-start" style="white-space: nowrap;">
                            @php
                                $bahanList = explode(', ', $row->bahan);
                                $jumlahList = explode(', ', $row->jumlah);
                                $satuanList = explode(', ', $row->satuan);
                            @endphp

                            @foreach($bahanList as $i => $bahan)
                                <div><strong>•</strong> {{ $bahan }}</div>
                            @endforeach
                        </td>

                        <td class="text-start" style="white-space: nowrap;">
                            @foreach($jumlahList as $i => $jumlah)
                                <div><strong>•</strong> {{ $jumlah }} {{ $satuanList[$i] ?? '-' }}</div>
                            @endforeach
                        </td>
                        <td style="white-space: nowrap;">Rp. {{ number_format($row->harga, 0, ',', '.') }}</td>
                        <td style="white-space: nowrap;">
                            @if($row->gambar_paket)
                                <img src="{{ asset('uploads/paket/' . $row->gambar_paket) }}" 
                                    width="120" 
                                    height="120" 
                                    style="object-fit: cover; border-radius: 8px;">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td class="text-center" style="white-space: nowrap;">
                            <div class="d-flex justify-content-center align-items-center gap-2 action-buttons">
                                <button 
                                    type="button"
                                    class="btn btn-sm btn-outline-warning p-1"
                                    title="Edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditPaket"
                                    data-id="{{ $row->id_paket }}"
                                    data-nama="{{ $row->nama_paket }}"
                                    data-harga="{{ $row->harga }}"
                                    data-idbahan="{{ $row->id_bahanbaku }}"
                                    data-bahan="{{ $row->bahan }}"
                                    data-jumlah="{{ $row->jumlah }}"
                                    data-satuan="{{ $row->satuan }}"
                                    data-gambar="{{ $row->gambar_paket }}"
                                    onclick="openEditPaket(this)">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger p-1 btn-delete-paket" title="Hapus" data-id="{{ $row->id_paket }}">
                                    <i class="bi bi-trash-fill fs-5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted" style="white-space: nowrap;">Tidak ada data Paket.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambahPaket" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('paket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="nama_paket" class="form-control" required>
                    </div>

                    <label class="form-label">Bahan Yang Di Dapat :</label>

                    <div id="bahan-wrapper">
                        <div class="row mb-2 bahan-item align-items-center position-relative">
                            <div class="col-md-5 position-relative">
                                <input type="text"
                                    class="form-control bahan-input"
                                    placeholder="Ketik / pilih bahan..."
                                    autocomplete="off"
                                    oninput="filterBahan(this)"
                                    onclick="showBahanList(this)">

                                <input type="hidden" name="id_bahanbaku[]" class="id-bahan-input">

                                <div class="bahan-list list-group position-absolute w-100 d-none"
                                    style="z-index: 9999; max-height: 220px; overflow-y: auto;">
                                    @foreach($bahanbaku as $bahan)
                                        <button type="button"
                                                class="list-group-item list-group-item-action bahan-option"
                                                data-id="{{ $bahan->id_bahanbaku }}"
                                                data-nama="{{ strtolower($bahan->nama_bahan) }}"
                                                data-text="{{ $bahan->nama_bahan }}"
                                                data-satuan="{{ $bahan->satuan }}"
                                                onclick="pilihBahan(this)">
                                            {{ $bahan->nama_bahan }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-4">
                                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah">
                            </div>

                            <div class="col-md-2">
                                <input type="text" class="form-control satuan-input" placeholder="Satuan" readonly>
                            </div>

                            <div class="col-md-1">
                                <button type="button" onclick="hapusBaris(this)" class="btn btn-danger w-100">
                                    X
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="tambahBaris()" class="btn btn-primary mt-2">
                        + Tambah Bahan
                    </button>

                    <div class="my-3">
                        <label class="form-label">Harga Paket</label>
                        <input type="text" name="harga" id="harga" class="form-control" placeholder="Rp 0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Paket</label>
                        <input type="file" 
                            name="gambar" 
                            id="tambah_gambar"
                            class="form-control" 
                            accept="image/*"
                            onchange="previewGambarTambah(event)">
                    </div>

                    <div class="mt-3">
                        <img id="preview_tambah" 
                            src="" 
                            width="120" 
                            style="border-radius:8px; object-fit:cover; display:none;">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPaket" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('paket.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="id_paket" id="edit_id_paket">

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="nama_paket" id="edit_nama_paket" class="form-control" required>
                    </div>

                    <label class="form-label">Bahan Yang Di Dapat :</label>

                    <div id="edit-bahan-wrapper"></div>

                    <button type="button" onclick="tambahBarisEdit()" class="btn btn-primary mt-2">
                        + Tambah Bahan
                    </button>

                    <div class="my-3">
                        <label class="form-label">Harga Paket</label>
                        <input type="text" 
                            name="harga" 
                            id="edit_harga" 
                            class="form-control" 
                            required
                            oninput="formatRupiahInput(this)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Paket</label>
                        <input type="file" name="gambar" id="edit_gambar" 
                            class="form-control" 
                            accept="image/*" 
                            onchange="previewGambarEdit(event)">

                        <div class="mt-3">
                            <img id="preview_gambar" 
                                src="" 
                                width="120" 
                                style="border-radius:8px; object-fit:cover;">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateSatuan(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const satuan = selectedOption.getAttribute('data-satuan') || '';

        const row = selectElement.closest('.bahan-item');
        const satuanInput = row.querySelector('.satuan-input');

        satuanInput.value = satuan;
    }

    function tambahBaris() {
        const wrapper = document.getElementById('bahan-wrapper');

        const html = `
            <div class="row mb-2 bahan-item align-items-center position-relative">
                <div class="col-md-5 position-relative">
                    <input type="text"
                        class="form-control bahan-input"
                        placeholder="Ketik / pilih bahan..."
                        autocomplete="off"
                        oninput="filterBahan(this)"
                        onclick="showBahanList(this)">

                    <input type="hidden" name="id_bahanbaku[]" class="id-bahan-input">

                    <div class="bahan-list list-group position-absolute w-100 d-none"
                        style="z-index: 9999; max-height: 220px; overflow-y: auto;">
                        @foreach($bahanbaku as $bahan)
                            <button type="button"
                                    class="list-group-item list-group-item-action bahan-option"
                                    data-id="{{ $bahan->id_bahanbaku }}"
                                    data-nama="{{ strtolower($bahan->nama_bahan) }}"
                                    data-text="{{ $bahan->nama_bahan }}"
                                    data-satuan="{{ $bahan->satuan }}"
                                    onclick="pilihBahan(this)">
                                {{ $bahan->nama_bahan }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-4">
                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah">
                </div>

                <div class="col-md-2">
                    <input type="text" class="form-control satuan-input" placeholder="Satuan" readonly>
                </div>

                <div class="col-md-1">
                    <button type="button" onclick="hapusBaris(this)" class="btn btn-danger w-100">
                        X
                    </button>
                </div>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
    }
    
    function hapusBaris(button) {
        const items = document.querySelectorAll('.bahan-item');

        if (items.length > 1) {
            button.closest('.bahan-item').remove();
        } else {
            alert('Minimal harus ada satu bahan.');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('harga');

        input.addEventListener('keyup', function (e) {
            let angka = this.value.replace(/[^,\d]/g, '').toString();
            let split = angka.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            this.value = 'Rp ' + rupiah;
        });
    });

    function previewGambarTambah(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview_tambah');

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    function showBahanList(input) {
        const row = input.closest('.bahan-item');
        const list = row.querySelector('.bahan-list');

        list.classList.remove('d-none');

        filterBahan(input);
    }

    function filterBahan(input) {
        const row = input.closest('.bahan-item');
        const keyword = input.value.toLowerCase();
        const list = row.querySelector('.bahan-list');
        const options = row.querySelectorAll('.bahan-option');

        list.classList.remove('d-none');

        options.forEach(option => {
            const nama = option.dataset.nama;

            if (nama.includes(keyword)) {
                option.classList.remove('d-none');
            } else {
                option.classList.add('d-none');
            }
        });

        row.querySelector('.id-bahan-input').value = '';
        row.querySelector('.satuan-input').value = '';
    }

    function pilihBahan(button) {
        const row = button.closest('.bahan-item');

        row.querySelector('.bahan-input').value = button.dataset.text;
        row.querySelector('.id-bahan-input').value = button.dataset.id;
        row.querySelector('.satuan-input').value = button.dataset.satuan;

        row.querySelector('.bahan-list').classList.add('d-none');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.bahan-item')) {
            document.querySelectorAll('.bahan-list').forEach(list => {
                list.classList.add('d-none');
            });
        }
    });

    function initSelect2() {
        $('.select-bahan').select2({
            dropdownParent: $('#modalTambahPaket'),
            width: '100%',
            placeholder: '-- Pilih Bahan --',
            allowClear: true
        });
    }

    $(document).ready(function () {
        initSelect2();

        $(document).on('change', '.select-bahan', function () {
            updateSatuan(this);
        });
    });

    function formatRupiahValue(angka) {
    angka = angka.toString().replace(/[^0-9]/g, '');

    if (!angka) return '';

    return 'Rp ' + angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

    function bahanOptionsHtml() {
        return `
            @foreach($bahanbaku as $bahan)
                <button type="button"
                        class="list-group-item list-group-item-action bahan-option"
                        data-id="{{ $bahan->id_bahanbaku }}"
                        data-nama="{{ strtolower($bahan->nama_bahan) }}"
                        data-text="{{ $bahan->nama_bahan }}"
                        data-satuan="{{ $bahan->satuan }}"
                        onclick="pilihBahan(this)">
                    {{ $bahan->nama_bahan }}
                </button>
            @endforeach
        `;
    }

    function buatRowBahan(valueId = '', valueNama = '', valueJumlah = '', valueSatuan = '') {
        return `
            <div class="row mb-2 bahan-item align-items-center position-relative">
                <div class="col-md-5 position-relative">
                    <input type="text"
                        class="form-control bahan-input"
                        placeholder="Ketik / pilih bahan..."
                        autocomplete="off"
                        value="${valueNama}"
                        oninput="filterBahan(this)"
                        onclick="showBahanList(this)">

                    <input type="hidden" name="id_bahanbaku[]" class="id-bahan-input" value="${valueId}">

                    <div class="bahan-list list-group position-absolute w-100 d-none"
                        style="z-index: 9999; max-height: 220px; overflow-y: auto;">
                        ${bahanOptionsHtml()}
                    </div>
                </div>

                <div class="col-md-4">
                    <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" value="${valueJumlah}">
                </div>

                <div class="col-md-2">
                    <input type="text" class="form-control satuan-input" placeholder="Satuan" value="${valueSatuan}" readonly>
                </div>

                <div class="col-md-1">
                    <button type="button" onclick="hapusBaris(this)" class="btn btn-danger w-100">
                        X
                    </button>
                </div>
            </div>
        `;
    }

    function tambahBarisEdit() {
        const wrapper = document.getElementById('edit-bahan-wrapper');
        wrapper.insertAdjacentHTML('beforeend', buatRowBahan());
    }

    function openEditPaket(button) {
        const idPaket = button.dataset.id;
        const namaPaket = button.dataset.nama;
        const harga = button.dataset.harga;
        const gambar = button.dataset.gambar;

        const idBahanList = button.dataset.idbahan ? button.dataset.idbahan.split(', ') : [];
        const bahanList = button.dataset.bahan ? button.dataset.bahan.split(', ') : [];
        const jumlahList = button.dataset.jumlah ? button.dataset.jumlah.split(', ') : [];
        const satuanList = button.dataset.satuan ? button.dataset.satuan.split(', ') : [];

        document.getElementById('edit_id_paket').value = idPaket;
        document.getElementById('edit_nama_paket').value = namaPaket;
        document.getElementById('edit_harga').value = formatRupiahValue(harga);

        const wrapper = document.getElementById('edit-bahan-wrapper');
        wrapper.innerHTML = '';

        if (gambar) {
            document.getElementById('preview_gambar').src = '/uploads/paket/' + gambar;
        } else {
            document.getElementById('preview_gambar').src = '';
        }

        bahanList.forEach((bahan, i) => {
            wrapper.insertAdjacentHTML('beforeend', buatRowBahan(
                idBahanList[i] ?? '',
                bahan ?? '',
                jumlahList[i] ?? '',
                satuanList[i] ?? ''
            ));
        });
    }

    function previewGambarEdit(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview_gambar');

        if (file) {
            preview.src = URL.createObjectURL(file);
        }
    }

    function formatRupiahInput(input) {
        let angka = input.value.replace(/[^0-9]/g, '');

        if (angka === '') {
            input.value = '';
            return;
        }

        input.value = 'Rp ' + angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }


    $(document).on('click', '.btn-delete-paket', function(e) {
        e.preventDefault();

        const idPaket = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data paket akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/gudang/paket/delete/' + idPaket,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Dihapus!', 'Paket berhasil dihapus.', 'success');
                            row.remove();
                        } else {
                            Swal.fire('Gagal!', response.message || 'Gagal menghapus paket.', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });
</script>
