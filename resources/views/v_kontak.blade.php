@extends('layouts.app')

@section('title', 'Kontak & Ulasan - Teh Boston')

@section('content')
<section class="section">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Kontak & Beri Ulasan</h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form" data-aos="fade-up">
                    {{-- Form Method POST dan Action ke route yang telah didefinisikan --}}
                    <form action="{{ route('kontak.storeUlasan') }}" method="POST">
                        @csrf {{-- CSRF token untuk keamanan Laravel --}}

                        {{-- Menampilkan pesan sukses atau error --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="name" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="{{ old('nama_lengkap') }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Masukkan email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Rating Produk</label>
                            <div class="rating-group @error('rating') is-invalid @enderror">
                                <input type="radio" id="star5" name="rating" value="5" {{ old('rating') == '5' ? 'checked' : '' }} />
                                <label for="star5" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == '4' ? 'checked' : '' }} />
                                <label for="star4" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == '3' ? 'checked' : '' }} />
                                <label for="star3" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == '2' ? 'checked' : '' }} />
                                <label for="star2" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == '1' ? 'checked' : '' }} required />
                                <label for="star1" class="rating-star"><i class="fas fa-star"></i></label>
                            </div>
                            @error('rating')
                                <div class="text-danger mt-1" style="font-size: 0.875em;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control @error('subjek') is-invalid @enderror" id="subject" name="subjek" placeholder="Masukkan subjek" value="{{ old('subjek') }}" required>
                            @error('subjek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label">Ulasan/Pesan</label>
                            <textarea class="form-control @error('ulasan_pesan') is-invalid @enderror" id="message" name="ulasan_pesan" rows="5" placeholder="Tulis ulasan atau pesan Anda" required>{{ old('ulasan_pesan') }}</textarea>
                            @error('ulasan_pesan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">Kirim Ulasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Pastikan style ini ada di public/css/style.css, bukan di sini. --}}
{{-- Jika tetap ingin di sini, pertahankan. Tapi rekomendasi: pindahkan ke style.css --}}
<style>
    .rating-group {
        display: flex;
        flex-direction: row-reverse; /* Untuk menampilkan bintang dari kanan ke kiri */
        justify-content: flex-end; /* Untuk rata kiri */
    }
    .rating-group input {
        display: none; /* Sembunyikan radio button asli */
    }
    .rating-group label {
        color: #ccc; /* Warna default bintang */
        cursor: pointer;
        font-size: 1.5rem; /* Ukuran bintang */
        padding: 0 0.2rem; /* Jarak antar bintang */
        transition: color 0.2s ease-in-out; /* Transisi warna */
    }
    .rating-group input:checked ~ label, /* Bintang yang dipilih dan bintang setelahnya */
    .rating-group input:hover ~ label { /* Bintang saat dihover dan bintang setelahnya */
        color: #ffc107; /* Warna bintang saat aktif (kuning) */
    }
    .rating-group label:hover, /* Bintang yang sedang dihover */
    .rating-group label:hover ~ label { /* Bintang setelah bintang yang dihover */
        color: #ffc107;
    }
    /* Untuk error validasi di rating group */
    .rating-group.is-invalid + .text-danger {
        display: block; /* Tampilkan pesan error */
    }
</style>

@endsection