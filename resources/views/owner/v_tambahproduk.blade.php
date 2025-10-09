@section ('Title')
Tambah Produk
@endsection
@extends('owner.v_templateowner')
@section('content')
<div class="container-fluid" style="margin-top:8px">
    <div class="row">
        <div class="col-md-6">

            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Data Produk</h3>
                </div>
                <!-- /.card-header -->

                <!-- form start -->
                <form action="{{ route('produk.insert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Nama Produk" value="{{ old('nama_produk') }}">
                            <div class="text-danger">
                                @error('nama_produk')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Harga Produk</label>
                            <input type="text" name="harga" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Harga Produk" value="{{ old('harga') }}">
                            <div class="text-danger">
                                @error('harga')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Gambar Produk</label>
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
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-sm btn-warning">Tambah</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->

        </div>
    </div>
</div>
@endsection