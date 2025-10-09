@extends('layouts.gudang')

@section('title', 'Daftar Struk Transaksi')

@section('content')
<style>
    .btn-success {
        background-color: var(--primary);
        border-color: var(--primary);
    }
    .btn-success:hover {
        background-color: #1B5E20;
        border-color: #1B5E20;
    }
    .card-header {
        border-bottom: 1px solid #eee;
    }
    /* 🌿 Style tambahan untuk badge jenis struk */
    .badge-jenis {
        font-size: 0.9rem;
        padding: 6px 12px;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    .badge-pemasukan {
        background-color: #2E7D32;
        color: #fff;
    }
    .badge-pengeluaran {
        background-color: #FFD54F;
        color: #000;
    }
    .badge-lain {
        background-color: #BDBDBD;
        color: #fff;
    }
</style>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Filter Struk</h5>
    </div>

    <div class="card-body">
        <form class="row g-3 align-items-end">
            {{-- 🔍 Input cari --}}
            <div class="col-md-3">
                <label for="keyword" class="form-label">Cari Struk</label>
                <input type="text" name="keyword" id="keyword" class="form-control"
                       placeholder="Masukkan ID Transaksi atau Nama Franchise"
                       value="{{ request('keyword') }}">
            </div>

            {{-- 📅 Filter bulan --}}
            <div class="col-md-3">
                <label for="monthFilter" class="form-label">Bulan</label>
                <select id="monthFilter" name="bulan" class="form-select" onchange="this.form.submit()">
                    @php $currentMonth = request('bulan', now()->month); @endphp
                    <option value="">Semua Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $currentMonth == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- 📆 Filter tahun --}}
            <div class="col-md-3">
                <label for="yearFilter" class="form-label">Tahun</label>
                <select id="yearFilter" name="tahun" class="form-select" onchange="this.form.submit()">
                    @php $currentYear = request('tahun', now()->year); @endphp
                    @for ($t = now()->year; $t >= 2022; $t--)
                        <option value="{{ $t }}" {{ $currentYear == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </div>

            {{-- 🧾 Filter jenis nota --}}
            
        </form>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th class="text-center">Jenis Struk<br><small class="text-muted">(Pemasukan / Pengeluaran Barang)</small></th>
                        <th class="text-center">File PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi as $item)
                        @php
                            $namaFileDb = $item->struk;
                            $namaFileNota = 'nota_' . $item->id_transaksi . '.pdf';
                            $pathDb = public_path('uploads/strukdc/' . $namaFileDb);
                            $pathNota = public_path('uploads/strukdc/' . $namaFileNota);
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $item->id_transaksi }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->translatedFormat('l, d F Y') }}</td>
                            <td class="text-center">
                                @if($item->jenis_transaksi === 'Pemasukan')
                                    <span class="badge badge-jenis badge-pemasukan">
                                        <i class="bi bi-arrow-down-circle me-1"></i> {{ $item->jenis_transaksi }}
                                    </span>
                                @elseif($item->jenis_transaksi === 'Pengeluaran')
                                    <span class="badge badge-jenis badge-pengeluaran">
                                        <i class="bi bi-arrow-up-circle me-1"></i> {{ $item->jenis_transaksi }}
                                    </span>
                                @else
                                    <span class="badge badge-jenis badge-lain">Lainnya</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($namaFileDb && file_exists($pathDb)) || file_exists($pathNota))
                                    <a href="{{ route('gudang.strukdc.download', $item->id_transaksi) }}" class="btn btn-danger btn-sm">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    console.log("Halaman Struk DC berhasil dimuat!");
</script>
@endsection
