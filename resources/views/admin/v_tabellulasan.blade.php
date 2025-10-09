@extends('admin.templatecoba')

@section('Title')
Tabel Ulasan
@endsection

@section('content')


{{-- Notifikasi error --}}
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mt-3 shadow-sm border-0" role="alert">
  <i class="fas fa-exclamation-circle mr-2"></i>
  <ul class="mb-0">
    @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span>&times;</span>
  </button>
</div>
@endif

<div class="card mt-3 shadow-sm border-0">
  <div class="card-header bg-white border-bottom">
    <h5 class="card-title mb-0">Data Ulasan</h5>
  </div>
  <div class="card-body">
        <!-- Search -->
      <form action="{{ route('admin.ulasan.index') }}" method="GET" class="mb-3 w-full max-w-md">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari Ulasan"
          class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:outline-none transition"
        >
      </form>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="thead-light">
          <tr class="text-center">
            <th>No</th>
            <th>ID Ulasan</th>
            <th>ID Akun</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Rating</th>
            <th>Subjek</th>
            <th>Pesan Ulasan</th>
            <th>Status Tampil</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @php $no = 1; @endphp
          @foreach($ulasan as $u)
          <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td>{{ $u->id_ulasan }}</td>
            <td>{{ $u->id_akun ?? '-' }}</td>
            <td>{{ $u->nama_lengkap }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->rating }} / 5</td>
            <td>{{ $u->subjek }}</td>
            <td>{{ $u->ulasan_pesan }}</td>
            <td class="text-center">
              @if($u->status_tampil == 1)
                <span class="badge badge-success">Tampil</span>
              @else
                <span class="badge badge-secondary">Disembunyikan</span>
              @endif
            </td>
            <td class="text-center">
              @if($u->status_tampil == 0)
              <form action="{{ url('/admin/tabelulasan/tampilkan/' . $u->id_ulasan) }}" method="POST" style="display:inline;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-outline-success mb-1">Tampilkan</button>
              </form>
              @endif

              <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#delete{{ $u->id_ulasan }}">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Delete Modals --}}
    @foreach($ulasan as $u)
    <div class="modal fade" id="delete{{ $u->id_ulasan }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $u->id_ulasan }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
          <div class="modal-body text-center py-4">
            <div class="mb-3">
              <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
            </div>
            <h5 class="mb-2">Hapus Ulasan Ini?</h5>
            <p class="text-muted">Anda yakin ingin menghapus ulasan dari <strong>{{ $u->nama_lengkap }}</strong>?</p>
            <div class="d-flex justify-content-center gap-2 mt-4">
              <form action="{{ url('/admin/tabelulasan/' . $u->id_ulasan) }}" method="POST" class="mr-2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger px-4">Hapus</button>
              </form>
              <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">Batal</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach

  </div>
</div>
@endsection
