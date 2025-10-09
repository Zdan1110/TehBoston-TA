@extends('survey.templatecoba')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Calon Mitra untuk Survey</span>
            <span class="badge bg-success">{{ $survey->count() }} Data</span>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Calon</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th>Sumber</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($survey as $data)
                            <tr>
                                <td>{{ $data->id_calon }}</td>
                                <td>{{ $data->nama_lengkap }}</td>
                                <td>{{ $data->alamat_usaha }}</td>
                                <td>{{ $data->no_hp }}</td>
                                <td><span class="status-badge status-pending">{{ $data->status }}</span></td>
                                <td>
                                    @if ($data->sumber == 'franchisebaru')
                                        <span class="badge bg-info">Franchise Baru</span>
                                    @else
                                        <span class="badge bg-secondary">Calon Mitra</span>
                                    @endif
                                </td>

                                <td>
                                @if (is_null($data->id_survey))
                                   <a href="{{ route('survey.laporan', ['id_calon' => $data->id_calon, 'sumber' => $data->sumber]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-file-alt me-1"></i> Buat Laporan
                                    </a>

                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
           
        </div>
    </div>
@endsection