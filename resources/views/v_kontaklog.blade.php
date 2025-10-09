@extends('layouts.applog')

@section('title', 'Kontak & Ulasan - Teh Boston')

@section('content')
<section class="section">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Kontak & Beri Ulasan</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form" data-aos="fade-up">
                    {{-- Notifikasi sukses --}}
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('ulasan.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ Session::get('user')['nama'] ?? '' }}"
                                   placeholder="Masukkan nama lengkap"
                                   {{ Session::has('user') ? 'readonly' : 'required' }}>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="{{ Session::get('user')['email'] ?? '' }}"
                                   placeholder="Masukkan email"
                                   {{ Session::has('user') ? 'readonly' : 'required' }}>
                        </div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label">Rating Produk</label>
                            <div class="rating-group">
                                <input type="radio" id="star5" name="rating" value="5">
                                <label for="star5" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" class="rating-star"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star1" name="rating" value="1" required>
                                <label for="star1" class="rating-star"><i class="fas fa-star"></i></label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Masukkan subjek" required>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Ulasan/Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Tulis ulasan atau pesan Anda" required></textarea>
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

<style>
    .rating-group {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating-group input {
        display: none;
    }
    .rating-group label {
        color: #ccc;
        cursor: pointer;
        font-size: 1.5rem;
        padding: 0 0.2rem;
    }
    .rating-group input:checked ~ label,
    .rating-group input:hover ~ label {
        color: #ffc107;
    }
    .rating-group label:hover,
    .rating-group label:hover ~ label {
        color: #ffc107;
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
</style>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@endsection
