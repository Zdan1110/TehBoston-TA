@extends('layouts.app')

@section('title', 'Cabang - Teh Boston')

@section('content')
    <style>
        /* ====================== BRANCH PAGE STYLES (INTERNAL) ====================== */
        .branch-content {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 20px;
        }

        /* Section Title */
        .branch-content .section-title {
            position: relative;
            margin-bottom: 40px;
            font-weight: 700;
            color: var(--primary-dark);
            text-align: center;
            font-size: 2.2rem;
            animation: fadeInUp 0.6s ease forwards;
        }

        .branch-content .section-title::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 4px;
            background: var(--secondary);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 10px;
        }

        /* Map & Branches Layout */
        .content-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 20px;
        }

        .map-container {
            flex: 2;
            min-width: 400px;
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            animation: fadeInUp 0.8s ease forwards;
            position: relative;
            transition: var(--transition);
        }

        .map-container:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }

        #map {
            height: 400px;
            width: 100%;
            z-index: 1;
            border-radius: 12px;
        }

        .branches-list {
            flex: 1;
            min-width: 300px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
            animation: fadeInUp 1s ease forwards;
            position: relative;
        }

        /* Branch Cards */
        .branch-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
            border-left: 4px solid transparent;
            position: relative;
            word-wrap: break-word;     /* teks panjang otomatis turun */
            overflow-wrap: break-word; /* teks panjang tidak keluar card */
        }

        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-left: 4px solid var(--secondary);
        }

        .branch-card.active {
            background: var(--light);
            border-left: 4px solid var(--primary);
            transform: translateY(-3px);
        }

        .branch-card h3 {
            color: var(--primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            font-size: 1.25rem;
        }

        .branch-card h3 i {
            margin-right: 8px;
            font-size: 1rem;
        }

        .branch-card:hover h3 i {
            transform: scale(1.2);
        }

        .branch-card p {
            margin-top: 5px;
            margin-bottom: 0;
            padding-top: 5px;
            border-top: 1px solid rgba(0,0,0,0.05);
            font-size: 0.95rem;
            line-height: 1.4;
            white-space: normal;       
        }
        .branch-card:hover p {
            color: var(--primary-dark);
        }

        /* Route button */
        .route-btn {
            background: var(--accent);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 1rem;
            position: absolute;
            top: 20px;
            right: 20px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .route-btn:hover {
            background: var(--secondary);
            color: white;
            transform: scale(1.1);
        }

        /* Info Section */
        .info-section {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: var(--shadow);
            margin-top: 30px;
            animation: fadeInUp 1.2s ease forwards;
            transition: var(--transition);
        }

        .info-section:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .info-section h2 {
            color: var(--primary);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--accent);
            font-size: 1.8rem;
            position: relative;
        }

        .info-section h2::after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background: var(--secondary);
            bottom: 0;
            left: 0;
            border-radius: 10px;
        }

        .info-content {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .info-text {
            flex: 2;
            min-width: 300px;
        }

        .info-text p {
            margin-bottom: 15px;
            color: #555;
            line-height: 1.8;
            font-size: 1.05rem;
            position: relative;
            padding-left: 20px;
        }

        .info-text p::before {
            content: "•";
            color: var(--secondary);
            position: absolute;
            left: 0;
            top: 0;
            font-size: 1.2rem;
        }

        .contact-info {
            flex: 1;
            min-width: 300px;
            background: var(--light);
            border-radius: 12px;
            padding: 25px;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 450px;
            margin-left: auto;
        }

        .contact-info:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .contact-item:hover {
            transform: translateX(5px);
        }

        .contact-item i {
            color: var(--secondary);
            font-size: 1.5rem;
            min-width: 40px;
            margin-top: 5px;
            transition: var(--transition);
        }

        .contact-item:hover i {
            transform: scale(1.2);
            color: var(--primary);
        }

        .contact-text h4 {
            color: var(--primary);
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .contact-text p {
            color: #555;
            font-size: 0.95rem;
        }

        /* Scroll hint (hanya untuk mobile) */
        .scroll-hint {
            display: none; /* default: disembunyikan (desktop) */
        }

        @media (max-width: 767px) {
            .content-wrapper {
                flex-direction: column;
            }
            .contact-info {
                flex: 2;
                min-width: 300px;
                background: var(--light);
                border-radius: 12px;
                padding: 25px;
                transition: var(--transition);
                display: flex;
                flex-direction: column;
                justify-content: center;
                max-width: 450px;
                margin-left: auto;
            }

            .map-container, .branches-list {
                min-width: 100%;
            }

            .branches-list {
                max-height: 300px;
                overflow-y: auto;
                padding: 10px;
                background: #fff;
                border-radius: 12px;
                box-shadow: var(--shadow);
            }

            .branch-card {
                margin-bottom: 12px;
            }

            .scroll-hint {
                display: block;
                text-align: center;
                font-size: 1.5rem;
                color: var(--secondary);
                margin-top: -5px;
                animation: bounce 1.5s infinite;
            }

            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(5px); }
            }
        }
    </style>

    <div class="branch-content"  style="margin-top: 40px">
        <h2 class="section-title" >Cabang Kami</h2>
        
        <div class="content-wrapper">
            <div class="map-container">
                <div id="map"></div>
            </div>
            
            <div class="branches-list">
                <div class="branch-card active" data-lat="-6.574552" data-lng="107.766049">
                    <h3><i class="fas fa-map-marker-alt"></i> Pasirkareumbi</h3>
                    <p>Jl. Letnan Jenderal S. Parman, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.574552,107.766049" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.559202" data-lng="107.795116">
                    <h3><i class="fas fa-map-marker-alt"></i> Cinangsi</h3>
                    <p>Jl. Sukamulya Cibogo, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.559202,107.795116" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.560413" data-lng="107.764078">
                    <h3><i class="fas fa-map-marker-alt"></i> Pejuang 45</h3>
                    <p>Jl. Pejuang 45, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.560413,107.764078" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.297561" data-lng="107.820725">
                    <h3><i class="fas fa-map-marker-alt"></i> Pamanukan</h3>
                    <p>Jl. Raya Rancasari, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.297561,107.820725" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.454906" data-lng="107.810753">
                    <h3><i class="fas fa-map-marker-alt"></i> Pagaden</h3>
                    <p>Jl. Jend. Ahmad Yani, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.454906,107.810753" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.564782" data-lng="107.754463">
                    <h3><i class="fas fa-map-marker-alt"></i> Cigadung</h3>
                    <p>Jl. MT. Haryono, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.564782,107.754463" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.568010" data-lng="107.762960">
                    <h3><i class="fas fa-map-marker-alt"></i> Depan PTPN</h3>
                    <p>Jl. Otto Iskandar Dinata, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.568010,107.762960" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.545368" data-lng="107.738344">
                    <h3><i class="fas fa-map-marker-alt"></i> Dangdeur</h3>
                    <p>Jl. Subang - Cidahu, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.545368,107.738344" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.560453585478372" data-lng="107.76984101811108">
                    <h3><i class="fas fa-map-marker-alt"></i> Depan REGIO</h3>
                    <p>Jl. Otto Iskandar Dinata, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.560453585478372,107.76984101811108" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.510544" data-lng="107.800784">
                    <h3><i class="fas fa-map-marker-alt"></i> Sembung</h3>
                    <p>Jl. Pagaden - Subang, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.510544,107.800784" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
                <div class="branch-card" data-lat="-6.559982" data-lng="107.779163">
                    <h3><i class="fas fa-map-marker-alt"></i> Rawabadak</h3>
                    <p>Jl. Kapten Hanafiah, Subang</p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-6.559982,107.779163" target="_blank" class="route-btn">
                        <i class="fas fa-directions"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Chevron down hint hanya muncul di mobile -->
        <div class="scroll-hint">
            <i class="fas fa-chevron-down"></i>
        </div>

        <div class="info-section fade-in">
            <h2>Informasi Cabang Teh Boston</h2>
            <div class="info-content">
                <div class="info-text">
                    <p>Teh Boston memiliki 14 cabang strategis di wilayah Subang yang siap melayani Anda. Setiap cabang kami menyediakan berbagai varian minuman teh berkualitas dengan bahan-bahan pilihan.</p>
                    <p>Jam operasional cabang Teh Boston adalah setiap hari dari pukul 09:00 hingga 17:00 WIB. Kami selalu menjaga kebersihan dan kualitas produk di setiap cabang.</p>
                    <p>Semua cabang Teh Boston memiliki standar pelayanan yang sama sehingga Anda akan mendapatkan pengalaman minum teh yang konsisten di mana pun lokasinya.</p>
                </div>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div class="contact-text">
                            <h4>Jam Operasional</h4>
                            <p>Setiap Hari: 09:00 - 17:00 WIB</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div class="contact-text">
                            <h4>Telepon</h4>
                            <p>+62 852 2212 4176 (Yadi)</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contact-text">
                            <h4>Email</h4>
                            <p>tehbostonofficial@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
