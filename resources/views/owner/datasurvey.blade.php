@extends('owner.templateowner')

@section('Title')
Tabel Calon
@endsection

@section('content')


<div class="card mt-2">
    <div class="card-header">
        <div class="card-title">Data Hasil Survey</div>
        <!-- Search -->
      <form action="{{ route('datasurvey') }}" method="GET" class="mb-3 w-full max-w-md">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari data survey..."
          class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:outline-none transition"
        >
      </form>
        
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>ID Calon</th>
                        <th>ID Akun</th>
                        <th>Nama Lengkap</th>
                        <th>Panjang</th>
                        <th>Lebar</th>
                        <th>Total Luas</th>
                        <th>Foto Lokasi</th>
                        <th>Catatan</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($survey as $data)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $data->id_calon }}</td>
                            <td>{{ $data->id_akun }}</td>
                            <td>{{ $data->nama_lengkap }}</td>
                            <td>{{ $data->panjang }}</td>
                            <td>{{ $data->lebar }}</td>
                            <td>{{ $data->total_luas }}</td>
                            <td>
                                <img src="{{ url('uploads/survey/' . $data->foto) }}"
                                    width="100px"
                                    class="zoomable-img"
                                    onclick="showModal('{{ url('uploads/survey/' . $data->foto) }}')"
                                    alt="Foto Lokasi">
                            </td>
                            <td>{{ $data->catatan }}</td>
                            <td>{{ $data->created_at ? date('d-m-Y H:i', strtotime($data->created_at)) : '-' }}</td>
                            <td>
                                @if ($data->status === 'Pembayaran')
                                    <span class="badge bg-success">Sudah di ACC</span>
                                @else
                                    <form action="{{ route('survey.acc', ['id_calon' => $data->id_calon, 'sumber' => $data->sumber ?? 'calonmitra']) }}" method="POST" onsubmit="return confirm('Konfirmasi ACC dan ubah status ke Pembayaran?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> ACC
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
    </div>
</div>

<!-- Modal Zoom Image -->
<div id="imgModal" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.8); align-items:center; justify-content:center;">
    <span onclick="closeModal()" style="position:absolute; top:20px; right:30px; color:#fff; font-size:30px; cursor:pointer;">&times;</span>
    <img id="modalImage" src="" style="max-width:90%; max-height:90%; box-shadow: 0 0 15px #fff;">
</div>

<script>
    function showModal(src) {
        document.getElementById('imgModal').style.display = 'flex';
        document.getElementById('modalImage').src = src;
    }

    function closeModal() {
        document.getElementById('imgModal').style.display = 'none';
    }

    // Optional: Close modal when clicking outside image
    document.getElementById('imgModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

@endsection
