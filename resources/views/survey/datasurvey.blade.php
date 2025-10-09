@extends('survey.templatecoba')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Data Hasil Survey</span>
            <div>
                <button class="btn btn-primary">
                    <i class="fas fa-download me-1"></i> Export Data
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="GET" action="{{ route('datasurvey') }}" class="search-container">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari survey..." name="search" value="{{ request('search') }}">
                </div>
            </form>

            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Survey</th>
                            <th>Nama Lengkap</th>
                            <th>Total Luas</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Tanggal Buat</th>         {{-- Kolom Baru --}}
                            <th>Tanggal Diubah</th>       {{-- Kolom Baru --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($survey as $data)
                            <tr>
                                <td>{{ $data->id_survey }}</td>
                                <td>{{ $data->nama_lengkap }}</td>
                                <td>{{ $data->total_luas }} m²</td>
                                <td>
                                    <a href="{{ asset('uploads/survey/' . $data->foto) }}" target="_blank" class="text-primary">
                                        @if (pathinfo($data->foto, PATHINFO_EXTENSION) === 'pdf')
                                            <i class="fas fa-file-pdf me-1"></i> survey.pdf
                                        @else
                                            <i class="fas fa-file-image me-1"></i> survey.{{ pathinfo($data->foto, PATHINFO_EXTENSION) }}
                                        @endif
                                    </a>
                                </td>
                                <td><span class="status-badge status-completed">Selesai</span></td>
                                
                                {{-- Kolom Baru --}}
                                <td>{{ $data->created_at ? date('d-m-Y H:i', strtotime($data->created_at)) : '-' }}</td>
                                <td>{{ $data->updated_at ? date('d-m-Y H:i', strtotime($data->updated_at)) : '-' }}</td>                   
                                <td>
                                    <a href="{{ route('survey.edit', $data->id_survey) }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('survey.destroy', $data->id_survey) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin menghapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            
            <nav aria-label="Page navigation">
                {{ $survey->links() }}
            </nav>
        </div>
    </div>
@endsection