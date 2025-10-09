@section ('Title')
Tabel Franchise baru
@endsection
@extends('admin.templatecoba')
@section('content')
<div class="card">
                  <div class="card-header">
                    <div class="card-title">Data Franchise Baru</div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>ID Franchise Baru</th>
                            <th>Nama Franchise</th>
                            <th>Provinsi Usaha</th>
                            <th>Kota Usaha</th>
                            <th>Kelurahan Usaha</th>
                            <th>Kecamatan Usaha</th>
                            <th>Alamat Usaha</th>
                            <th>Kode Pos</th>
                            <th>Titik Koordinat</th>
                            <th>Foto Lokasi Usaha</th>
                            <th>Via Pembayaran</th>
                            <th>Bukti Pembayaran</th>
                            <th>Status</th>          
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1; ?>
                          @foreach ($admin as $data)
                          <tr>
                              <td>{{ $no++ }}</td>
                              <td>{{ $data->id_franchisebaru }}</td>
                              <td>{{ $data->nama_franchise }}</td>
                              <td>{{ $data->provinsi_usaha }}</td>
                              <td>{{ $data->kota_usaha }}</td>
                              <td>{{ $data->kelurahan_usaha }}</td>
                              <td>{{ $data->kecamatan_usaha }}</td>
                              <td>{{ $data->alamat_usaha }}</td>
                              <td>{{ $data->kode_pos }}</td>
                              <td>
                                  <a href="{{ $data->titik_koordinat }}" target="_blank">
                                    {{ $data->titik_koordinat }}
                                  </a>
                                </td>
                              <td><img src="{{ url('uploads/lokasi/'. $data->lokasi_usaha) }}" width="100px"></td>
                              <td>{{ $data->via_pembayaran }}</td>
                              <td><img src="{{ url('uploads/bukti/' . $data->bukti) }}" width="100px" style="cursor:pointer" onclick="showImageModal('{{ url('uploads/bukti/' . $data->bukti) }}')"></td>
                              <td>
                                <form action="/admin/franchisebaru/update-status/{{ $data->id_franchisebaru }}" method="POST">
                                  @csrf
                                  @method('PUT')
                                  <select name="status" onchange="this.form.submit()" class="form-control form-control-sm">
                                    <option value="Review Dokumen" {{ $data->status == 'Review Dokumen' ? 'selected' : '' }}>Review Dokumen</option>
                                    <option value="Survey Lokasi" {{ $data->status == 'Survey Lokasi' ? 'selected' : '' }}>Survey Lokasi</option>
                                    <option value="Pembayaran" {{ $data->status == 'Pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                                    <option value="Pembuatan Booth" {{ $data->status == 'Pembuatan Booth' ? 'selected' : '' }}>Pembuatan Booth</option>
                                    <option value="Diterima" {{ $data->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="Ditolak" {{ $data->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                  </select>
                                </form>
                              </td>
                            
                          </tr>
                          @endforeach
                      </tbody>
                      </table>
                      @foreach ($admin as $data)
                      <div class="modal fade" id="delete{{ $data->id_franchisebaru }}">
                          <div class="modal-dialog modal-sm">
                              <div class="modal-content bg-danger">
                                  <div class="modal-header">
                                      <h6 class="modal-title">Nama Franchise :  {{ $data->nama_franchise }}</h6>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                    <p>Apakah anda ingin menghapus data ini?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <form action="/admin/franchisebaru/{{ $data->id_franchisebaru }}" method="POST">
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