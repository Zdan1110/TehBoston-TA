@section ('Title')
Tabel Franchise
@endsection

@extends('admin.templatecoba')

@section('content')
<div class="card mt-3 shadow-sm border-0">
  <div class="card-header bg-white border-bottom">
    <h5 class="card-title mb-0">Data Akun Franchise</h5>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <!-- Search -->
      <form action="{{ route('adminakun') }}" method="GET" class="mb-3 w-full max-w-md">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari akun..."
          class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:outline-none transition"
        >
      </form>
      <table class="table table-bordered table-hover align-middle">
        <thead class="thead-light">
          <tr class="text-center">
            <th>No</th>
            <th>ID Akun</th>
            <th>Nama Lengkap</th>
            <th>Nomor HP</th>
            <th>Username</th>
            <th>Tipe Akun</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          @foreach ($admin as $data)
          <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td>{{ $data->id_akun }}</td>
            <td>{{ $data->nama }}</td>
            <td>{{ $data->no_hp }}</td>
            <td>{{ $data->username }}</td>
            <td>{{ $data->type_akun }}</td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#delete{{ $data->id_akun }}">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Delete Modals --}}
    @foreach ($admin as $data)
    <div class="modal fade" id="delete{{ $data->id_akun }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $data->id_akun }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
          <div class="modal-body text-center py-4">
            <div class="mb-3">
              <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
            </div>
            <h5 class="mb-2">Hapus Akun Ini?</h5>
            <p class="text-muted">Anda yakin ingin menghapus akun <strong>{{ $data->username }}</strong>?</p>
            <div class="d-flex justify-content-center gap-2 mt-4">
              <form action="{{ url('/admin/akun/' . $data->id_akun) }}" method="POST" class="mr-2">
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
