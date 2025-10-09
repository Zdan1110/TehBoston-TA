@section('Title')
Edit Produk
@endsection
@extends('admin.templatecoba')
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
</style>

<div class="container-fluid" style="margin-top:75px">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header" style="background-color: var(--dark-green)">
                    <h3 class="card-title">Edit Data Produk</h3>
                </div>

                <form action="/admin/produk/update/{{ $produk->id_produk }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="idProduk">ID Produk</label>
                            <input type="text" name="id_produk" class="form-control" id="idProduk" 
                                   placeholder="Masukan ID Produk" value="{{ $produk->id_produk }}" readonly>
                            <div class="text-danger">
                                @error('id_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="namaProduk">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" id="namaProduk" 
                                   placeholder="Masukan Nama Produk" value="{{ $produk->nama_produk }}">
                            <div class="text-danger">
                                @error('nama_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hpp">HPP</label>
                            <input type="text" name="hpp" class="form-control" id="hpp" 
                                   placeholder="Masukan HPP" value="{{ $produk->hpp }}">
                            <div class="text-danger">
                                @error('hpp')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hargaProduk">Harga Jual</label>
                            <input type="text" name="harga" class="form-control" id="hargaProduk" 
                                   placeholder="Masukan Harga" value="{{ $produk->harga }}">
                            <div class="text-danger">
                                @error('harga')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                        <label for="gambarProduk">Gambar Produk</label>
                        <div class="custom-file">
                            <input type="file" name="gambar_produk" class="form-control custom-file-input" id="gambarProduk" onchange="previewGambar(event)">
                        </div>
                        <div style="margin-top: 15px;">
                            <label>Preview Gambar Lama:</label><br>
                            @if($produk->gambar_produk && file_exists(public_path('uploads/produk/' . $produk->gambar_produk)))
                            <img id="preview" src="{{ asset('uploads/produk/' . $produk->gambar_produk) }}" alt="Gambar Produk" width="150" style="border-radius: 8px;">
                        @else
                            <p class="text-muted">Gambar tidak tersedia.</p>
                        @endif

                        </div>
                        <div class="text-danger">
                            @error('gambar_produk')
                                {{ $message }}
                            @enderror
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