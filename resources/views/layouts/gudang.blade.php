<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gudang Teh Boston - @yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link
        rel="icon"
        href="/public/favicon.ico"
        type="image/x-icon"
      />

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #2E7D32;
      --secondary: #FFD600;
      --light: #F9F9F9;
    }

    body {
      background-color: var(--light);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    @media only screen and (max-width: 768px) {
        .apexcharts-canvas {
            width: 100% !important;
            max-width: 500px !important;
            margin: 0 auto;
        }
    }

    /* SIDEBAR */
    .sidebar {
      position: fixed;
      background: linear-gradient(180deg, var(--primary) 0%, #1B5E20 100%);
      color: white;
      width: 250px;
      height: 100vh;
      left: 0;
      top: 0;
      transition: all 0.3s ease-in-out;
      z-index: 1000;
    }

    .logo-container {
      padding: 1rem;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .logo-text {
      font-weight: bold;
      font-size: 1.2rem;
      margin-left: 10px;
    }

    .nav-link {
      color: rgba(255,255,255,0.85);
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
    }

    .nav-link.active,
    .nav-link:hover {
      background-color: var(--secondary);
      color: var(--primary);
    }

    .nav-link i {
      margin-right: 10px;
    }

    .main-content {
      margin-left: 250px;
      transition: margin-left 0.3s ease-in-out;
    }

    .topbar {
      background-color: white;
      padding: 10px 20px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .topbar .user {
      font-weight: bold;
      display: flex;
      align-items: center;
      color: #333;
    }

    .topbar .user i {
      margin-right: 5px;
    }

    /* FLOATING BUTTON */
    .mobile-menu-btn {
      display: none;
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background-color: var(--primary);
      color: white;
      border: none;
      font-size: 1.8rem;
      z-index: 1101;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* MOBILE NAV APPEARS FROM BOTTOM */
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        bottom: -100%;
        top: auto;
        left: 0;
        transition: bottom 0.3s ease-in-out;
        padding-bottom: 1rem;
      }

      .sidebar.active {
        bottom: 0;
      }

      .main-content {
        margin-left: 0;
      }

      .mobile-menu-btn {
        display: flex;
        justify-content: center;
        align-items: center;
      }
    }

    .btn-lunas {
        background-color: #13f164;
        color: white;
        border: 2px solid #464444;
    }

    .btn-lunas:hover {
        background-color: #16a34a;
    }s
    /* Hapus warna biru default di sidebar link */
    .sidebar .nav-link {
      color: rgba(255,255,255,0.85) !important;
      text-decoration: none !important;
    }

    /* Saat hover atau aktif, pakai warna custom */
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: var(--secondary) !important;
      color: var(--primary) !important;
    }

    .select2-container .select2-selection--single {
        height: 38px;
        padding: 5px 8px;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

  </style>
</head>
<body>
  @php
    $user = session('user');
  @endphp
  <button class="mobile-menu-btn" id="mobileMenuBtn">
    <i class="bi bi-list"></i>
  </button>

  <div class="sidebar" id="sidebar">
  <div class="logo-container d-flex align-items-center">
    <img src="{{ asset('gambar/logo.png') }}" alt="Logo" height="40">
    <span class="logo-text">Distribution Centre</span>
  </div>
  <ul class="nav flex-column mt-3">

    <li class="nav-item">
        <a href="{{ route('gudang.index') }}" class="nav-link {{ Request::is('gudang') ? 'active' : '' }}">
          <i class="bi bi-box"></i> Dashboard
        </a>
    </li>

    @if($user && strtolower($user['type_akun']) !== 'kasirdc')
    <li class="nav-item">
      <a href="/gudang/masuk" class="nav-link {{ Request::is('gudang/masuk') ? 'active' : '' }}">
        <i class="bi bi-box-arrow-in-down"></i> Barang Masuk
      </a>
    </li>
    @endif

    <li class="nav-item">
      <a href="/gudang/pesanan" class="nav-link {{ Request::is('gudang/pesanan') ? 'active' : '' }}">
        <i class="bi bi-box-arrow-up"></i> Pesanan Bahan Baku
      </a>
    </li>

    @if($user && strtolower($user['type_akun']) !== 'kasirdc')
    <li class="nav-item">
      <a href="{{ route('laporan.stok') }}" class="nav-link {{ Request::is('gudang/stok') ? 'active' : '' }}">
        <i class="bi bi-clipboard-data"></i> Laporan Stok
      </a>
    </li>
    @endif

    <li class="nav-item">
      <a href="{{ route('riwayat.gudang') }}" class="nav-link {{ Request::is('gudang/riwayat') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> Riwayat
      </a>
    </li>

    @if($user && strtolower($user['type_akun']) !== 'kasirdc')
    <li class="nav-item">
      <a href="{{ route('gudang.datasupplier') }}" class="nav-link {{ Request::is('gudang/datasupplier') ? 'active' : '' }}">
        <i class="bi bi-truck"></i> Data Supplier
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ route('gudang.tabelpaket') }}" class="nav-link {{ Request::is('gudang/tabelpaket') ? 'active' : '' }}">
        <i class="bi bi-truck"></i> Kelola Paket Bahan Baku
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#laporanMenu" role="button" aria-expanded="{{ Request::is('gudang/laporan*') ? 'true' : 'false' }}" aria-controls="laporanMenu">
        <div>
          <i class="bi bi-file-earmark-text"></i> <span>Data Laporan</span>
        </div>
        <i class="bi bi-caret-down-fill"></i>
      </a>
      <div class="collapse {{ Request::is('gudang/laporan*') ? 'show' : '' }}" id="laporanMenu">
        <ul class="nav flex-column ms-4">
          <li class="nav-item">
            <a href="{{ route('gudang.tabelpenjualan') }}" class="nav-link {{ Request::is('gudang/laporan/penjualan-bahanbaku') ? 'active' : '' }}">
              <i class="bi bi-bag-check"></i> Penjualan Bahan
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('gudang.transaksi.index') }}" class="nav-link {{ Request::is('gudang/laporan/pengeluaran') ? 'active' : '' }}">
              <i class="bi bi-cash-stack"></i> Pencatatan Transaksi
            </a>
          </li>
        </ul>
      </div>
    </li>
    @endif

    <li class="nav-item">
      <a href="/home" class="nav-link">
        <i class="bi bi-box-arrow-left"></i> Ke Halaman Home
      </a>
    </li>

    <li class="nav-item mt-3">
      <a href="/logout" class="nav-link text-danger">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </li>
  </ul>
</div>


  <div class="main-content" id="mainContent">
    <div class="topbar">
      <div class="datetime">
        <span id="clock">--:--:--</span> | <span id="date">-- -- ----</span>
      </div>
      <div class="user">
        <i class="bi bi-person-circle me-2"></i>
        <span><strong>{{ Session::get('user')['username'] ?? 'Guest' }}</strong></span>
      </div>
    </div>

    <div class="container-fluid p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">@yield('title')</h2>
        @yield('breadcrumb')
      </div>
      @yield('content')
    </div>
  </div>

  <script>
    document.getElementById('mobileMenuBtn').addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('active');
    });

    function updateDateTime() {
      const now = new Date();
      const time = now.toLocaleTimeString('id-ID');
      const date = now.toLocaleDateString('id-ID', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
      });
      document.getElementById('clock').textContent = time;
      document.getElementById('date').textContent = date;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @yield('scripts')
</body>
</html>