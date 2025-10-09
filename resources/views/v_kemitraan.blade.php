@extends('layouts.app')

@section('title', 'Kemitraan - Teh Boston')

@section('content')
<section class="section">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Kemitraan</h2>

        <!-- Langkah Mitra -->
        <div class="mb-5">
            <h3 class="text-center mb-5" data-aos="fade-up">Langkah Gabung Menjadi Mitra</h3>
            <div class="row g-4">
                @php
                    $steps = [
                        ['icon' => 'fa-map-marker-alt', 'title' => 'Cari Lokasi Usaha', 'desc' => 'Tentukan lokasi strategis untuk usaha minuman Anda'],
                        ['icon' => 'fa-file-alt', 'title' => 'Isi Form Pendaftaran', 'desc' => 'Lengkapi formulir pendaftaran mitra secara online'],
                        ['icon' => 'fa-clock', 'title' => 'Tunggu Konfirmasi', 'desc' => 'Tim kami akan memproses pendaftaran Anda'],
                        ['icon' => 'fa-money-bill-wave', 'title' => 'Pembayaran', 'desc' => 'Lakukan pembayaran paket kemitraan'],
                        ['icon' => 'fa-search-location', 'title' => 'Survey Lokasi', 'desc' => 'Tim survey kami akan mengunjungi lokasi usaha'],
                        ['icon' => 'fa-store', 'title' => 'Pembuatan Booth', 'desc' => 'Proses pembuatan dan pengiriman booth usaha'],
                    ];
                @endphp

                @foreach ($steps as $index => $step)
                <div class="col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
                    <div class="card border-0 shadow h-100 text-center py-4 px-3">
                        <div class="position-relative d-inline-block mb-3">
                            <div class="position-absolute top-0 start-0 translate-middle bg-warning text-dark rounded-circle fw-bold"
                                 style="width: 28px; height: 28px; line-height: 28px; font-size: 14px;">
                                {{ $index + 1 }}
                            </div>
                            <div class="step-icon bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white"
                                 style="width: 60px; height: 60px;">
                                <i class="fas {{ $step['icon'] }} fa-2x"></i>
                            </div>
                        </div>
                        <h5>{{ $step['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Paket Mitra -->
        <!-- Paket Mitra -->
        <div class="mt-5">
            <h3 class="text-center mb-5" data-aos="fade-up">Paket Mitra</h3>
        
            <div class="row g-4">
                @php
                    $paket = [
                        ['img' => 'booth.png', 'nama' => 'Booth (2,7m x 2m x 2,5 m)'],
                        ['img' => 'peralatan.png', 'nama' => 'Peralatan'],
                        ['img' => 'sirup.png', 'nama' => 'Perasa Pokok'],
                        ['img' => 'serbukrasa.png', 'nama' => 'Serbuk Varian rasa'],
                    ];
                @endphp
                  <!-- Warning ilustrasi -->
            <div class="alert alert-warning text-center fw-bold" role="alert" data-aos="fade-up">
                ⚠️ Gambar hanya ilustrasi, klik gambar untuk selengkapnya
            </div>
        

               {{-- ganti bagian loop paket dengan kode ini --}}
@foreach ($paket as $i => $item)
<div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ 100 * ($i + 1) }}">
    <div class="package-card">
        {{-- anchor untuk download: gunakan asset() dan atribut download --}}
        <a href="{{ asset('gambar/paketmitra.xlsx') }}"
           download="paketmitra.xlsx"
           class="package-download d-block text-decoration-none"
           title="Download daftar paket">
            <img src="{{ Str::startsWith($item['img'], 'http') ? $item['img'] : asset('gambar/' . $item['img']) }}"
                 class="package-img w-100" alt="{{ $item['nama'] }}">
            <div class="package-overlay">
                <h5 class="text-white mb-0">{{ $item['nama'] }}</h5>
            </div>
        </a>
    </div>
</div>
@endforeach

            </div>

            <!-- Harga dan tombol -->
            <div class="text-center mt-5" data-aos="fade-up">
                <h4 class="mb-4">Harga Paket: <span class="text-primary fw-bold">Rp 28.000.000</span></h4>
                <button id="btn-daftar-page" class="btn btn-primary btn-lg px-5">Daftar Menjadi Mitra</button>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('btn-daftar-page').addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Login Dulu!',
                text: 'Anda harus login terlebih dahulu sebelum mendaftar mitra.',
                confirmButtonText: 'Login Sekarang',
                confirmButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
        });
    });
</script>
@endsection
