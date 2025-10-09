@extends('kasir.template_kasir')

@section('title', 'Edit Stok Bahan Baku')
@section('page-title', 'Edit Stok Bahan Baku')

@section('content')

<div class="bg-white rounded-xl shadow-md p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Stok Bahan Baku</h2>

    <form action="/updatestok/{{ $stokfranchise->id_stokfranchise }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="nama_bahan" class="block text-sm font-medium text-gray-700 mb-2">Nama Bahan</label>
                <input type="text" id="nama_bahan" value="{{ $stokfranchise->nama_bahan }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    disabled>
            </div>
            
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" id="stok" name="stok" value="{{ $stokfranchise->stok }}" min="0" step="0.01"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('stok') border-red-500 @enderror" 
                    required>
                @error('stok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                <input type="text" id="satuan" value="{{ $stokfranchise->satuan }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    disabled>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 mt-8">
            <a href="/stokbahan" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection