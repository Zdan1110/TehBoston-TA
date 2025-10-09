@extends('layouts.app')

@section('title', 'Home - Teh Boston')

@section('content')
<style>
    /* Desktop Styles (Original) - DO NOT CHANGE THESE */
    .hero-content {
        padding-top: 230px;
    }

    @media (min-width: 769px) {
        .hero-content {
            padding: 80px 60px; /* atas-bawah 80px, kiri-kanan 60px */
        }

        .hero-section .d-flex {
            gap: 40px; /* beri jarak antar teks dan gambar */
            align-items: center;
        }
    }

    .custom-btn {
        background: linear-gradient(135deg, #FFB74D,rgb(158, 135, 74));
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 25px;
        padding: 8px 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
    }

    .accordion-button {
        background-color: #ffffff !important;
        color: #000000 !important;
        box-shadow: none !important;
        border: none !important;
    }

    .accordion-button:focus {
        box-shadow: none !important;
        outline: none !important;
        background-color: #ffffff !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #ffffff !important;
        color: #000000 !important;
        box-shadow: none !important;
    }

    .accordion-button::after {
        filter: none !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.5 3.5a.5.5 0 0 1 .707 0L8 9.293l5.793-5.793a.5.5 0 0 1 .707.707l-6 6a.5.5 0 0 1-.707 0z'/%3E%3C/svg%3E");
    }

    .custom-btn:hover {
        background: linear-gradient(135deg,rgb(244, 185, 96),rgb(226, 193, 144));
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
    }

    .hero-image {
        width: 100%;
        max-width: 450px;
        height: auto;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hero-image:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    /* Mobile Specific Styles (max-width: 768px) - ONLY CHANGE THESE */
    @media (max-width: 768px) {
        .hero-image {
            max-width: 250px;
            margin: 0 auto;
        }

        .hero-section .d-flex {
            flex-direction: column; /* Stack items vertically */
            gap: 10px; /* Reduce gap on mobile */
            align-items: center; /* Center items horizontally */
        }

        .hero-content {
            padding-top: 50px; 
            padding-bottom: 30px;
            text-align: center; /* Center text on mobile */
        }

        .hero-content .btn {
            padding: 10px 25px;
            font-size: 1rem;
        }

        .section-title {
            text-align: center;
        }

        /* Menghapus CSS yang tidak perlu dan berpotensi konflik */
        /* Cukup ratakan teks di card ke tengah untuk mobile */
        #produk .card {
            text-align: center;
        }
    }

</style>
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content" data-aos="fade-right">
                    <h1 class="hero-title">Minuman Teh Berkualitas dengan Varian Rasa Unik</h1>
                    <p class="hero-subtitle">Teh Boston menawarkan pengalaman minum teh yang berbeda dengan bahan premium dan racikan spesial. Bergabunglah dengan jaringan franchise kami untuk peluang bisnis yang menguntungkan.</p>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="#" id="btn-daftar-mitra" class="btn btn-primary me-3">Gabung Mitra</a>
                        <a href="#produk" class="btn btn-primary me-3">Lihat Produk</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <img src="{{ asset('gambar/figur1.png') }}" alt="Teh Boston" class="hero-image img-fluid">
            </div>

        </div>
    </div>
</section>

<section id="produk" class="section py-5">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Produk Series Kami</h2>
        <div class="row g-4">
            @php
                $produkList = [
                    ['img' => 'teaseries.png', 'title' => 'Tea Series', 'desc' => ''],
                    ['img' => 'yakultseries.png', 'title' => 'Yakult Series', 'desc' => ''],
                    ['img' => 'coffeeseries.png', 'title' => 'Coffee Series', 'desc' => ''],
                    ['img' => 'blend.png', 'title' => 'Blend Series', 'desc' => ''],
                ];
            @endphp

            @foreach ($produkList as $i => $produk)
            {{-- Menggunakan col-6 agar tetap 2 kolom di HP, mb-4 untuk jarak bawah --}}
            <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="{{ 100 * ($i + 1) }}">
                {{-- Menggunakan class .card dari Bootstrap dan .h-100 untuk tinggi yang sama --}}
                <div class="card shadow-sm border-0 h-100">
                    <img src="{{ asset('gambar/' . $produk['img']) }}" class="card-img-top" alt="{{ $produk['title'] }}">
                    {{-- KUNCI PERBAIKAN: Menambahkan d-flex dan flex-column agar mt-auto pada tombol berfungsi --}}
                    <div class="card-body d-flex flex-column">
                        {{-- Wrapper untuk konten agar tombol bisa didorong ke bawah --}}
                        <div>
                            <h5 class="card-title">{{ $produk['title'] }}</h5>
                            <p class="card-text">{{ $produk['desc'] }}</p>
                        </div>
                        {{-- mt-auto akan mendorong tombol ini ke bagian paling bawah card --}}
                        <a href="#" class="btn custom-btn mt-auto" data-bs-toggle="modal" data-bs-target="#menuModal">Lihat Produk</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Modal tidak berubah --}}
    <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="menuModalLabel">Menu Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('gambar/Menu.png') }}" alt="Menu Produk" class="img-fluid rounded shadow mb-4">
                    <a href="{{ asset('gambar/Menu.pdf') }}" class="btn btn-primary" download>
                        <i class="bi bi-download me-2"></i>Download Menu PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="faq-section py-5 bg-white">
    <div class="container">
        <h2 class="section-title text-center mb-4" data-aos="fade-up">Pertanyaan Umum Seputar Franchise</h2>

        <div class="accordion" id="faqAccordion" data-aos="fade-up">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        Berapa biaya untuk bergabung sebagai mitra Teh Boston?
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Biaya awal untuk menjadi mitra dimulai dari Rp 28.000.000, sudah termasuk perlengkapan dan bahan awal.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        Apa saja yang didapatkan setelah bergabung?
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Anda akan mendapatkan booth, perlengkapan lengkap, pelatihan, dan pasokan bahan baku awal.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faq3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        Apakah perlu pengalaman bisnis sebelumnya?
                    </button>
                </h2>
                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Tidak perlu. Kami menyediakan pelatihan lengkap hingga Anda siap menjalankan usaha.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faq4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                        Bagaimana sistem pasokan bahan baku?
                    </button>
                </h2>
                <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                         Pesan melalui kasir Distribution Centre, setelah melakukan pembayaran pesanan akan diantar.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="testimonial-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-4" data-aos="fade-up">Apa Kata Pelanggan Kami</h2>

        <div class="swiper testimonial-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                @forelse ($testimonials as $t)
                <div class="swiper-slide">
                    <div class="card shadow-sm p-4 h-100 border-0">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3 d-flex align-items-center justify-content-center bg-secondary text-white rounded-circle" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>

                            <div>
                                <h6 class="mb-0 fw-bold">{{ $t->nama_lengkap }}</h6>
                                <div class="text-warning">
                                    @for ($i = 0; $i < $t->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @for ($i = $t->rating; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 fst-italic">{{ $t->ulasan_pesan }}</p>
                    </div>
                </div>
                @empty
                <div class="swiper-slide">
                    <div class="card shadow-sm p-4 h-100 border-0 text-center">
                        <p class="fst-italic mb-0">Belum ada testimoni.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnDaftar = document.getElementById('btn-daftar-mitra');

        btnDaftar?.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Login Dulu!',
                text: 'Anda harus login terlebih dahulu untuk mendaftar sebagai mitra.',
                confirmButtonText: 'Login Sekarang',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/login";
                }
            });
        });

        new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            breakpoints: {
                576: { slidesPerView: 2 },
                992: { slidesPerView: 3 }
            }
        });
    });
</script>

@endsection