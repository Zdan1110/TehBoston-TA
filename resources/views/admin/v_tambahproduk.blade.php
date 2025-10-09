@section ('Title')
Tambah Produk
@endsection
@extends('admin.templatecoba')
@section('content')
<style>
    /* Modern Green Color Scheme */
    :root {
        --primary-green: #2E8B57; /* Warna hijau utama */
        --dark-green: #1F6F4A; /* Hijau lebih tua untuk header */
        --light-green: #E8F5E9;
        --accent-green: #4CAF50;
        --card-bg: #FFFFFF;
        --text-dark: #000000;
        --text-light: #666666;
        --border-radius: 10px;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    body {
        background-color: #F8F9FA;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .card-primary {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }

    /* Card Header Style (Tetap seperti sebelumnya) */
    .card-header {
        background-color: var(--dark-green); /* Warna hijau tua */
        color: white;
        border-bottom: none;
        padding: 15px 20px;
    }

    .card-title {
        font-weight: 600;
        margin: 0;
        font-size: 1.2rem;
    }

    /* Card Body Modern */
    .card-body {
        background-color: var(--card-bg);
        padding: 25px;
    }

    .card-footer {
        background-color: var(--light-green);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 15px 20px;
        display: flex;
        justify-content: flex-end;
    }

    /* Form Elements Modern */
    .form-group {
        margin-bottom: 20px;
    }

    label {
        color: black !important;
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

    /* Button Style Modern */
    .btn-warning {
        background-color: var(--primary-green);
        border: none;
        color: white;
        padding: 8px 20px;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background-color: var(--dark-green);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    /* Error Message */
    .text-danger {
        color: #E53935;
        font-size: 0.8rem;
        margin-top: 5px;
        display: block;
    }

    /* File Input Modern */
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

    /* Layout */
    .container-fluid {
        padding: 0 25px;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
            padding: 0 15px;
        }
        
        .container-fluid {
            padding: 0 15px;
        }
    }
</style>

<div class="container-fluid" style="margin-top:70px">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Card Design dengan header tetap -->
            <div class="card card-primary">
                <div class="card-header" style="background-color: var(--dark-green)">
                    <h3 class="card-title">Tambah Data Produk</h3>
                </div>

                <form action="/admin/produk/insert" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="namaProduk">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" id="namaProduk" placeholder="Masukkan Nama Produk" value="{{ old('nama_produk') }}">
                            <div class="text-danger">
                                @error('nama_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hpp">HPP</label>
                            <input type="text" name="hpp" class="form-control" id="hpp" placeholder="Masukkan HPP" value="{{ old('hpp') }}">
                            <div class="text-danger">
                                @error('hpp')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hargaProduk">Harga Jual</label>
                            <input type="text" name="harga" class="form-control" id="hargaProduk" placeholder="Masukkan Harga Jual" value="{{ old('harga') }}">
                            <div class="text-danger">
                                @error('harga')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gambarProduk">Gambar Produk</label>
                            <div class="custom-file">
                                <input type="file" name="gambar_produk" class="form-control custom-file-input" id="gambarProduk">
                            </div>
                            <div class="text-danger">
                                @error('gambar_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="background-color: var(--dark-green)">
                        <button type="submit" class="btn btn-warning">
                            Tambah Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection