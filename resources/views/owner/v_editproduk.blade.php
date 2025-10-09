@section ('Title')
Edit Produk
@endsection
@extends('owner.v_templateowner')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Produk</h3>
                </div>

                <form action="/produk/update/{{ $produk->id_produk }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">ID Produk</label>
                            <input type="text" name="id_produk" class="form-control" id="exampleInputEmail1" placeholder="Masukan ID Produk" value="{{ $produk->id_produk }}" readonly>
                            <div class="text-danger">
                                @error('id_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" id="exampleInputEmail1" placeholder="Masukan Nama Produk" value="{{ $produk->nama_produk }}">
                            <div class="text-danger">
                                @error('nama_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Harga</label>
                            <input type="text" name="harga" class="form-control" id="exampleInputEmail1" placeholder="Masukan Harga" value="{{ $produk->harga }}">
                            <div class="text-danger">
                                @error('harga')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">Gambar Produk</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="gambar_produk" class="form-control" id="exampleInputFile">
                                </div>
                            </div>
                            <div class="text-danger">
                                @error('gambar_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>


                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-warning">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection