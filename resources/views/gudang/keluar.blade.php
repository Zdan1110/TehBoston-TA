@extends('layouts.gudang')

@section('title', 'Barang Keluar')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="#">Gudang</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Barang Keluar</li>
    </ol>
    <h6 class="font-weight-bolder mb-0">Barang Keluar</h6>
</nav>
@endsection

@php
    use Illuminate\Support\Facades\DB;
    $franchise = DB::table('tb_franchise')->get();
    $bahanbaku = DB::table('tb_bahanbaku')->get();
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5>Form Barang Keluar ke Franchise</h5>
                <p class="text-sm text-muted mb-0">Catat semua bahan baku yang keluar ke franchise</p>
            </div>
            <div class="card-body pt-0">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ url('gudang/tambahkeluar') }}" id="form-barang-keluar">
                    @csrf

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Pilih Franchise <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_franchise" required>
                                <option selected disabled>-- Pilih Franchise --</option>
                                @foreach($franchise as $f)
                                    <option value="{{ $f->id_franchise }}">{{ $f->nama_franchise }} - {{ $f->alamat_usaha }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Tanggal Keluar</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}" readonly>
                            <input type="hidden" name="tanggal_keluar" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}">
                        </div>

                    </div>

                    {{-- Daftar Bahan Baku --}}
                    <div id="bahan-baku-wrapper">
                        <div class="row mt-3 bahan-row">
                            <div class="col-md-4">
                                <label class="form-label">Bahan Baku</label>
                                <select class="form-select" name="id_bahanbaku[]" required>
                                    <option selected disabled>Pilih Bahan Baku</option>
                                    @foreach($bahanbaku as $b)
                                        <option value="{{ $b->id_bahanbaku }}" data-satuan="{{ $b->satuan }}" data-harga="{{ $b->harga_jual }}">{{ $b->nama_bahan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jumlah</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="jumlah[]" required placeholder="0">
                                    <span class="input-group-text satuan-label">-</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" class="form-control harga-display" placeholder="Rp. 0">
                                <input type="hidden" name="harga[]" class="harga-value">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-remove-row">X</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success mt-2" id="add-row">+ Tambah Bahan</button>

                    <div class="mt-4">
                        <label class="form-label fw-bold">Total Harga</label>
                        <input type="text" id="total_harga_display" class="form-control" readonly value="Rp. 0">
                        <input type="hidden" name="total_harga" id="total_harga_value">
                    </div>
                    

                    <div class="d-flex justify-content-between mt-4">
                       
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Barang Keluar
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
                <div class="alert alert-primary bg-opacity-10">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Perhatian:</strong> Pastikan data yang dimasukkan sesuai dengan tujuan franchise
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item py-2"><i class="bi bi-check-circle text-success me-2"></i>Pilih franchise </li>
                    <li class="list-group-item py-2"><i class="bi bi-check-circle text-success me-2"></i> Pilih Bahan Baku</li>
                    <li class="list-group-item py-2"><i class="bi bi-check-circle text-success me-2"></i> Periksa kembali jumlah & satuan</li>
                </ul>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Stok Terkini</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($bahanbaku as $b)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-box me-2 text-secondary"></i>{{ $b->nama_bahan }}</span>
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
    document.addEventListener("DOMContentLoaded", function () {
        const bahanSelect = document.getElementById('bahanSelect');
        const satuanSpan = document.getElementById('satuan');

        function updateSatuan() {
            const selectedOption = bahanSelect.options[bahanSelect.selectedIndex];
            if (selectedOption) {
                const satuan = selectedOption.getAttribute('data-satuan') || '-';
                satuanSpan.textContent = satuan;
            }
        }

        updateSatuan();
        bahanSelect.addEventListener('change', updateSatuan);
    });
</script>
<script>
    const displayInput = document.getElementById('total_display');
    const hiddenInput = document.getElementById('total');
    const form = document.querySelector('form');
    const errorLabel = document.getElementById('total_error');

    function formatRupiah(angka, prefix = '') {
        let number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return prefix + rupiah;
    }

    function updateRow(row) {
        const select = row.querySelector('select[name="id_bahanbaku[]"]');
        const jumlah = parseInt(row.querySelector('input[name="jumlah[]"]').value) || 0;
        const hargaDisplay = row.querySelector('.harga-display');
        const hargaHidden = row.querySelector('.harga-value');

        const hargaSatuan = parseInt(select.selectedOptions[0]?.dataset.harga) || 0;

        hargaHidden.value = hargaSatuan; // simpan harga satuan ke backend
        hargaDisplay.value = formatRupiah((hargaSatuan * jumlah).toString(), 'Rp. '); // tampilkan total baris
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.bahan-row').forEach(row => {
            const jumlah = parseInt(row.querySelector('input[name="jumlah[]"]').value) || 0;
            const hargaSatuan = parseInt(row.querySelector('.harga-value').value) || 0;
            total += jumlah * hargaSatuan;
        });
        document.getElementById('total_harga_value').value = total;
        document.getElementById('total_harga_display').value = formatRupiah(total.toString(), 'Rp. ');
    }

    document.addEventListener('input', function(e) {
        if (e.target.name === 'jumlah[]') {
            const row = e.target.closest('.bahan-row');
            updateRow(row);
            updateTotal();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.name === 'id_bahanbaku[]') {
            const row = e.target.closest('.bahan-row');
            updateRow(row);
            updateTotal();
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function formatRupiahInput(input) {
        input.addEventListener("input", function () {
            let angka = this.value.replace(/[^0-9]/g, '');
            if (angka === '') angka = '0';
            const angkaInt = parseInt(angka);
            this.value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
            }).format(angkaInt);
        });
    }

    // Inisialisasi harga awal
    document.querySelectorAll('.harga-satuan').forEach(formatRupiahInput);

    // Fungsi untuk atur satuan saat bahan dipilih
    function setSatuan(selectElement) {
        const satuan = selectElement.selectedOptions[0].dataset.satuan || '-';
        const satuanLabel = selectElement.closest('.bahan-row').querySelector('.satuan-label');
        satuanLabel.textContent = satuan;
    }


    // Tambah baris bahan
    document.getElementById('add-row').addEventListener('click', function () {
        const wrapper = document.getElementById('bahan-baku-wrapper');
        const row = wrapper.querySelector('.bahan-row');
        const clone = row.cloneNode(true);

        // Reset input pada baris baru
        clone.querySelectorAll('input, select').forEach(input => {
            input.value = '';
        });

        wrapper.appendChild(clone);
        clone.querySelectorAll('.harga-satuan').forEach(formatRupiahInput);

        // Tambahkan event hapus pada tombol baru
        clone.querySelector('.btn-remove-row').addEventListener('click', function () {
            clone.remove();
            updateTotalHarga(); // panggil saat baris dihapus juga
        });

            // Tambahkan event input untuk input jumlah & harga baru
        clone.querySelectorAll('input[name="jumlah[]"], .harga-display').forEach(input => {
            input.addEventListener('input', updateTotalHarga);
        });

        updateTotalHarga(); // ini yang penting ditambahkan di akhir
        });

    // Inisialisasi pada semua select awal
    document.querySelectorAll('select[name="id_bahanbaku[]"]').forEach(select => {
        select.addEventListener('change', function () {
            setSatuan(this);
        });
    });

    // Setelah append clone
    clone.querySelectorAll('select[name="id_bahanbaku[]"]').forEach(select => {
        select.addEventListener('change', function () {
            setSatuan(this);
        });
    });


    // Tombol hapus baris pertama
    document.querySelectorAll('.btn-remove-row').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.bahan-row').remove();
        });
    });
});
</script>

<script>
function formatRupiah(angka, prefix = '') {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   	 = number_string.split(','),
        sisa     	 = split[0].length % 3,
        rupiah     	 = split[0].substr(0, sisa),
        ribuan     	 = split[0].substr(sisa).match(/\d{3}/gi);
    
    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return prefix + rupiah;
}

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('harga-display')) {
        let angka = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = formatRupiah(angka, 'Rp. ');
        e.target.nextElementSibling.value = angka; // simpan ke hidden input
    }
});


document.addEventListener('input', function(e) {
    if (
        e.target.name === 'jumlah[]' ||
        e.target.classList.contains('harga-display')
    ) {
        updateTotalHarga();
    }
});
</script>


@endsection
