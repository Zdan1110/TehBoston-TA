@section ('Title')
Tabel Akun
@endsection
@extends('owner.v_templateowner')
@section('content')
<div class="card" style="margin-top:8px">
                  <div class="card-header">
                    <div class="card-title">Data Akun</div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>ID_Akun</th>
                            <th>Nama Lengkap</th>
                            <th>Nomor HP</th>
                            <th>Username</th>
                            <th>Tipe Akun</th>
                            <!-- <th>Action</th> -->
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1; ?>
                          @foreach ($owner as $data)
                          <tr>
                              <td>{{ $no++ }}</td>
                              <td>{{ $data->id_akun }}</td>
                              <td>{{ $data->nama }}</td>
                              <td>{{ $data->no_hp }}</td>
                              <td>{{ $data->username }}</td>
                              <td>
                                <form action="{{ route('owner.updatetipe', $data->id_akun) }}" method="POST">
                                  @csrf
                                  @method('PUT')
                                  <select name="type_akun" onchange="this.form.submit()" class="form-control form-control-sm">
                                    <option value="admin" {{ $data->type_akun == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="survey" {{ $data->type_akun == 'survey' ? 'selected' : '' }}>Survey</option>
                                    <option value="gudang" {{ $data->type_akun == 'gudang' ? 'selected' : '' }}>Owner DC</option>
                                    <option value="kasirdc" {{ $data->type_akun == 'kasirdc' ? 'selected' : '' }}>Kasir DC</option>
                                    <option value="user" {{ $data->type_akun == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="auditor" {{ $data->type_akun == 'auditor' ? 'selected' : '' }}>Auditor</option>
                                  </select>
                                </form>
                              </td>
                              <!-- <td>
                                  <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{ $data->id_akun }}">
                                    Delete
                                  </button>
                              </td> -->
                          </tr>
                          @endforeach
                      </tbody>
                      </table>
                      @foreach ($owner as $data)
                      <div class="modal fade" id="delete{{ $data->id_akun }}">
                          <div class="modal-dialog modal-sm">
                              <div class="modal-content bg-danger">
                                  <div class="modal-header">
                                      <h6 class="modal-title">Username : {{ $data->username }}</h6>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                    <p>Apakah anda yakin ingin menghapus akun ini ?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <form action="{{ route('akunowner.delete', $data->id_akun) }}" method="POST">
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