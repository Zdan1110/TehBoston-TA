<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #1B5E20;
            --secondary: #FF9800;
            --light-bg: #f5f5f5;
            --white: #ffffff;
            --text-dark: #333333;
            --text-light: #666666;
            --border-color: #e0e0e0;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --header-gradient: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.6;
            padding-bottom: 80px;
        }
        
        .container {
            max-width: 100%;
            padding: 0 15px;
        }
        
        /* Header */
        .header {
            background: var(--header-gradient);
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .logo img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }
        
        .logo-placeholder {
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .app-title {
            display: flex;
            flex-direction: column;
        }
        
        .app-title h1 {
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .app-title span {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .header-stats {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 12px;
            border-radius: 20px;
            gap: 6px;
            backdrop-filter: blur(5px);
        }
        
        .header-stats i {
            font-size: 0.9rem;
        }
        
        .header-stats span {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }
        
        .btn {
            flex: 1;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-dark);
            color: white;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-small {
            padding: 8px 12px;
            font-size: 0.85rem;
            flex: none;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn:hover:not(:disabled) {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* QR Cards */
        .qr-cards {
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: rgba(255, 255, 255, 0.15);
        }
        
        .qr-card {
            background-color: rgba(115, 115, 115, 0.30);;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.10);
            padding: 15px;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }
        
        .qr-card:hover {
            transform: translateY(-3px);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-title {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 1.1rem;
        }
        
        .card-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s ease;
        }
        
        .edit-btn:hover {
            color: var(--primary-dark);
        }
        
        .delete-btn:hover {
            color: #e53935;
        }
        
        .card-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .card-field {
            margin-bottom: 8px;
        }
        
        .field-label {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 3px;
        }
        
        .field-value {
            font-size: 0.9rem;
            font-weight: 500;
            word-break: break-word;
        }
        
        .qr-image {
            grid-column: 1 / -1;
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
        }
        
        .qr-image img {
            max-width: 120px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--border-color);
        }
        
        .empty-state p {
            font-size: 1.1rem;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .modal-title {
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--primary-dark);
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text-light);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.95rem;
        }
        
        .input-with-button {
            display: flex;
            gap: 10px;
        }
        
        .input-with-button .form-input {
            flex: 1;
        }
        
        .qr-preview {
            text-align: center;
            padding: 15px;
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            margin-top: 10px;
            background-color: #f9f9f9;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .qr-preview img {
            max-width: 180px;
            height: auto;
            border-radius: 5px;
        }
        
        .preview-info {
            margin-top: 10px;
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        .preview-warning {
            color: var(--secondary);
            font-weight: 500;
            margin-top: 5px;
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-cancel {
            background-color: #f5f5f5;
            color: var(--text-dark);
        }
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .card-content {
                grid-template-columns: 1fr;
            }
            
            .input-with-button {
                flex-direction: column;
            }
            
            .app-title h1 {
                font-size: 1.2rem;
            }
            
            .header-stats span {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 360px) {
            .logo-section {
                gap: 8px;
            }
            
            .logo {
                width: 36px;
                height: 36px;
            }
            
            .app-title h1 {
                font-size: 1.1rem;
            }
            
            .app-title span {
                font-size: 0.7rem;
            }
            
            .header-stats {
                padding: 4px 8px;
            }
        }

        /* Modal Scanner */
        #qr-modal {
            position: fixed; /* selalu fixed */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* background hitam transparan */
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999; /* pastikan di atas semua elemen */
        }

        #qr-modal .bg-white {
            position: relative;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        #qr-modal.show {
            display: flex; /* hanya tampil kalau ada class "show" */
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <div class="logo">
                        <!-- Ganti dengan path logo Anda -->
                        <!-- <img src="/public/upload/gambar/logo.png" alt="Logo"> -->
                        <div class="logo-placeholder">
                            <i class="fas fa-qrcode"></i>
                        </div>
                    </div>
                    <div class="app-title">
                        <h1>QR Manager</h1>
                        <span>Kelola Kode QR Anda</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="action-buttons">
            <button class="btn btn-primary" id="addBtn">
                <i class="fas fa-plus"></i> Tambah
            </button>
            <button class="btn btn-secondary" id="startQrScanner">
                <i class="fas fa-qrcode"></i> Scan QR
            </button>
        </div>
        @if(session('success'))
            <div class="alert-box" style="background-color: #d4edda; color: #155724; padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #c3e6cb; position: relative;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button class="close-alert" style="position: absolute; top: 0px; right: 10px; background: none; border: none; font-size: 32px; color: inherit; cursor: pointer;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box" style="background-color: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 6px; margin-bottom: 15px; border: 1px solid #f5c6cb; position: relative;">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button class="close-alert" style="position: absolute; top: 0px; right: 10px; background: none; border: none; font-size: 32px; color: inherit; cursor: pointer;">&times;</button>
            </div>
        @endif
        
        
        <div class="qr-cards" id="qrCards">
            @forelse($qrs as $qr)
            <div class="qr-card">
                <div class="card-header">
                    <div class="card-title">ID: {{ $qr->id_qr }}</div>
                        <div class="card-actions">
                            <button type="button" class="action-btn edit-btn" data-id="{{ $qr->id_qr }}" data-url="{{ $qr->url }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('qr.delete', $qr->id_qr) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn delete-btn" onclick="return confirm('Yakin hapus QR ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card-content">
                        <div class="card-field">
                            <div class="field-label">URL</div>
                            <div class="field-value">
                                <a href="{{ $qr->url }}" target="_blank">{{ $qr->url }}</a>
                            </div>
                        </div>

                        <div class="card-field">
                            <div class="field-label">Dibuat</div>
                            <div class="field-value">{{ $qr->created_at }}</div>
                        </div>

                        <div class="card-field">
                            <div class="field-label">Diupdate</div>
                            <div class="field-value">{{ $qr->updated_at }}</div>
                        </div>

                        <div class="qr-image">
                            <img src="{{ asset('uploads/qrcode/' . $qr->qr_img) }}" alt="QR Image">
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-qrcode"></i>
                    <p>Belum ada QR Code yang tersimpan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div id="editQrModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit QR Code</h3>
                    <button class="close-btn" id="closeEditModal">&times;</button>
                </div>
                <form id="editQrForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="editUuid">ID QR</label>
                        <input type="text" id="editUuid" name="id_qr" class="form-input" style="background-color: rgba(128, 128, 128, 0.5);" readonly>
                    </div>

                    <div class="form-group">
                        <label for="editUrl">URL</label>
                        <input type="text" id="editUrl" name="url" class="form-input" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
        
        <!-- Modal QR -->
        <div id="qr-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <div id="reader" style="width: 300px; height: 300px;"></div>
            </div>
        </div>
        <!-- Modal untuk Tambah/Edit -->
        <div class="modal" id="qrModal">
            <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah QR Code</h3>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="qrForm" action="{{ route('qr.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_qr" id="uuid">
                
                <div class="form-group">
                    <label for="uuid">ID QR</label>
                    <input type="text" class="form-input" id="uuidDisplay" placeholder="Klik tombol untuk generate ID" readonly>
                    <button type="button" id="generateUuid">Generate</button>
                </div>

                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" name="url" class="form-input" id="url" placeholder="Masukkan URL untuk QR Code">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
    
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // === Tambah QR ===
    const addBtn = document.getElementById('addBtn');
    const addModal = document.getElementById('qrModal');
    const closeAddModal = document.getElementById('closeModal');

    addBtn.addEventListener('click', () => {
        addModal.style.display = 'flex';
    });

    closeAddModal.addEventListener('click', () => {
        addModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === addModal) {
            addModal.style.display = 'none';
        }
    });

    document.getElementById('generateUuid').addEventListener('click', function() {
        const randomId = 'qr' + Math.floor(Math.random() * 100000); 
        document.getElementById('uuid').value = randomId;
        document.getElementById('uuidDisplay').value = randomId;
    });

    // --- Modal Scanner ---
    let html5QrCode;
    const scannerModal = document.getElementById('qr-modal');

    function startQrScanner() {
        scannerModal.classList.add('show');
        scannerModal.classList.remove('hidden');

        html5QrCode = new Html5Qrcode("reader");

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const backCamera = devices.find(device => device.label.toLowerCase().includes('back')) || devices[0];

                html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    qrCodeMessage => {
                        console.log("QR hasil:", qrCodeMessage);
                        window.location.href = `/admin/qr/${encodeURIComponent(qrCodeMessage)}`;
                        stopQrScanner();
                    }
                ).catch(err => {
                    alert("Tidak bisa membuka kamera: " + err);
                    stopQrScanner();
                });
            } else {
                alert("Tidak ada kamera terdeteksi.");
            }
        });

        scannerModal.addEventListener('click', (event) => {
            if (event.target === scannerModal) {
                stopQrScanner();
            }
        });
    }

    function stopQrScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
                scannerModal.classList.remove('show');
                scannerModal.classList.add('hidden');
            });
        } else {
            scannerModal.classList.remove('show');
            scannerModal.classList.add('hidden');
        }
    }

    document.getElementById('startQrScanner').addEventListener('click', () => {
        startQrScanner();
    });
</script>
    <script>
        // === Edit QR ===
        const editBtns = document.querySelectorAll('.edit-btn');
        const editModal = document.getElementById('editQrModal');
        const closeEditModal = document.getElementById('closeEditModal');

        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const url = btn.getAttribute('data-url');

                // isi ke input form
                document.getElementById('editUuid').value = id;
                document.getElementById('editUrl').value = url;

                // ubah action form sesuai id
                document.getElementById('editQrForm').action = `/admin/qr/${id}`;

                // tampilkan modal
                editModal.style.display = 'flex';
            });
        });

        closeEditModal.addEventListener('click', () => {
            editModal.style.display = 'none';
        });

        // Tutup notifikasi saat tombol X diklik
        document.querySelectorAll('.close-alert').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
    </script>
</body>
</html>