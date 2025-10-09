@section ('Title')
Tabel Calon
@endsection
@extends('owner.v_templateowner')
@section('content')
<div class="card" style="margin-top:8px">
                  <div class="card-header">
                    <div class="card-title">Data Calon Mitra</div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>ID Calon</th>
                            <th>ID Akun</th>
                            <th>Nama Lengkap</th>
                            <th>Nomor KTP</th>
                            <th>Provinsi</th>
                            <th>Kota</th>
                            <th>Kelurahan</th>
                            <th>Foto KTP</th>
                            <th>Nomor HP</th>
                            <th>Alamat Lengkap</th>
                            <th>Provinsi Usaha</th>
                            <th>Kota Usaha</th>
                            <th>Kelurahan Usaha</th>
                            <th>Kecamatan Usaha</th>
                            <th>Alamat Usaha</th>
                            <th>Kode Pos</th>
                            <th>Titik Koordinat</th>
                            <th>Foto Lokasi Usaha</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1; ?>
                          @foreach ($owner as $data)
                          <tr>
                              <td>{{ $no++ }}</td>
                              <td>{{ $data->id_calon }}</td>
                              <td>{{ $data->id_akun }}</td>
                              <td>{{ $data->nama_lengkap }}</td>
                              <td>{{ $data->no_ktp }}</td>
                              <td>{{ $data->provinsi }}</td>
                              <td>{{ $data->kota }}</td>
                              <td>{{ $data->kelurahan }}</td>
                              <td><img src="{{ url('uploads/ktp/'. $data->ktp) }}" width="100px"></td>
                              <td>{{ $data->no_hp }}</td>
                              <td>{{ $data->alamat_lengkap }}</td>
                              <td>{{ $data->provinsi_usaha }}</td>
                              <td>{{ $data->kota_usaha }}</td>
                              <td>{{ $data->kelurahan_usaha }}</td>
                              <td>{{ $data->kecamatan_usaha }}</td>
                              <td>{{ $data->alamat_usaha }}</td>
                              <td>{{ $data->kode_pos }}</td>
                              <td>{{ $data->titik_koordinat }}</td>
                              <td><img src="{{ url('uploads/lokasi/'. $data->lokasi_usaha) }}" width="100px"></td>
                              
                              <td>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete{{ $data->id_calon }}">
                                    Delete
                                </button>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                      </table>
                      @foreach ($owner as $data)
                      <div class="modal fade" id="delete{{ $data->id_calon }}">
                          <div class="modal-dialog modal-sm">
                              <div class="modal-content bg-danger">
                                  <div class="modal-header">
                                      <h6 class="modal-title">Nama Lengkap : {{ $data->nama_lengkap }}</h6>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                    <p>Apakah anda ingin menghapus data ini?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <form action="{{ route('C_owner.delete', $data->id_calon) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-light">Yes</button>
                                    </form>
                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">No</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                    </div>
                  </div>
                </div>
@endsection