<!-- File: resources/views/profile.blade.php -->
@extends('layouts.applog')

@section('title', 'Profil - Teh Boston')

@section('content')
<section class="section bg-light">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Profil Kami</h2>
        
        <div class="row g-5 mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="vm-card">
                    <div class="text-center">
                        <div class="vm-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="mb-4">VISI</h3>
                        <p class="mb-0">"Menjadikan Teh Boston merek minuman teh dan minuman kekinian terdepan yang
dikenal karena inovasi varian rasa yang khas, dan komitmen untuk menyajikan minuman
yang berkualitas dan bercitarasa tinggi"</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="vm-card">
                    <div class="text-center">
                        <div class="vm-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3 class="mb-4">MISI</h3>
                        <ul class="text-start ps-4">
                            <li class="mb-3">Menyajikan produk berkualitas tinggi</li>
                            <li class="mb-3">Melakukan inovasi berkelanjutan</li>
                            <li class="mb-3">Mebangun merk yang kuat</li>
                            <li class="mb-3">Membangun Ekosistem Kemitraan yang Kuat</li>
                            <li class="mb-3">Memberikan Pelayanan Prima</li>
                            <li>Memperluas Jangkauan Pasar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card border-0 shadow h-100">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Sejarah Teh Boston</h3>
                        <p class="card-text">Teh Boston didirikan pada tahun 2023 dengan misi membantu masyarakat yang memiliki keinginan untuk berwirausaha di bidang minuman. Bermula dari sebuah gerobak kecil di kota Subang, Teh Boston tumbuh dengan pesat berkat komitmen terhadap kualitas dan rasa yang konsisten.</p>
                        <p class="card-text">Dengan semangat gotong royong, Teh Boston tidak hanya menawarkan minuman berkualitas tetapi juga membuka peluang kemitraan bagi masyarakat yang ingin memiliki usaha sendiri. Setiap produk Teh Boston dibuat dengan bahan pilihan dan proses yang higienis, menjamin kepuasan pelanggan.</p>
                        <p class="card-text">Hingga saat ini, Teh Boston terus berinovasi menciptakan varian rasa baru dan memperluas jaringan mitra di seluruh Indonesia. Keberhasilan Teh Boston tidak lepas dari dukungan para mitra dan konsumen setia yang telah mempercayai kualitas produk kami.</p>
                        <p class="card-text">Kami berkomitmen untuk terus memberikan yang terbaik dan menjadi mitra terpercaya bagi siapa saja yang ingin memulai usaha minuman dengan modal terjangkau dan keuntungan menjanjikan.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card border-0 shadow h-100 overflow-hidden">
                    <img src="gambar/sejarah.png" alt="Booth Teh Boston" class="w-100 h-100 object-cover">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection