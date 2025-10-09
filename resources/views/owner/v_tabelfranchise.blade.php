@section ('Title')
Tabel Franchise
@endsection

@extends('owner.v_templateowner')

@section('content')
<div class="card mt-3 shadow-sm border-0">
  <div class="card-header bg-white border-bottom">
    <h5 class="card-title mb-0">Data Franchise Aktif</h5>
  </div>
  <div class="card-body">
    <!-- Search -->
      <form action="{{ route('ownerfranchise') }}" method="GET" class="mb-3 w-full max-w-md">
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari Franchise..."
          class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-green-700 focus:outline-none transition"
        >
      </form>
    <div class="table-responsive">
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
          </tr>
        </thead>
        <tbody>
          @foreach ($owner as $data)
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
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
