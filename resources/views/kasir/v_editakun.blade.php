@extends('kasir.template_kasir')

@section('title', 'Edit Akun')
@section('page-title', 'Edit Akun')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    @if (session('pesan'))
        <div class="alert bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
            <p>{{ session('pesan') }}</p>
        </div>
    @endif

    <form action="{{ route('updateakunkasir', $akun->id_akun) }}" method="POST" class="max-w-md mx-auto">
        @csrf
        {{-- cukup pakai POST, tidak perlu @method('POST') --}}

        <div class="mb-6">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
            <input type="text" name="username" id="username"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('username') border-red-500 @enderror"
                value="{{ old('username', $akun->username) }}" required>
            @error('username')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password Baru</label>
            <input type="password" name="password" id="password"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                placeholder="Masukkan password baru">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-2">Biarkan kosong jika tidak ingin mengubah password</p>
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
