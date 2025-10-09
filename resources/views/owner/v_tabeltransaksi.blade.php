@section('Title')
Tabel transaksi
@endsection

@extends('owner.templateowner')

@section('content')
<div class="card mt-2">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div class="card-title">Data Transaksi</div>
  </div>

  <div class="card-body">
      <div class="mb-3">
        <a href="{{ route('owner.transaksi.index', ['filter' => 'pemasukan']) }}" class="btn btn-success mr-2">Pemasukan</a>
        <a href="{{ route('owner.transaksi.index', ['filter' => 'pengeluaran']) }}" class="btn btn-danger mr-2">Pengeluaran</a>
        <a href="{{ route('owner.transaksi.index') }}" class="btn btn-secondary ml-2">Tampilkan Semua</a>
      </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @php $no = 1; @endphp
          @foreach($transaksi as $t)
          <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $t->id_transaksi }}</td>
            <td>{{ $t->tanggal_transaksi }}</td>
            <td>
                @if($t->id_pemasukan)
                    <i class="fa-solid fa-check text-green-600"></i>
                @else
                    -
                @endif
            </td>
            <td>
                @if($t->id_pengeluaran)
                    <i class="fa-solid fa-check text-green-600"></i>
                @else
                    -
                @endif
            </td>
            <td>{{ $t->keterangan }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>


    </div>
  </div>
</div>
@endsection
