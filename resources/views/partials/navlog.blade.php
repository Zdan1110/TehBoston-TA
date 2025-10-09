@php
    $user = session('user');
    if (!$user || !isset($user['id_akun'])) {
        header("Location: /login");
        exit;
    }
@endphp
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm py-3" style="min-height: 10px;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary d-flex align-items-center" href="/">
            <img src="{{ asset('gambar/logo.png') }}" alt="Logo" style="height: 70px; width: auto; margin-right: 10px;">
            <span><i class="fas fa-leaf me-1"></i>Teh <span class="text-warning">Boston</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center" id="main-nav-menu">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="/home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('profilee') ? 'active' : '' }}" href="/profilee">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('kemitraann') ? 'active' : '' }}" href="/kemitraann">Kemitraan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('cabangg') ? 'active' : '' }}" href="/cabangg">Cabang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('kontaks') ? 'active' : '' }}" href="/kontaks">Kontak</a>
                </li>
                <li class="nav-item ms-3">
                    <a class="nav-link d-flex align-items-center" href="#" id="userMenuTrigger" role="button">
                        <div class="avatar bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <span class="ms-2">
                            <strong>{{ Session::get('user')['username'] ?? 'Guest' }}</strong>
                        </span>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center d-none" id="user-nav-menu">
                <!-- Logout dipindahkan ke atas Data Diri -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="/profiles"><i class="fas fa-user me-2"></i>Data Diri</a></li>
                <li class="nav-item"><a class="nav-link" href="/franchisee"><i class="fas fa-store me-2"></i>Franchisee</a></li>
                <li class="nav-item"><a class="nav-link" href="/status"><i class="fas fa-clipboard-list me-2"></i>Status Pendaftaran</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.edit', Auth::user()->id_akun) }}">
                        <i class="fas fa-cog me-2"></i>Account Setting
                    </a>
                </li>
                <!-- Kembali tetap di paling bawah -->
                <li class="nav-item">
                    <a class="nav-link" href="#" id="backToMainMenu">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </li>
            </ul>

        </div>
    </div>
</nav>