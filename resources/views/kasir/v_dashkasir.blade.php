@extends('kasir.template_kasir')

@section('title', 'Dashboard Kasir')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($pendapatanperhari->total_pendapatan ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-green-600">
                    
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Pendapatan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($pendapatanbulanini->total_pendapatan ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-red-600">
                    
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Pelanggan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $jumlahpelangganperhari->total_transaksi ?? 0 }} Pelanggan</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-blue-600">
                    
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ url('/dashkasir') }}" class="flex justify-end mb-6">
    <div class="flex items-end gap-2">
        <div>
            <label for="bulan_awal" class="block text-xs font-medium text-gray-700">Bulan Awal</label>
            <input type="month" name="bulan_awal" id="bulan_awal" 
                   value="{{ request('bulan_awal') }}"
                   placeholder="Pilih bulan"
                   class="mt-1 block w-36 rounded-md border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
        </div>
        <div>
            <label for="bulan_akhir" class="block text-xs font-medium text-gray-700">Bulan Akhir</label>
            <input type="month" name="bulan_akhir" id="bulan_akhir" 
                   value="{{ request('bulan_akhir') }}"
                   placeholder="Pilih bulan"
                   class="mt-1 block w-36 rounded-md border-gray-300 text-sm shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
        </div>
        <div>
            <button type="submit" 
                    class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
        </div>
    </div>
</form>

    <div class="card card-round mb-3">
        <div class="card-header">
            <h5 class="card-title">Grafik Penjualan Bulanan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- Tambahkan wrapper responsive --}}
                <div style="overflow-x: auto;">
                    <div style="min-width: 300px; width: 100%;">
                        {!! $chart->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Histori Transaksi Terakhir</h3>
                    <a href="/pelaporan" class="text-sm text-green-600 hover:text-green-800 font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    @if(!empty($penjualan) && count($penjualan) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelanggan</th> {{-- <-- ADDED THIS HEADER --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu (Jumlah terjual)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Item</th> {{-- Changed from 'Total Harga' as this is per item detail --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Transaksi</th> {{-- Changed from 'Waktu' for clarity --}}
                                    {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $no = 1; @endphp
                                @foreach($penjualan as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $no++ }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->pelanggan }}</td> {{-- <-- ADDED THIS CELL --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->nama_produk }} ({{ $item->jumlah }})</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y H:i') }}</td>
                                        {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button type="button" class="text-red-600 hover:text-red-900" onclick="alert('Fitur hapus belum diimplementasikan.')">Hapus</button>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="px-6 py-4 text-gray-600 text-center">Tidak ada data transaksi untuk ditampilkan.</p>
                    @endif
                </div>
                {{-- Pagination (if you implement it for $penjualan) --}}
                {{-- <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-right">
                    <nav class="inline-flex">
                        <a href="#" class="px-3 py-1 rounded-md bg-green-600 text-white font-medium">1</a>
                        <a href="#" class="ml-1 px-3 py-1 rounded-md bg-white text-gray-700 hover:bg-gray-100">2</a>
                        <a href="#" class="ml-1 px-3 py-1 rounded-md bg-white text-gray-700 hover:bg-gray-100">3</a>
                        <a href="#" class="ml-1 px-3 py-1 rounded-md bg-white text-gray-700 hover:bg-gray-100">Next</a>
                    </nav>
                </div> --}}
            </div>
        </div>

        <div class="lg:col-span-1">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Produk Terlaris</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse ($produkTerjual as $produk)
                <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <div>
                        <p class="font-medium text-gray-900">{{ $produk->nama_produk }}</p>
                        <p class="text-sm text-gray-500">Terjual</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $produk->total_terjual }}</span>
                </div>
            @empty
                <div class="px-6 py-4 text-center text-sm text-gray-500">
                    Belum ada data penjualan.
                </div>
            @endforelse
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
{{ $chart->script() }}
@endsection