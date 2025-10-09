@section ('Title')
Tabel Franchise
@endsection

@extends('admin.templatecoba')

@section('content')
<div class="card mt-3 shadow-sm border-0">
  <div class="card-header bg-white border-bottom">
    <h5 class="card-title mb-0">Data Franchise Aktif</h5>
  </div>
  <div class="card-body">
    <!-- Search -->
    <form action="{{ route('adminfranchise') }}" method="GET" class="mb-3 w-full max-w-md">
      <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari Franchise..."
        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:outline-none transition"
      >
    </form>

    <!-- Table Wrapper with Horizontal Scroll -->
    <div class="table-responsive" style="overflow-x: auto;">
      <table class="table table-bordered table-hover align-middle">
        <thead class="thead-light">
          <tr class="text-center">
            <th>ID Franchise</th>
            <th>Nama Franchise</th>
            <th>Provinsi</th>
            <th>Kota</th>
            <th>Kelurahan</th>
            <th>Kecamatan</th>
            <th>Alamat Usaha</th>
            <th>Kode Pos</th>
            <th>Titik Koordinat</th>
            <th>Foto Lokasi Usaha</th>
            <th>Berlangganan Kasir</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($admin as $data)
          <tr>
            <td>{{ $data->id_franchise }}</td>
            <td>{{ $data->nama_franchise }}</td>
            <td>{{ $data->provinsi_usaha }}</td>
            <td>{{ $data->kota_usaha }}</td>
            <td>{{ $data->kelurahan_usaha }}</td>
            <td>{{ $data->kecamatan_usaha }}</td>
            <td>{{ $data->alamat_usaha }}</td>
            <td>{{ $data->kode_pos }}</td>
            <td>
              <a href="{{ $data->titik_koordinat }}" target="_blank">{{ $data->titik_koordinat }}</a>
            </td>
            <td>
              <img src="{{ url('uploads/lokasi/'. $data->lokasi_usaha) }}" width="100px" class="img-thumbnail">
            </td>
            <td>
                <form action="/admin/franchise/update-akses/{{ $data->id_franchise }}" method="POST">
                  @csrf
                  @method('PUT')
                  <select name="akses" onchange="this.form.submit()" class="form-control form-control-sm border border-black">
                    <option value="Berlangganan" {{ $data->langganan == 'Berlangganan' ? 'selected' : '' }}>Berlangganan</option>
                    <option value="Tidak Berlangganan" {{ $data->langganan == 'Tidak Berlangganan' ? 'selected' : '' }}>Tidak Berlangganan</option>
                  </select>
                </form>
           
            <td>
              <a href="/admin/franchise/edit/{{ $data->id_franchise }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('franchiseadmin.delete', $data->id_franchise) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus franchise ini?')">
                  Delete
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
