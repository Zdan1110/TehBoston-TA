@section ('Title')
Laporan
@endsection

@extends('kasir.template_kasir')

@section('content')

<style>
  .main {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
  }

  .filter-container {
    background: #f8f9fa;
    border: 1px solid #ddd;
    padding: 16px;
    border-radius: 8px;
    max-width: 800px;
    margin-bottom: 20px;
  }

  .filter-row {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .filter-row label {
    font-weight: 600;
    color: #333;
  }

  .filter-row select,
  .filter-row input {
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    background-color: #fff;
    transition: 0.2s;
  }

  .filter-row select:hover,
  .filter-row input:hover {
    border-color: #007bff;
  }

  .filter-row button {
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 7px 14px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
  }

  .filter-row button:hover {
    background: #0056b3;
  }

  .reset-btn {
    background: #6c757d;
  }

  .reset-btn:hover {
    background: #5a6268;
  }

  .cetak-pdf {
    margin-bottom: 15px;
  }

  .cetak-pdf button {
    background-color: #ffc107;
    border: none;
    color: #000;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.2s;
  }

  .cetak-pdf button:hover {
    background-color: #e0a800;
  }

  .history-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }

  .history-table th,
  .history-table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
  }

  .history-table th {
    background-color: #f9f9f9;
  }
</style>

<div class="main">

  {{-- 🔹 FORM FILTER --}}
  <form id="filterForm" method="GET" action="{{ url('/pelaporan') }}">
    <div class="filter-container">
      <div class="filter-row">
        <label for="tipe_filter">Tipe Filter:</label>
        <select id="tipe_filter" name="tipe_filter" onchange="ubahFilter()">
          <option value="">-- Pilih Tipe --</option>
          <option value="harian" {{ request('tipe_filter') == 'harian' ? 'selected' : '' }}>Per Hari</option>
          <option value="mingguan" {{ request('tipe_filter') == 'mingguan' ? 'selected' : '' }}>Per Minggu</option>
          <option value="bulanan" {{ request('tipe_filter') == 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
          <option value="tahunan" {{ request('tipe_filter') == 'tahunan' ? 'selected' : '' }}>Per Tahun</option>
        </select>

        <!-- input dinamis -->
        <div id="filter_input_area" style="display:flex; gap:10px;"></div>

        <button type="submit">Filter</button>
        <a href="{{ url('/pelaporan') }}" class="reset-btn" style="text-decoration:none; padding:7px 14px; border-radius:6px; color:white;">Reset</a>
      </div>
    </div>
  </form>

  {{-- 🔹 TOMBOL CETAK PDF --}}
  <div class="cetak-pdf">
    <button type="button" onclick="cetakPDF()">Cetak PDF</button>
  </div>

  {{-- 🔹 TABEL --}}
  @if(!empty($penjualan) && count($penjualan) > 0)
    <table class="history-table">
      <thead>
        <tr>
          <th>No</th>
          <th>Menu (Jumlah terjual)</th>
          <th>Total Harga</th>
          <th>Waktu</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @php $no = 1; @endphp
        @foreach($penjualan as $item)
          <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item->nama_produk }}</td>
            <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y H:i') }}</td>
            <td>
              @if($type_akun !== 'kasir')
                <a href="{{ url('/pelaporan/delete/' . $item->id_penjualan) }}" 
                   class="btn btn-sm btn-outline-danger" 
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                   <i class="fas fa-trash-alt"></i>
                </a>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>Tidak ada data untuk filter yang dipilih.</p>
  @endif
</div>

 <script>
  function ubahFilter() {
    const tipe = document.getElementById('tipe_filter').value;
    const area = document.getElementById('filter_input_area');
    area.innerHTML = '';

    if (tipe === 'harian') {
      area.innerHTML = `<input type="date" name="tanggal" value="{{ request('tanggal') }}">`;
    } else if (tipe === 'mingguan') {
      area.innerHTML = `<input type="week" name="minggu" value="{{ request('minggu') }}">`;
    } else if (tipe === 'bulanan') {
      area.innerHTML = `
        <select name="bulan">
          <option value="">-- Bulan --</option>
          @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
              {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
            </option>
          @endfor
        </select>
        <select name="tahun">
          <option value="">-- Tahun --</option>
          @for ($thn = date('Y'); $thn >= 2020; $thn--)
            <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
              {{ $thn }}
            </option>
          @endfor
        </select>
      `;
    } else if (tipe === 'tahunan') {
      area.innerHTML = `
        <select name="tahun">
          <option value="">-- Tahun --</option>
          @for ($thn = date('Y'); $thn >= 2020; $thn--)
            <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
              {{ $thn }}
            </option>
          @endfor
        </select>
      `;
    }
  }

  document.addEventListener('DOMContentLoaded', ubahFilter);
</script>

@endsection
