<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Franchise - Teh Boston</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2e7d32;
            --primary-light: #4CAF50;
            --primary-dark: #1b5e20;
            --text: #333333;
            --text-light: #757575;
            --border: #e0e0e0;
            --bg: #fafafa;
            --card-bg: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 0 20px;
        }
        
        .logo {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
            letter-spacing: -1px;
        }
        
        .logo span {
            color: #ff9800;
        }
        
        .subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Card Grid Styles */
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin: 30px 0 20px;
            padding-left: 10px;
            border-left: 4px solid var(--primary);
        }
        
        .cards-container {
            display: flex;
            gap: 25px;
            overflow-x: auto;
            padding: 10px 5px 25px;
            margin-bottom: 30px;
            scrollbar-width: thin;
        }
        
        .cards-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .cards-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .cards-container::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        .franchise-card {
            min-width: 300px;
            max-width: 300px;
            height: 380px;
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }
        
        .franchise-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 20px;
            text-align: center;
            color: white;
            height: 140px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .franchise-code {
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .franchise-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        
        .card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        
        .info-label {
            width: 100px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-light);
        }
        
        .info-value {
            flex: 1;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-top: auto;
        }
        
        .btn:hover {
            background: var(--primary-dark);
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        /* Add Franchise Card */
        .add-card {
            min-width: 300px;
            max-width: 300px;
            height: 380px;
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px dashed var(--border);
        }
        
        .add-card:hover {
            border-color: var(--primary);
            background: rgba(76, 175, 80, 0.03);
            transform: translateY(-5px);
        }
        
        .add-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        
        .add-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
        }
        
        .add-desc {
            font-size: 0.9rem;
            color: var(--text-light);
            max-width: 80%;
            padding: 0 20px;
        }
        
        /* Detail View */
        .detail-view {
            display: none;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 30px;
            margin-top: 20px;
            border: 1px solid var(--border);
        }
        
        .detail-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .back-btn {
            display: flex;
            align-items: center;
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            margin-right: 20px;
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .back-btn:hover {
            background: rgba(46, 125, 50, 0.1);
        }
        
        .back-btn i {
            margin-right: 8px;
        }
        
        .detail-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .detail-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 768px) {
            .detail-content {
                grid-template-columns: 1fr;
            }
        }
        
        .detail-section {
            padding: 20px 0;
        }
        
        .section-heading {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .franchise-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin-top: 15px;
            box-shadow: var(--shadow);
        }
        
        .placeholder-image {
            width: 100%;
            height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
            border-radius: 12px;
            color: var(--text-light);
            border: 1px dashed var(--border);
        }
        
        .placeholder-image i {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: #e0e0e0;
        }
        
        .footer {
            text-align: center;
            padding: 30px 20px 20px;
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: 40px;
            width: 100%;
        }
        
        /* Profile Info */
        .profile-info {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--primary);
        }
        
        .profile-img i {
            font-size: 2.5rem;
            color: var(--primary);
        }
        
        .profile-text h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text);
        }
        
        .profile-text p {
            color: var(--text-light);
        }
        
        /* Tombol Kembali */
        .home-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: var(--primary);
            padding: 14px 25px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid var(--primary);
            margin-top: 20px;
            box-shadow: var(--shadow);
        }
        
        .home-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }
        
        .home-btn i {
            margin-right: 10px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .logo {
                font-size: 2.2rem;
            }
            
            .cards-container {
                gap: 15px;
            }
            
            .franchise-card, .add-card {
                min-width: 280px;
                max-width: 280px;
            }
            
            .profile-info {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    @if (session('success'))
    <div class="alert alert-success alert-dismissible popup-top fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible popup-top fade show" role="alert">
        <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">Teh <span>Boston</span></div>
            <div class="subtitle">Manajemen Franchise & Profil Pengguna</div>
        </div>
        
        <!-- Profile Info -->
        <div class="profile-info">
            <div class="profile-img">
                @if(isset($profile->pas_photo))
                    <img src="{{ asset('uploads/photo/'.$profile->pas_photo) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div class="profile-text">
                <h2>{{ Session::get('user')['username'] ?? 'Guest' }}</h2>
                <p>{{ Session::get('user')['email'] ?? '-' }}</p>
            </div>
        </div>
        
        <!-- Franchise Cards -->
        <h2 class="section-title">Franchise Saya</h2>
        <div class="cards-container">
            <!-- Dynamic Franchise Cards -->
            @foreach ($franchise as $data)
            <div class="franchise-card" id="card-{{ $data->id_franchise }}">
                <div class="card-header">
                    <div class="franchise-code">{{ $data->id_franchise ?? 'FR-001' }}</div>
                    <div class="franchise-name">{{ $data->nama_franchise ?? 'Depan PTPN' }}</div>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Lokasi:</div>
                        <div class="info-value">{{ $data->kota_usaha ?? 'Subang' }}, {{ $data->provinsi_usaha ?? 'Jawa Barat' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat:</div>
                        <div class="info-value">{{ $data->alamat_usaha ?? 'Jl. Pejuan 45' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status:</div>
                        <div class="info-value">{{ $data->status ?? '-' }}</div>
                    </div>
                    <button class="btn" onclick="showDetail('{{ $data->id_franchise }}')">
                        <i class="fas fa-info-circle"></i> Lihat Detail
                    </button>
                </div>
            </div>
            @endforeach
            
            <!-- Add Franchise Card -->
            <a href="/tambahfranchise" class="add-card">
                <div class="add-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="add-title">Tambah Franchise</div>
                <div class="add-desc">Daftarkan franchise baru untuk memperluas jaringan</div>
            </a>
        </div>
        
        <!-- Tombol Kembali ke Home -->
        <a href="/home" class="home-btn">
            <i class="fas fa-home"></i> Kembali ke Halaman Home
        </a>
        
        <!-- Detail View -->
        <div id="detail-view" class="detail-view">
            <div class="detail-header">
                <div class="back-btn" onclick="hideDetail()">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </div>
                <div class="detail-title">Detail Franchise</div>
            </div>
            
            <div class="detail-content">
                @foreach ($franchise as $data)
                <div id="detail-{{ $data->id_franchise }}" class="franchise-detail" style="display: none;">
                    <div class="detail-section">
                        <div class="section-heading">Informasi Franchise</div>
                        <div class="info-grid">
                            <div class="info-row">
                                <div class="info-label">Kode:</div>
                                <div class="info-value">{{ $data->id_franchise }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Nama:</div>
                                <div class="info-value">{{ $data->nama_franchise }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Alamat:</div>
                                <div class="info-value">{{ $data->alamat_usaha }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Provinsi:</div>
                                <div class="info-value">{{ $data->provinsi_usaha }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kota:</div>
                                <div class="info-value">{{ $data->kota_usaha }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kelurahan:</div>
                                <div class="info-value">{{ $data->kelurahan_usaha }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kode Pos:</div>
                                <div class="info-value">{{ $data->kode_pos }}</div>
                            </div>
                            <button class="btn"
                                @if($data->langganan === 'Tidak Berlangganan')
                                    onclick="alert('Silakan berlangganan terlebih dahulu untuk menggunakan fitur kasir.')"
                                @else
                                    onclick="window.location.href='{{ url('/loginkasir/'.$data->id_franchise) }}'"
                                @endif
                            >
                                <i class="fas fa-cash-register"></i> Login Akun Kasir
                            </button>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <div class="section-heading">Foto Lokasi</div>
                        @if(isset($data->lokasi_usaha))
                            <img src="{{ asset('uploads/lokasi/'.$data->lokasi_usaha) }}" alt="Lokasi Usaha" class="franchise-image">
                        @else
                            <div class="placeholder-image">
                                <i class="fas fa-store"></i>
                                <div>Foto Usaha Tidak Tersedia</div>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            © {{ date('Y') }} © 2025 Teh Boston. Hak Cipta Dilindungi. Dibuat dengan penuh cinta untuk para pecinta teh boston.
        </div>
    </div>

    <script>
        function showDetail(franchiseId) {
            // Sembunyikan semua detail franchise
            document.querySelectorAll('.franchise-detail').forEach(detail => {
                detail.style.display = 'none';
            });
            
            // Tampilkan detail franchise yang dipilih
            const detailElement = document.getElementById(`detail-${franchiseId}`);
            if (detailElement) {
                detailElement.style.display = 'block';
            }
            
            // Tampilkan panel detail view
            document.getElementById('detail-view').style.display = 'block';
            
            // Scroll ke detail view
            window.scrollTo({
                top: document.getElementById('detail-view').offsetTop - 20,
                behavior: 'smooth'
            });
            
            // Highlight card yang dipilih
            document.querySelectorAll('.franchise-card').forEach(card => {
                card.style.boxShadow = 'var(--shadow)';
            });
            const selectedCard = document.getElementById(`card-${franchiseId}`);
            if (selectedCard) {
                selectedCard.style.boxShadow = '0 0 0 3px rgba(46, 125, 50, 0.3)';
            }
        }

        function hideDetail() {
            // Sembunyikan panel detail view
            document.getElementById('detail-view').style.display = 'none';
            
            // Hapus highlight dari card
            document.querySelectorAll('.franchise-card').forEach(card => {
                card.style.boxShadow = 'var(--shadow)';
            });
        }
        
        // Add hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.franchise-card, .add-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-8px)';
                    card.style.boxShadow = '0 12px 30px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                    card.style.boxShadow = 'var(--shadow)';
                });
            });
        });
    </script>
</body>
</html>