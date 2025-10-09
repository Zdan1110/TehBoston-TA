<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Survey - {{ $title ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #28a745;
            --primary-dark: #218838;
            --primary-light: #d4edda;
            --secondary: #f8f9fa;
            --text-dark: #343a40;
            --text-light: #6c757d;
        }

        body {
            background-color: #f5f9f6;
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Utility class to prevent scrolling */
        .no-scroll {
            overflow: hidden;
        }

        /* Sidebar improvements */
        .sidebar {
            background: linear-gradient(180deg, var(--primary), var(--primary-dark));
            color: white;
            min-height: 100vh; /* This is the default for desktop */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            position: fixed;
            width: 250px;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            border-radius: 5px;
            margin: 5px 0;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Styling for the sidebar header/brand area */
        .sidebar-brand {
            padding-top: 20px;
            padding-bottom: 20px; /* Memberi ruang di bawah logo dan teks */
            text-align: center; /* Memusatkan konten di dalamnya */
        }

        .sidebar-brand img {
            max-width: 120px; /* Sesuaikan ini. Coba nilai yang lebih besar agar tidak pecah */
            height: auto; /* Penting agar aspek rasio terjaga */
            display: block; /* Memastikan margin auto berfungsi untuk centering */
            margin: 0 auto 15px auto; /* Memusatkan gambar dan memberi margin bawah */
        }

        /* Mobile menu toggle */
        .mobile-menu-toggle {
            display: none; /* Hidden by default for desktop, will be overridden for mobile */
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1100;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            align-items: center; /* Use flex properties only when displayed */
            justify-content: center; /* Use flex properties only when displayed */
        }

        .page-content {
            padding: 20px;
            transition: margin 0.3s;
        }

        /* Header improvements */
        .header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-title {
            flex: 1;
            min-width: 200px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: white;
            border-bottom: 2px solid var(--primary-light);
            padding: 15px 20px;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            color: var(--primary-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .badge-success {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            padding: 7px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .table thead th {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
            border-top: none;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .survey-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .search-container {
            position: relative;
            max-width: 300px;
            width: 100%;
        }

        .search-container i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-container input {
            padding-left: 40px;
            border-radius: 30px;
            border: 1px solid #dee2e6;
            width: 100%;
        }

        /* Responsive adjustments */
        @media (min-width: 993px) {
            .page-content {
                margin-left: 250px;
            }
            /* Explicitly hide the mobile menu toggle on desktop */
            .mobile-menu-toggle {
                display: none !important; /* Use !important to ensure override */
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                bottom: -100%; /* Initially hidden */
                left: 0;
                width: 100%;
                height: auto;
                min-height: auto; /* Reset min-height for mobile */
                max-height: 80vh; /* Menaikkan tinggi maksimal sidebar untuk mobile */
                overflow-y: auto;
                background: var(--primary);
                transition: bottom 0.3s ease-in-out;
                z-index: 1050;
                border-top-left-radius: 12px;
                border-top-right-radius: 12px;
                padding: 15px;
                /* Tambahkan padding bawah yang cukup untuk tombol X */
                padding-bottom: 75px; /* (55px tinggi tombol + 20px jarak aman) */
            }

            .sidebar.active {
                bottom: 0; /* Slide up when active */
            }

            /* Pastikan ul.nav tidak memiliki margin-top yang aneh di mobile */
            .sidebar ul.nav {
                margin-top: 0; /* Override any desktop margin-top if needed */
            }

            .mobile-menu-toggle {
                display: flex; /* Show on mobile */
                bottom: 15px; /* Jarak dari bawah layar */
                left: 50%;
                transform: translateX(-50%);
                top: auto;
            }

            .page-content {
                margin-left: 0;
            }

            .header {
                position: sticky; /* Sticky header for mobile */
                top: 15px;
                z-index: 990;
                margin-top: 15px;
            }

            /* Overlay for mobile menu */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .dropdown-menu {
                position: absolute;
                right: 0;
                left: auto;
            }
        }

        @media (max-width: 576px) {
            .header {
                padding: 12px;
            }

            .page-content {
                padding: 15px;
            }

            .search-container {
                max-width: 100%;
            }

            .btn {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        {{-- HANYA GUNAKAN BARIS INI. JANGAN TEMPEL KODE HTML SIDBAR DI SINI SECARA MANUAL --}}
        @include('survey.navcoba')
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <header class="header">
                    <div class="header-title">
                        <h4 class="mb-0">{{ $title ?? 'Dashboard' }}</h4>
                    </div>
                    <div class="header-actions">
                       

                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                                <i class="fas fa-user me-1" aria-hidden="true"></i> {{ session('nama_user') ?? 'Survey' }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="/survey/profile"><i class="fas fa-user me-2" aria-hidden="true"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2" aria-hidden="true"></i> Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="/logout">
                                        <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i> Keluar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>

                <main class="page-content">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle functionality
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.classList.toggle('no-scroll');

            // Change icon
            const icon = mobileMenuToggle.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }

        mobileMenuToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // This function should ideally be in a separate script file or specific view if only used on one page
        function calculateArea() {
            const panjangInput = document.getElementById('panjang');
            const lebarInput = document.getElementById('lebar');
            const totalLuasInput = document.getElementById('total_luas');

            if (panjangInput && lebarInput && totalLuasInput) {
                const panjang = parseFloat(panjangInput.value) || 0;
                const lebar = parseFloat(lebarInput.value) || 0;
                const totalLuas = panjang * lebar;
                totalLuasInput.value = totalLuas.toFixed(2);
            }
        }

        // If 'panjang' and 'lebar' inputs exist, attach event listeners
        if (document.getElementById('panjang') && document.getElementById('lebar')) {
            document.getElementById('panjang').addEventListener('input', calculateArea);
            document.getElementById('lebar').addEventListener('input', calculateArea);
            // Also call it once on load if values might be pre-filled
            calculateArea();
        }

        // Close sidebar when clicking outside on mobile (more robust check)
        document.addEventListener('click', function(event) {
            // Check if sidebar is active and screen width is mobile
            if (sidebar.classList.contains('active') && window.innerWidth <= 992) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = mobileMenuToggle.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnToggle) {
                    toggleSidebar(); // Use the existing toggle function
                }
            }
        });

        // Auto resize textareas
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }

        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                autoResize(this);
            });

            // Initialize on load
            autoResize(textarea);
        });
    </script>
</body>
</html>