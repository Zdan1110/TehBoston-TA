@extends('survey.templatecoba')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Form Buat Laporan Survey</span>
            <a href="{{ route('survey.calon') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h5>Detail Calon Mitra</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Calon:</strong> {{ $data->id_calon }}</p>
                            <p><strong>Nama Lengkap:</strong> {{ $data->nama_lengkap }}</p>
                            <p><strong>Alamat:</strong> {{ $data->alamat_usaha }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>No. HP:</strong> {{ $data->no_hp }}</p>
                            
                            <p><strong>Status:</strong> <span class="status-badge status-pending">{{ $data->status }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Jika ada foto profil atau foto lokasi, tampilkan -->
                    <img src="{{ url('uploads/survey/' . $data->lokasi_usaha) }}" alt="Foto Lokasi Usaha" class="survey-img">
                </div>
            </div>
            
            <hr>
            
            <form method="POST" action="{{ route('membuat.laporan', ['id_calon' => $data->id_calon]) }}?sumber={{ request('sumber') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_calon" value="{{ $data->id_calon }}">
                @if(request('sumber') === 'franchisebaru')
                    <input type="hidden" name="id_franchisebaru" value="{{ $data->id_franchisebaru }}">
                @endif
                <input type="hidden" name="id_akun" value="{{ auth()->user()->id_akun }}">
                <input type="hidden" name="nama_lengkap" value="{{ $data->nama_lengkap }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="panjang" class="form-label">Panjang (m)</label>
                            <input type="number" step="0.01" class="form-control" id="panjang" name="panjang" placeholder="Masukkan panjang lokasi" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="lebar" class="form-label">Lebar (m)</label>
                            <input type="number" step="0.01" class="form-control" id="lebar" name="lebar" placeholder="Masukkan lebar lokasi" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="total_luas" class="form-label">Total Luas (m²)</label>
                    <input type="text" class="form-control" id="total_luas" name="total_luas" placeholder="0" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="foto" class="form-label">Upload Foto/Dokumen Survey</label>
                    <input class="form-control" type="file" id="foto" name="foto" accept="image/*,.pdf" required>
                    <div class="form-text">Format: JPG, PNG, atau PDF. Maksimal 5MB.</div>
                </div>
                
                <div class="mb-3">
                    <label for="catatan" class="form-label">Catatan Survey</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="4" placeholder="Tulis catatan hasil survey" required></textarea>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('survey.calon') }}" class="btn btn-outline-primary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const panjang = document.getElementById('panjang');
        const lebar = document.getElementById('lebar');
        const totalLuas = document.getElementById('total_luas');

        function hitungLuas() {
            const p = parseFloat(panjang.value) || 0;
            const l = parseFloat(lebar.value) || 0;
            totalLuas.value = (p * l).toFixed(2);
        }

        panjang.addEventListener('input', hitungLuas);
        lebar.addEventListener('input', hitungLuas);
    });
</script>

@endsection