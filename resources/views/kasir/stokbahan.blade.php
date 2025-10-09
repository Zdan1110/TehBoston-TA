@extends('kasir.template_kasir')

@section('title', 'Stok Bahan Baku')
@section('page-title', 'Stok Bahan Baku')

@section('content')

<div class="bg-white rounded-xl shadow-md p-4 sm:p-6 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Stok Bahan Baku</h2>
        </div>

        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            @if($type_akun !== 'kasir')
                <button id="openAddModal" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors w-full sm:w-auto">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Stok</span>
                </button>
            @endif
            
            <div class="relative w-full sm:w-auto">
                <input type="text" placeholder="Cari bahan..." id="searchBox" class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent w-full">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold text-green-700 mt-2 mb-4">
        Franchise: {{ $franchise->nama_franchise }}
    </h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow mb-6 hidden md:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        @if($type_akun !== 'kasir')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($stok as $item)
                        <tr class="hover:bg-gray-50 data-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 {{ $item->jenis_bahan === 'sirup' ? 'bg-blue-100' : 'bg-green-100' }} rounded-lg flex items-center justify-center">
                                        <i class="{{ $item->jenis_bahan === 'sirup' ? 'fas fa-wine-bottle text-blue-600' : 'fas fa-box text-green-600' }}"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_bahan }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $item->id_bahanbaku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->jenis_bahan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ number_format($item->stok) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item->satuan }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $bg = 'bg-green-100 text-green-800';
                                    $dot = 'bg-green-500';
                                    $status = 'Aman';
                                    if ($item->stok < 10) {
                                        $bg = 'bg-red-100 text-red-800';
                                        $dot = 'bg-red-500';
                                        $status = 'Rendah';
                                    } elseif ($item->stok < 25) {
                                        $bg = 'bg-yellow-100 text-yellow-800';
                                        $dot = 'bg-yellow-500';
                                        $status = 'Sedang';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bg }}">
                                    <span class="w-2 h-2 rounded-full {{ $dot }} mr-1"></span>
                                    {{ $status }}
                                </span>
                            </td>
                            @if($type_akun !== 'kasir')
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="/editstok/{{ $item->id_stokfranchise }}" 
                                        class="text-blue-600 hover:text-blue-900 p-2 hover:bg-gray-100 rounded-full">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('stokbahan.destroy', $item->id_stokfranchise) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-2 hover:bg-gray-100 rounded-full">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-4 italic">Tidak ada stok untuk franchise ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:hidden">
            @forelse ($stok as $item)
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow data-row">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                             <div class="flex-shrink-0 h-10 w-10 {{ $item->jenis_bahan === 'sirup' ? 'bg-blue-100' : 'bg-green-100' }} rounded-lg flex items-center justify-center">
                                <i class="{{ $item->jenis_bahan === 'sirup' ? 'fas fa-wine-bottle text-blue-600' : 'fas fa-box text-green-600' }}"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900">{{ $item->nama_bahan }}</p>
                                <p class="text-xs text-gray-500">ID: {{ $item->id_bahanbaku }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-1">
                            <a href="/editstok/{{ $item->id_stokfranchise }}" 
                                class="text-blue-600 hover:text-blue-900 p-2 hover:bg-gray-100 rounded-full">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('stokbahan.destroy', $item->id_stokfranchise) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 hover:bg-gray-100 rounded-full">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kategori:</span>
                            <span class="font-medium text-gray-800">{{ $item->jenis_bahan }}</span>
                        </div>
                         <div class="flex justify-between">
                            <span class="text-gray-500">Stok:</span>
                            <span class="font-bold text-lg text-green-700">{{ number_format($item->stok) }} <span class="text-sm font-normal text-gray-500">{{ $item->satuan }}</span></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status:</span>
                            @php
                                $bg = 'bg-green-100 text-green-800';
                                $dot = 'bg-green-500';
                                $status = 'Aman';
                                if ($item->stok < 10) {
                                    $bg = 'bg-red-100 text-red-800';
                                    $dot = 'bg-red-500';
                                    $status = 'Rendah';
                                } elseif ($item->stok < 25) {
                                    $bg = 'bg-yellow-100 text-yellow-800';
                                    $dot = 'bg-yellow-500';
                                    $status = 'Sedang';
                                }
                            @endphp
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bg }}">
                                <span class="w-2 h-2 rounded-full {{ $dot }} mr-1"></span>
                                {{ $status }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 sm:col-span-2 text-center text-gray-500 py-4 italic">
                    Tidak ada stok untuk franchise ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
        <p class="text-gray-500 mb-6">Apakah Anda yakin ingin menghapus bahan baku ini?</p>
        <div class="flex justify-end space-x-3">
            <button id="cancelDelete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                Hapus
            </button>
        </div>
    </div>
</div>

<div id="addModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Tambah Stok Bahan Baku</h3>
            <button id="closeAddModal" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addForm" action="/tambahstok" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nama_bahan" class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan</label>
                <select id="nama_bahan" name="id_bahanbaku" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                    required>
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($bahanbaku as $bahan)
                        <option value="{{ $bahan->id_bahanbaku }}">{{ $bahan->nama_bahan }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok</label>
                <input type="number" id="stok" name="stok" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelAdd" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchBox = document.querySelector('#searchBox');
        // Target semua baris data, baik di tabel maupun di kartu
        const dataRows = document.querySelectorAll('.data-row'); 
        const deleteForms = document.querySelectorAll('.delete-form');
        const deleteModal = document.getElementById('deleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        
        // Modal tambah stok
        const addModal = document.getElementById('addModal');
        const openAddModalBtn = document.getElementById('openAddModal');
        const closeAddModalBtn = document.getElementById('closeAddModal');
        const cancelAddBtn = document.getElementById('cancelAdd');
        const addForm = document.getElementById('addForm');
        
        let formToSubmit = null;

        // Fungsi pencarian yang sudah diperbarui
        searchBox.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();

            dataRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                // Gunakan class 'hidden' dari Tailwind untuk menyembunyikan
                if (rowText.includes(searchTerm)) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        });

        // Fungsi konfirmasi hapus (tidak ada perubahan logika)
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                formToSubmit = this;
                deleteModal.classList.remove('hidden');
            });
        });

        cancelDeleteBtn.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
            formToSubmit = null;
        });

        confirmDeleteBtn.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        // Fungsi modal tambah stok (tidak ada perubahan logika)
        openAddModalBtn.addEventListener('click', function() {
            addModal.classList.remove('hidden');
        });

        closeAddModalBtn.addEventListener('click', function() {
            addModal.classList.add('hidden');
        });

        cancelAddBtn.addEventListener('click', function() {
            addModal.classList.add('hidden');
        });

        // Tutup modal jika klik di luar area modal (tidak ada perubahan logika)
        window.addEventListener('click', function(e) {
            if (e.target === addModal) {
                addModal.classList.add('hidden');
            }
            if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    });
</script>

@endsection