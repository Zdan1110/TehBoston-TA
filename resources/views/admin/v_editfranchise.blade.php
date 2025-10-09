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
                    <h3 class="card-title">Edit Data Franchise</h3>
                </div>

                <form action="/admin/franchise/update/{{ $franchise->id_franchise }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="idfranchise">ID franchise</label>
                            <input type="text" name="id_franchise" class="form-control" id="idfranchise" 
                                   placeholder="Masukan ID franchise" value="{{ $franchise->id_franchise }}" readonly>
                            <div class="text-danger">
                                @error('id_franchise')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="namafranchise">Nama franchise</label>
                            <input type="text" name="nama_franchise" class="form-control" id="namafranchise" 
                                   placeholder="Masukan Nama franchise" value="{{ $franchise->nama_franchise }}">
                            <div class="text-danger">
                                @error('nama_franchise')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="provinsi_usaha">provinsi usaha</label>
                            <input type="text" name="provinsi_usaha" class="form-control" id="provinsi_usaha" 
                                   placeholder="Masukan Provinsi Usaha" value="{{ $franchise->provinsi_usaha }}">
                            <div class="text-danger">
                                @error('provinsi_usaha')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kota_usaha">Kota Usaha</label>
                            <input type="text" name="kota_usaha" class="form-control" id="kota_usaha" 
                                   placeholder="Masukan kota Usaha" value="{{ $franchise->kota_usaha }}">
                            <div class="text-danger">
                                @error('kota_usaha')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kelurahan_usaha">Kelurahan Usaha</label>
                            <input type="text" name="kelurahan_usaha" class="form-control" id="kelurahan_usaha" 
                                   placeholder="Masukan kelurahan Usaha" value="{{ $franchise->kelurahan_usaha }}">
                            <div class="text-danger">
                                @error('kelurahan_usaha')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kecamatan_usaha">Kecamatan Usaha</label>
                            <input type="text" name="kecamatan_usaha" class="form-control" id="kecamatan_usaha" 
                                   placeholder="Masukan kecamatan Usaha" value="{{ $franchise->kecamatan_usaha }}">
                            <div class="text-danger">
                                @error('kecamatan_usaha')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat_usaha">Alamat Usaha</label>
                            <input type="text" name="alamat_usaha" class="form-control" id="alamat_usaha" 
                                   placeholder="Masukan alamat usaha" value="{{ $franchise->alamat_usaha }}">
                            <div class="text-danger">
                                @error('alamat_usaha')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kode_pos">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control" id="kode_pos" 
                                   placeholder="Masukan kode pos" value="{{ $franchise->kode_pos }}">
                            <div class="text-danger">
                                @error('kode_pos')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="titik_koordinat">Titik Koordinat</label>
                            <input type="text" name="titik_koordinat" class="form-control" id="titik_koordinat" 
                                   placeholder="Masukan titik koordinat" value="{{ $franchise->titik_koordinat }}">
                            <div class="text-danger">
                                @error('titik_koordinat')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                        <label for="lokasi_usaha">Gambar Lokasi Usaha</label>
                        <div class="custom-file">
                            <input type="file" name="lokasi_usaha" class="form-control custom-file-input" id="lokasi_usaha" onchange="previewGambar(event)">
                        </div>
                        <div style="margin-top: 15px;">
                            <label>Preview Gambar Lama:</label><br>
                            @if($franchise->lokasi_usaha && file_exists(public_path('uploads/lokasi/' . $franchise->lokasi_usaha)))
                            <img id="preview" src="{{ asset('uploads/lokasi/' . $franchise->lokasi_usaha) }}" alt="Gambar franchise" width="150" style="border-radius: 8px;">
                        @else
                            <p class="text-muted">Gambar tidak tersedia.</p>
                        @endif

                        </div>
                        <div class="text-danger">
                            @error('lokasi_usaha')
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