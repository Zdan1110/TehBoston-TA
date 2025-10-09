@extends('survey.templatecoba')

@section('content')
<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Profil Saya</h5>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            @if($user->foto_profile && file_exists(public_path('uploads/survey/profile/' . $user->foto_profile)))
                                <img src="{{ asset('uploads/survey/profile/' . $user->foto_profile) }}" alt="Foto Profil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/150" alt="Foto Profil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @endif

                        </div>
                        <div class="mb-3">
                            <input type="file" class="form-control" id="foto_profile" name="foto_profile">
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_akun" class="form-label">ID Akun</label>
                                <input type="text" class="form-control" id="id_akun" value="{{ $user->id_akun }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="type_akun" class="form-label">Tipe Akun</label>
                                <input type="text" class="form-control" id="type_akun" value="{{ $user->type_akun }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Daftar</label>
                                <input type="text" class="form-control" value="{{ $user->created_at->format('d F Y H:i') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Terakhir Diperbarui</label>
                                <input type="text" class="form-control" value="{{ $user->updated_at->format('d F Y H:i') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection