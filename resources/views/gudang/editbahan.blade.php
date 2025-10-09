@section('Title')
Edit Bahan Baku
@endsection
@extends('layouts.gudang')
@section('content')
<style>
    /* Modern Green Color Scheme */
    :root {
        --primary-green: #2E8B57;
        --dark-green: #1F6F4A;
        --light-green: #E8F5E9;
        --card-bg: #FFFFFF;
        --text-dark: #333333;
        --border-radius: 10px;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    body {
        background-color: #F8F9FA;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .container-fluid {
        padding: 0 25px;
    }

    .card-primary {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 30px;
    }

    /* Card Header - Unchanged Style */
    .card-header {
        background-color: var(--dark-green);
        color: white;
        padding: 15px 20px;
        border-bottom: none;
    }

    .card-title {
        font-weight: 600;
        margin: 0;
        font-size: 1.2rem;
    }

    .card-body {
        background-color: var(--card-bg);
        padding: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        color: var(--text-dark);
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
        font-size: 0.9rem;
    }

    .form-control {
        border: 1px solid #E0E0E0;
        border-radius: 6px;
        padding: 10px 12px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.2);
        outline: none;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
    }

    .btn-warning {
        background-color: var(--primary-green);
        border: none;
        color: white;
        padding: 8px 20px;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-warning:hover {
        background-color: var(--dark-green);
        transform: translateY(-1px);
    }

    .text-danger {
        color: #E53935;
        font-size: 0.8rem;
        margin-top: 5px;
        display: block;
    }

    .custom-file-input {
        cursor: pointer;
        padding: 8px;
        border: 1px solid #E0E0E0;
        border-radius: 6px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .custom-file-input:hover {
        border-color: var(--primary-green);
    }

    .card-footer {
        background-color: var(--light-green);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 15px 20px;
        display: flex;
        justify-content: flex-end;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
            padding: 0;
        }
        
        .container-fluid {
            padding: 0 15px;
        }
    }
    
    .select-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 100%;
        padding: 10px;
        padding-right: 35px; /* beri ruang untuk panah */
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
        cursor: pointer;
    }

    .select-wrapper::after {
        content: '\25BC'; /* simbol panah ▼ */
        font-size: 14px;
        color: #555;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none; /* supaya klik tetap ke select */
    }

    .select-wrapper select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
        outline: none;
    }
</style>

<div class="container-fluid" style="margin-top:75px">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header" style="background-color: var(--dark-green)">
                    <h3 class="card-title">Edit Data Bahan Baku</h3>
                </div>

                <form action="/gudang/bahan/update/{{ $bahan->id_bahanbaku }}/{{ $bahan->id_supplier }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="supplier">Supplier</label>
                            <input type="text" name="id_supplier" class="form-control" id="supplier" 
                                   placeholder="Masukan ID Supplier" value="{{ $bahan->nama_supplier }}" readonly>
                            <div class="text-danger">
                                @error('nama_supplier')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="namaBahan">Nama Bahan</label>
                            <input type="text" name="nama_bahan" class="form-control" id="namaBahan" 
                                   placeholder="Masukan Nama Bahan" value="{{ $bahan->nama_bahan }}">
                            <div class="text-danger">
                                @error('nama_bahan')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jenisBahan">Jenis Bahan</label>
                            <div class="select-wrapper">
                                <select name="jenis_bahan" class="form-control" id="jenisBahan">
                                    <option value="" disabled selected>-- Pilih Jenis Bahan --</option>
                                    <option value="serbuk" {{ $bahan->jenis_bahan == 'serbuk' ? 'selected' : '' }}>Serbuk</option>
                                    <option value="sirup" {{ $bahan->jenis_bahan == 'sirup' ? 'selected' : '' }}>Sirup</option>
                                    <option value="lain-lain" {{ $bahan->jenis_bahan == 'lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                            <div class="text-danger">
                                @error('jenis_bahan')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hargaModal">Harga Modal</label>
                            <input type="number" name="harga_modal" class="form-control" id="hargaModal" 
                                   placeholder="Masukan Harga Modal" value="{{ $bahan->harga_modal }}">
                            <div class="text-danger">
                                @error('harga_modal')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="hargaJual">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" id="hargaJual" 
                                   placeholder="Masukan Harga Jual" value="{{ $bahan->harga_jual }}">
                            <div class="text-danger">
                                @error('harga_jual')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" class="form-control" id="stok" 
                                   placeholder="Masukan Stok" value="{{ $bahan->stok }}">
                            <div class="text-danger">
                                @error('stok')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="Satuan">Jenis Satuan Bahan</label>
                            <input type="text" name="satuan" class="form-control" id="Satuan" 
                                   placeholder="Masukan Jenis Satuan Bahan" value="{{ $bahan->satuan }}">
                            <div class="text-danger">
                                @error('satuan')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                    </div>


                    <div class="card-footer" style="background-color: var(--dark-green)">
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary" style="margin-left: 10px;">Kembali</a>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function previewGambar(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection