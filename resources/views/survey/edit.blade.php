@extends('survey.templatecoba')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Edit Laporan Survey</span>
            <a href="{{ route('datasurvey') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('survey.update', $survey->id_survey) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $survey->nama_lengkap) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="panjang" class="form-label">Panjang (m)</label>
                            <input type="number" step="0.01" class="form-control" id="panjang" name="panjang" value="{{ old('panjang', $survey->panjang) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="lebar" class="form-label">Lebar (m)</label>
                            <input type="number" step="0.01" class="form-control" id="lebar" name="lebar" value="{{ old('lebar', $survey->lebar) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="total_luas" class="form-label">Total Luas (m²)</label>
                            <input type="text" class="form-control" id="total_luas" name="total_luas" value="{{ old('total_luas', $survey->total_luas) }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan Survey</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="6" required>{{ old('catatan', $survey->catatan) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Upload Ulang Foto/Dokumen Survey (Opsional)</label>
                            <input class="form-control" type="file" id="foto" name="foto" accept="image/*,.pdf">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengganti. Maks 5MB.</div>

                            @if ($survey->foto)
                                <div class="mt-2">
                                    <strong>Dokumen saat ini:</strong><br>
                                    <a href="{{ asset('uploads/survey/' . $survey->foto) }}" target="_blank">
                                        @if (pathinfo($survey->foto, PATHINFO_EXTENSION) === 'pdf')
                                            <i class="fas fa-file-pdf text-danger"></i> Lihat PDF
                                        @else
                                            <img src="{{ asset('uploads/survey/' . $survey->foto) }}" alt="Foto Survey" style="max-height: 100px;">
                                        @endif
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('datasurvey') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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

            hitungLuas(); // Hitung langsung saat load jika nilai sudah ada
        });
    </script>
@endsection
