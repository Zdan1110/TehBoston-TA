@extends('layouts.gudang')

@section('title', 'Edit Akun')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="/gudang">Gudang</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit Akun</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Edit Akun</h6>
    </nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5>Edit Akun Gudang</h5>
            </div>
            <div class="card-body">
                @if (session('pesan'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('pesan') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('updateakun', $akun->id_akun) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $akun->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah password</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" style="background-color: green">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
