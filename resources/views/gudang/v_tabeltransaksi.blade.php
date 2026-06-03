@extends('layouts.gudang')

@section('title', 'Tabel Transaksi')

@section('content')
<div class="card mt-3 shadow-lg border-0 rounded-3 overflow-hidden">
  <div class="card-header bg-gradient-green d-flex flex-wrap justify-content-between align-items-center py-3">
    <h4 class="mb-0 text-white fw-bold">📊 Data Transaksi</h4>

    <div class="btn-group shadow-sm">
      <a href="{{ route('transaksi.create') }}" class="btn btn-light text-green fw-bold">
        <i class="fas fa-plus-circle me-1"></i> Tambah Transaksi
      </a>
      <a href="{{ route('transaksi.export.excel', [
          'bulan' => request('bulan'),
          'tahun' => request('tahun'),
          'filter' => request('filter')
      ]) }}" 
      class="btn btn-success text-white fw-bold">
          <i class="fas fa-file-excel me-1"></i> Export Excel
      </a>
      <a href="{{ route('transaksi.export.pdf', [
              'bulan' => request('bulan'),
              'tahun' => request('tahun'),
              'filter' => request('filter')
          ]) }}" 
        class="btn btn-danger text-white fw-bold">
        <i class="fas fa-file-pdf me-1"></i> Export PDF
      </a>
    </div>
  </div>

  <div class="card-body p-4" style="background-color:#F3F8F2;">
    {{-- Filter Section --}}
    <div class="filter-card bg-light-green rounded-3 p-4 mb-4 shadow-sm border-start border-success border-3">
      <h5 class="mb-3 fw-bold text-dark">🔍 Filter Data</h5>
      <form method="GET" action="{{ route('gudang.transaksi.index') }}">
        <div class="row g-3 align-items-end">
          {{-- Jenis Transaksi --}}
          <div class="col-md-3">
            <label for="filter" class="form-label fw-semibold">Jenis Transaksi</label>
            <select name="filter" id="filter" class="form-select shadow-sm border-success-subtle">
              <option value="">Tampilkan Semua</option>
              <option value="pemasukan" {{ request('filter') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
              <option value="pengeluaran" {{ request('filter') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
          </div>

          {{-- Bulan --}}
          <div class="col-md-3">
            <label for="bulan" class="form-label fw-semibold">Bulan</label>
            <select name="bulan" id="bulan" class="form-select shadow-sm border-success-subtle">
              <option value="">Semua Bulan</option>
              @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                  {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Tahun --}}
          <div class="col-md-3">
            <label for="tahun" class="form-label fw-semibold">Tahun</label>
            <select name="tahun" id="tahun" class="form-select shadow-sm border-success-subtle">
              <option value="">Semua Tahun</option>
              @foreach(range(date('Y'), date('Y') - 5) as $y)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
              @endforeach
            </select>
          </div>

          {{-- Tombol Filter --}}
          <div class="col-md-3 text-end">
            <button type="submit" class="btn btn-green fw-bold shadow-sm">
              <i class="fas fa-filter me-1"></i> Terapkan Filter
            </button>
            <a href="{{ route('gudang.transaksi.index') }}" class="btn btn-outline-secondary fw-bold">
              <i class="fas fa-undo me-1"></i> Reset
            </a>
          </div>
        </div>
      </form>
    </div>

    {{-- Tabel Data --}}
    <div class="table-responsive rounded-3 shadow-sm">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light-green text-dark">
          <tr>
            <th class="ps-4 py-3 fw-semibold" style="white-space: nowrap;">No</th>
            <th class="py-3 fw-semibold" style="white-space: nowrap;">ID Transaksi</th>
            <th class="py-3 fw-semibold" style="white-space: nowrap;">Deskripsi</th>
            <th class="text-center py-3 fw-semibold" style="white-space: nowrap;">Jenis</th>
            <th class="py-3 fw-semibold" style="white-space: nowrap;">Jumlah (Rp)</th>
            <th class="py-3 fw-semibold" style="white-space: nowrap;">Keterangan</th>
            <th class="py-3 fw-semibold" style="white-space: nowrap;">Tanggal</th>
            <th class="text-center py-3 fw-semibold" style="white-space: nowrap;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @php $no = 1; @endphp
          @forelse($transaksi as $t)
          <tr class="border-bottom">
            <td class="ps-4 py-3 fw-medium" style="white-space: nowrap;">{{ $no++ }}</td>
            <td class="py-3" style="white-space: nowrap;">
              <span class="badge bg-success bg-opacity-10 text-success fw-medium">{{ $t->id_transaksi }}</span>
            </td>
            <td class="py-3" style="white-space: nowrap;">{{ $t->transaksi }}</td>
            <td class="text-center py-3"  style="white-space: nowrap;">
              <span class="badge {{ $t->jenis == 'pemasukan' ? 'bg-success' : 'bg-danger' }} px-3 py-2 fw-medium">
                {{ ucfirst($t->jenis) }}
              </span>
            </td>
            <td class="py-3 fw-bold {{ $t->jenis == 'pemasukan' ? 'text-success' : 'text-danger' }}" style="white-space: nowrap;">
              Rp {{ number_format($t->jumlah, 0, ',', '.') }}
            </td>
            <td class="py-3" style="white-space: nowrap;">{{ $t->keterangan ?? '-' }}</td>
            <td class="py-3 text-muted" style="white-space: nowrap;">
              {{ \Carbon\Carbon::parse($t->created_at)->format('d-m-Y H:i') }}
            </td>
            <td class="text-center py-3" style="white-space: nowrap;">
              <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('transaksi.edit', $t->id_transaksi) }}"
                   class="btn btn-warning btn-sm d-flex align-items-center justify-content-center rounded-lg shadow-sm"
                   style="width:32px; height:32px;">
                  <i class="fas fa-edit text-white"></i>
                </a>
                <button onclick="hapusTransaksi('{{ $t->id_transaksi }}')"
                   class="btn btn-danger btn-sm d-flex align-items-center justify-content-center rounded-lg shadow-sm"
                   style="width:32px; height:32px;">
                  <i class="fas fa-trash text-white"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr class="text-center border-bottom">
            <td colspan="8" class="py-4 text-muted fst-italic" style="white-space: nowrap;">
              <i class="fas fa-inbox me-2"></i>Tidak ada data transaksi.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<style>
  /* === Warna Utama Hijau Natural === */
  .bg-gradient-green {
    background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
  }

  .btn-green {
    background-color: #4CAF50;
    color: #fff;
    border: none;
  }
  .btn-green:hover {
    background-color: #388E3C;
    color: #fff;
  }

  .text-green {
    color: #2E7D32 !important;
  }

  .bg-light-green {
    background-color: #E8F5E9 !important;
  }

  .table-hover tbody tr:hover {
    background-color: rgba(76, 175, 80, 0.05);
  }
  
    .btn-warning {
    background-color: #FBBF24 !important;
    border: none;
  }
  .btn-warning:hover {
    background-color: #F59E0B !important;
  }

  .btn-danger {
    background-color: #EF4444 !important;
    border: none;
  }
  .btn-danger:hover {
    background-color: #DC2626 !important;
  }
</style>

<script>
  function hapusTransaksi(id) {
    if (confirm('Yakin ingin menghapus transaksi ini?')) {
      fetch(`/gudang/transaksi/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Transaksi berhasil dihapus');
          location.reload();
        } else {
          alert('Gagal menghapus transaksi: ' + data.message);
        }
      });
    }
  }
</script>
@endsection
