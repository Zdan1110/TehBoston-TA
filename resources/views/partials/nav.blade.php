<!-- File: resources/views/partials/nav.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm py-3"
     style="min-height: 10px;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary d-flex align-items-center" href="/">
    <img src="{{ asset('gambar/logo.png') }}" alt="Logo" style="height: 70px; width: auto; margin-right: 10px;">
    <span><i class="fas fa-leaf me-1"></i>Teh <span class="text-warning">Boston</span></span>
    </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('profile') ? 'active' : '' }}" href="/profile">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('kemitraan') ? 'active' : '' }}" href="/kemitraan">Kemitraan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('cabang') ? 'active' : '' }}" href="/cabang">Cabang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('kontak') ? 'active' : '' }}" href="/kontak">Kontak</a>
                </li>
                <li class="nav-item ms-3">
            <li class="nav-item ms-3">
                <a class="nav-link bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                href="/login" style="width: 40px; height: 40px; transition: 0.3s; margin-left: -10px;">
                    <i class="fas fa-right-to-bracket"></i>
                </a>
            </li>


        </div>
    </div>
</nav>