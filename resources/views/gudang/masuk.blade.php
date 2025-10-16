@extends('layouts.gudang')

@section('title', 'Barang Masuk')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Gudang</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Barang Masuk</li>
    </ol>
    <h6 class="font-weight-bolder mb-0">Barang Masuk</h6>
</nav>
@endsection

@php
    use Illuminate\Support\Facades\DB;
    $suppliers = DB::table('tb_supplier')->get();
    $bahanbaku = DB::table('tb_bahanbaku')->get();
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Barang Masuk dari Supplier</h5>
                <p class="text-sm text-muted mb-0">Catat semua bahan baku yang masuk dari supplier</p>
            </div>
            <div class="card-body pt-0">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible popup-top fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible popup-top fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible popup-top fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ url('gudang/tambahmasuk') }}">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}" readonly>
                            <input type="hidden" name="tanggal_masuk" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_supplier" required>
                                <option selected disabled>Pilih Supplier</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- wrapper daftar bahan --}}
                    <div id="bahan-baku-wrapper">
                        <div class="row mt-3 bahan-row">
                            <div class="col-md-4">
                                <label class="form-label">Bahan Baku <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_bahanbaku[]" required>
                                    <option selected disabled>Pilih Bahan Baku</option>
                                    @foreach($bahanbaku as $b)
                                        <option value="{{ $b->id_bahanbaku }}" 
                                                data-satuan="{{ $b->satuan }}" 
                                                data-harga="{{ $b->harga_modal }}">
                                            {{ $b->nama_bahan }} 
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Jumlah</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="jumlah[]" placeholder="0" required>
                                    <span class="input-group-text satuan-label">-</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Harga</label>
                                <input type="text" class="form-control harga-display" readonly placeholder="Rp. 0">
                                <input type="hidden" name="harga[]" class="harga-value">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-remove-row">X</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success mt-2" id="add-row">+ Tambah Bahan</button>

                    <div class="mt-4">
                        <label class="form-label fw-bold" style="gap: 20px">Total Harga</label>
                        <input type="text" id="total_harga_display" class="form-control" readonly value="Rp. 0">
                        <input type="hidden" name="total_harga" id="total_harga_value">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary" style="background-color: #ffd600; color:black">
                            <i class="bi bi-clock me-2"></i>Pending
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Barang Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5>Petunjuk Pengisian</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-primary bg-primary bg-opacity-10 border-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Perhatian:</strong> Pastikan data yang dimasukkan sesuai dengan faktur supplier
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-2 d-flex align-items-center">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <span>Isi tanggal sesuai dengan tanggal barang diterima</span>
                    </li>
                    <li class="list-group-item px-0 py-2 d-flex align-items-center">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <span>Pilih supplier yang mengirimkan barang</span>
                    </li>
                    <li class="list-group-item px-0 py-2 d-flex align-items-center">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <span>Periksa kembali jumlah dan satuan</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Stok Terkini</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($bahanbaku as $b)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-box me-2 text-secondary"></i>
                                {{ $b->nama_bahan }}
                            </span>
                            <span class="badge bg-secondary rounded-pill">{{ $b->stok }} {{ $b->satuan }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(angka);
}

function updateRow(row) {
    const select = row.querySelector('select[name="id_bahanbaku[]"]');
    const jumlah = parseInt(row.querySelector('input[name="jumlah[]"]').value) || 0;
    const hargaDisplay = row.querySelector('.harga-display');
    const hargaHidden = row.querySelector('.harga-value');

    const hargaSatuan = parseInt(select.selectedOptions[0]?.dataset.harga) || 0;
    const satuan = select.selectedOptions[0]?.dataset.satuan || '-';

    hargaHidden.value = hargaSatuan;
    hargaDisplay.value = formatRupiah(hargaSatuan * jumlah);
    row.querySelector('.satuan-label').textContent = satuan;
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.bahan-row').forEach(row => {
        const jumlah = parseInt(row.querySelector('input[name="jumlah[]"]').value) || 0;
        const hargaSatuan = parseInt(row.querySelector('.harga-value').value) || 0;
        total += jumlah * hargaSatuan;
    });
    document.getElementById('total_harga_value').value = total;
    document.getElementById('total_harga_display').value = formatRupiah(total);
}

document.addEventListener('input', e => {
    if (e.target.name === 'jumlah[]') {
        updateRow(e.target.closest('.bahan-row'));
        updateTotal();
    }
});
document.addEventListener('change', e => {
    if (e.target.name === 'id_bahanbaku[]') {
        updateRow(e.target.closest('.bahan-row'));
        updateTotal();
    }
});

document.getElementById('add-row').addEventListener('click', () => {
    const wrapper = document.getElementById('bahan-baku-wrapper');
    const firstRow = wrapper.querySelector('.bahan-row');
    const clone = firstRow.cloneNode(true);

    // reset isi
    clone.querySelectorAll('input').forEach(input => input.value = '');
    clone.querySelector('select').value = '';
    clone.querySelector('.satuan-label').textContent = '-';
    clone.querySelector('.harga-display').value = 'Rp. 0';
    clone.querySelector('.harga-value').value = '';

    wrapper.appendChild(clone);

    // tombol hapus
    clone.querySelector('.btn-remove-row').addEventListener('click', () => {
        clone.remove();
        updateTotal();
    });
});

// tombol hapus baris awal
document.querySelectorAll('.btn-remove-row').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.bahan-row').remove();
        updateTotal();
    });
});
</script>
@endsection
