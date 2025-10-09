<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Status Calon Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'review': '#ca8a04',
                        'survey': '#3b82f6',
                        'diterima': '#16a34a',
                        'pembayaran': '#8b5cf6',
                        'booth': '#ec4899',
                        'ditolak': '#dc2626',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes pulseScale {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .pulse-icon {
            animation: pulseScale 2s infinite;
            transform-origin: center;
        }
        .track-container {
            display: flex;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f3f4f6;
            padding-bottom: 16px;
        }
        .track-container::-webkit-scrollbar {
            height: 6px;
        }
        .track-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 3px;
        }
        .status-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 90px;
            position: relative;
        }
        .status-line {
            flex: 1;
            border-top: 2px solid #d1d5db;
            margin-top: 28px;
        }
        .status-icon {
            width: 2.5rem;
            height: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .status-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            line-height: 1.2;
            padding: 0 4px;
        }

        /* Warna untuk status indicator */
        .status-review { background-color: #fef9c3; color: #854d0e; }
        .status-survey { background-color: #dbeafe; color: #1e40af; }
        .status-diterima { background-color: #dcfce7; color: #166534; }
        .status-pembayaran { background-color: #ede9fe; color: #5b21b6; }
        .status-booth { background-color: #fce7f3; color: #9d174d; }
        .status-ditolak { background-color: #fee2e2; color: #b91c1c; }
        .status-default { background-color: #f3f4f6; color: #4b5563; }

        /* Two-column layout */
        .status-container-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 1000px;
            margin: 0 auto;
            align-items: center;
        }

        @media (min-width: 768px) {
            .status-container-wrapper {
                flex-direction: row;
                align-items: flex-start;
                justify-content: center;
            }

            .status-card, .payment-card, .payment-checking-card {
                flex: 1;
                max-width: 480px;
            }

            .payment-card, .payment-checking-card {
                margin-top: 0;
            }
        }

        /* Payment Card */
        .payment-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .payment-card-header {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1.125rem;
            display: flex;
            align-items: center;
        }

        .payment-card-header i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .payment-card-body {
            padding: 1.5rem;
        }

        .payment-amount {
            background: #f9fafb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px dashed #d1d5db;
        }

        .payment-amount-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .payment-amount-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8b5cf6;
        }

        /* Modern Bank Selection */
        .bank-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
            margin-bottom: 1.5rem;
        }

        .bank-option {
            position: relative;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .bank-option:hover {
            border-color: #c7d2fe;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .bank-option.selected {
            border-color: #8b5cf6;
            background-color: #f5f3ff;
            box-shadow: 0 0 0 3px #ede9fe;
        }

        .bank-radio {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .bank-option .checkmark {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background-color: #8b5cf6;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            border: 2px solid white;
        }

        .bank-option.selected .checkmark {
            display: flex;
        }

        /* Modern File Upload */
        .modern-upload {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .modern-upload:hover {
            border-color: #8b5cf6;
            background: #f5f3ff;
        }

        .modern-upload.active {
            border-color: #8b5cf6;
            background: #f5f3ff;
        }

        .upload-icon {
            font-size: 2rem;
            color: #8b5cf6;
            margin-bottom: 1rem;
        }

        .upload-text {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1e293b;
        }

        .upload-hint {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .upload-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #8b5cf6;
            color: white;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .upload-btn:hover {
            background: #7c3aed;
        }

        .preview-container {
            margin-top: 1rem;
            display: none;
        }

        .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 1rem;
            border: 1px solid #e2e8f0;
        }

        .file-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 0.75rem;
            border-radius: 8px;
            margin-top: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .file-details {
            display: flex;
            align-items: center;
        }

        .file-icon {
            margin-right: 0.75rem;
            color: #8b5cf6;
        }

        .file-name {
            font-weight: 500;
            color: #1e293b;
            word-break: break-all;
            text-align: left;
        }

        .file-remove {
            color: #ef4444;
            cursor: pointer;
            margin-left: 1rem;
        }

        /* Styling for the new payment checking card */
        .payment-checking-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            text-align: center;
            padding: 2.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .payment-checking-icon {
            font-size: 4rem;
            color: #ca8a04; /* Yellow for "checking" */
            margin-bottom: 1.5rem;
            animation: pulseScale 2s infinite; /* Add pulse animation */
        }

        .payment-checking-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        .payment-checking-message {
            font-size: 1rem;
            color: #666;
            line-height: 1.5;
        }
        .hidden {
    display: none;
}
    </style>
</head>
<body class="bg-gray-50">

<div class="flex flex-col items-center min-h-screen w-full p-4 sm:p-6">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-teal-700 mb-6">Status Pendaftaran</h1>

    <div class="status-container-wrapper">
        <div class="status-card bg-white p-6 sm:p-8 rounded-xl shadow-md">
            <div class="mb-6">
                <p class="mb-2 text-gray-700"><strong class="text-gray-800">ID:</strong> {{ $calon->id_calon }}</p>
                <p class="text-gray-700"><strong class="text-gray-800">Nama Lengkap:</strong> {{ $calon->nama_lengkap }}</p>
            </div>

            <div class="track-container">
                <div class="flex items-start min-w-max">
                    <div class="status-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="status-icon {{ $calon->status == 'Review Dokumen' ? 'pulse-icon' : '' }}"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="
                                color: {{ $calon->status == 'Review Dokumen' ? '#ca8a04' : '#9ca3af' }};
                                opacity: {{ in_array($calon->status, ['Review Dokumen', 'Survey Lokasi', 'Diterima', 'Pembayaran', 'Pembuatan Booth']) ? '1' : '0.4' }};
                            "
                            title="Review Dokumen"
                        >
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span class="status-label"
                            style="
                                color: {{ $calon->status == 'Review Dokumen' ? '#ca8a04' : '#6b7280' }};
                                opacity: {{ in_array($calon->status, ['Review Dokumen', 'Survey Lokasi', 'Diterima', 'Pembayaran', 'Pembuatan Booth']) ? '1' : '0.6' }};
                            "
                        >Review Dokumen</span>
                    </div>

                    <div class="status-line"></div>

                    <div class="status-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="status-icon {{ $calon->status == 'Survey Lokasi' ? 'pulse-icon' : '' }}"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="
                                color: {{ $calon->status == 'Survey Lokasi' ? '#3b82f6' : '#9ca3af' }};
                                opacity: {{ in_array($calon->status, ['Survey Lokasi', 'Diterima', 'Pembayaran', 'Pembuatan Booth']) ? '1' : '0.4' }};
                            "
                            title="Survey Lokasi"
                        >
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span class="status-label"
                            style="
                                color: {{ $calon->status == 'Survey Lokasi' ? '#3b82f6' : '#6b7280' }};
                                opacity: {{ in_array($calon->status, ['Survey Lokasi', 'Diterima', 'Pembayaran', 'Pembuatan Booth']) ? '1' : '0.6' }};
                            "
                        >Survey Lokasi</span>
                    </div>

                    <div class="status-line"></div>

                    <div class="status-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="status-icon {{ $calon->status == 'Pembayaran' ? 'pulse-icon' : '' }}"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="
                                color: {{ $calon->status == 'Pembayaran' ? '#8b5cf6' : '#9ca3af' }};
                                opacity: {{ in_array($calon->status, ['Pembayaran', 'Pembuatan Booth']) ? '1' : '0.4' }};
                            "
                            title="Pembayaran"
                        >
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span class="status-label"
                            style="
                                color: {{ $calon->status == 'Pembayaran' ? '#8b5cf6' : '#6b7280' }};
                                opacity: {{ in_array($calon->status, ['Pembayaran', 'Pembuatan Booth']) ? '1' : '0.6' }};
                            "
                        >Pembayaran</span>
                    </div>

                    <div class="status-line"></div>

                    <div class="status-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="status-icon {{ $calon->status == 'Pembuatan Booth' ? 'pulse-icon' : '' }}"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="
                                color: {{ $calon->status == 'Pembuatan Booth' ? '#ec4899' : '#9ca3af' }};
                                opacity: {{ $calon->status == 'Pembuatan Booth' ? '1' : '0.4' }};
                            "
                            title="Pembuatan Booth"
                        >
                            <path d="M20 9.556V20a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h7.414l2 2H20v5.556"></path>
                            <path d="M10 16v-4h4v4h-4z"></path>
                        </svg>
                        <span class="status-label"
                            style="
                                color: {{ $calon->status == 'Pembuatan Booth' ? '#ec4899' : '#6b7280' }};
                                opacity: {{ $calon->status == 'Pembuatan Booth' ? '1' : '0.6' }};
                            "
                        >Pembuatan Booth</span>
                    </div>

                    <div class="status-line"></div>

                    <div class="status-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="status-icon {{ $calon->status == 'Diterima' ? 'pulse-icon' : '' }}"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="
                                color: {{ $calon->status == 'Diterima' ? '#16a34a' : '#9ca3af' }};
                                opacity: {{ in_array($calon->status, ['Diterima', 'Pembayaran', 'Pembuatan Booth']) ? '1' : '0.4' }};
                            "
                            title="Diterima"
                        >
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9 12l2 2l4 -4" />
                        </svg>
                        <span class="status-label"
                            style="
                                color: {{ $calon->status == 'Diterima' ? '#16a34a' : '#6b7280' }};
                                opacity: {{ in_array($calon->status, ['Diterima', 'Pembayaran', 'Pembuatan Booth']) ? '1' : '0.6' }};
                            "
                        >Diterima</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center mt-6 pt-4 border-t border-gray-200">
                <div class="status-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="status-icon {{ $calon->status == 'Ditolak' ? 'pulse-icon' : '' }}"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        style="
                            color: {{ $calon->status == 'Ditolak' ? '#dc2626' : '#9ca3af' }};
                            opacity: {{ $calon->status == 'Ditolak' ? '1' : '0.4' }};
                        "
                        title="Ditolak"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    <span class="status-label"
                        style="
                            color: {{ $calon->status == 'Ditolak' ? '#dc2626' : '#6b7280' }};
                            opacity: {{ $calon->status == 'Ditolak' ? '1' : '0.6' }};
                        "
                    >Ditolak</span>
                </div>
            </div>

            <div class="mt-6 py-3 px-4 rounded-lg text-center font-medium
                @php
                    $statusClass = 'status-default';
                    if ($calon->status == 'Review Dokumen') $statusClass = 'status-review';
                    elseif ($calon->status == 'Survey Lokasi') $statusClass = 'status-survey';
                    elseif ($calon->status == 'Diterima') $statusClass = 'status-diterima';
                    elseif ($calon->status == 'Pembayaran') $statusClass = 'status-pembayaran';
                    elseif ($calon->status == 'Pembuatan Booth') $statusClass = 'status-booth';
                    elseif ($calon->status == 'Ditolak') $statusClass = 'status-ditolak';
                    echo $statusClass;
                @endphp">
                Status saat ini: <span class="font-bold">{{ $calon->status }}</span>
            </div>

            <div class="mt-8 flex justify-center">
                <a href="/home"
                    class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg
                            transition-colors duration-200 font-medium shadow-sm hover:shadow-md">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        {{-- Conditional rendering for Payment Section --}}
        @if($calon->status == 'Pembayaran')
            @if(is_null($calon->bukti))
                {{-- Show payment form if no proof is uploaded yet --}}
                <div class="payment-card">
                    <div class="payment-card-header">
                        <i class="fas fa-money-bill-wave"></i>
                        Pembayaran Calon
                    </div>

                    <div class="payment-card-body">
                        <div class="payment-amount">
                            <div class="payment-amount-label">Total Pembayaran</div>
                            <div class="payment-amount-value">Rp 28.000.000</div>
                        </div>

                        <form id="uploadForm" action="/uploadbukti/{{ $calon->id_calon }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_calon" value="{{ $calon->id_calon }}">

                           <div class="mb-6">
    <h3 class="font-semibold text-gray-700 mb-3">Pilih Metode Pembayaran</h3>
    <div class="bank-options">
        <label class="bank-option">
            <input type="radio" name="via_pembayaran" value="BCA" class="bank-radio" required>
            <div class="checkmark"><i class="fas fa-check"></i></div>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" alt="BCA" class="bank-logo">
            <div class="bank-name">BCA a.n CAHYADHI ARIF</div>
            <div class="bank-number">2380564231</div>
        </label>

        <label class="bank-option">
            <input type="radio" name="via_pembayaran" value="Cash" class="bank-radio">
            <div class="checkmark"><i class="fas fa-check"></i></div>
            <div class="upload-icon" style="font-size: 2rem; margin-bottom: 0.5rem;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="bank-name">Cash</div>
            <div class="bank-number">Pembayaran Tunai</div>
        </label>
    </div>
    
    <!-- Warning message for cash payment -->
    <div id="cashWarning" class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
            <div>
                <p class="text-yellow-800 text-sm font-medium">
                    Silakan datang ke kantor untuk pembayaran tunai, lalu upload bukti pembayaran setelahnya.
                </p>
            </div>
        </div>
    </div>
</div> <!-- PENUTUP YANG BENAR HARUS DI SINI -->
                            <div class="modern-upload" id="dropArea">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Seret & Jatuhkan file bukti transfer di sini</div>
                                <div class="upload-hint">Atau</div>
                                <label for="modernUpload" class="upload-btn">
                                    <i class="fas fa-folder-open mr-2"></i> Pilih File
                                </label>
                                <input type="file" id="modernUpload" name="bukti" class="hidden" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div class="preview-container" id="previewContainer">
                                    <div class="file-info">
                                        <div class="file-details">
                                            <i class="fas fa-file-alt file-icon"></i>
                                            <div class="file-name" id="displayFileName">file_bukti.jpg</div>
                                        </div>
                                        <i class="fas fa-times file-remove" id="removeFile"></i>
                                    </div>
                                    <img src="" alt="Preview" class="preview-image" id="imagePreview">
                                </div>
                            </div>

                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-700 text-white rounded-lg
                                                                        shadow-md hover:shadow-lg transition-all duration-200 font-medium flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Show "Payment checking" message if proof is uploaded --}}
                <div class="payment-checking-card">
                    <i class="fas fa-hourglass-half payment-checking-icon"></i>
                    <h2 class="payment-checking-title">Pembayaran Sedang Diverifikasi</h2>
                    <p class="payment-checking-message">
                        **Pembayaran Anda sedang dalam proses verifikasi oleh tim kami.**
                        Mohon tunggu konfirmasi lebih lanjut yang akan kami kirimkan melalui email atau notifikasi.
                    </p>
                    <p class="payment-checking-message mt-2 text-sm text-gray-500">
                        Mohon Ditunggu <span class="font-semibold text-gray-700">{{ $calon->nama_lengkap }}</span>
                    </p>
                </div>
            @endif
        @endif {{-- End of the main payment status condition --}}
    </div>
</div>

    <script>
        // Modern Bank Selection
        const bankOptions = document.querySelectorAll('.bank-option');
        // Modern Bank Selection
if (bankOptions.length > 0) {
    bankOptions.forEach(option => {
        option.addEventListener('click', function() {
            bankOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            const radioButton = this.querySelector('.bank-radio');
            radioButton.checked = true;

            // TAMBAHKAN BARIS INI untuk memicu event 'change' secara manual
            radioButton.dispatchEvent(new Event('change'));
        });
    });
}
        // Modern File Upload
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('modernUpload');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const displayFileName = document.getElementById('displayFileName');
        const removeFile = document.getElementById('removeFile');

        // Only add event listeners if the upload section is present in the DOM
        if (dropArea && fileInput && previewContainer && imagePreview && displayFileName && removeFile) {
            // Click to select file
            dropArea.addEventListener('click', (event) => {
                // Only trigger file input if the click is not on the 'Pilih File' button or 'removeFile' icon itself
                if (!event.target.closest('.upload-btn') && !event.target.closest('.file-remove')) {
                    fileInput.click();
                }
            });

            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropArea.classList.add('active');
            }

            function unhighlight() {
                dropArea.classList.remove('active');
            }

            // Handle dropped files
            dropArea.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            });

            // Handle file processing
            function handleFiles(files) {
                if (files.length) {
                    const file = files[0];
                    displayFileName.textContent = file.name;

                    // Show image preview if it's an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                        const fileIconElement = previewContainer.querySelector('.file-icon');
                        if (fileIconElement) {
                            if (file.type === 'application/pdf') {
                                fileIconElement.className = 'fas fa-file-pdf file-icon';
                            } else {
                                fileIconElement.className = 'fas fa-file-alt file-icon';
                            }
                        }
                    }

                    previewContainer.style.display = 'block';
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                }
            }

            // Remove file
            removeFile.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                previewContainer.style.display = 'none';
                imagePreview.src = '';
                displayFileName.textContent = '';
                const fileIconElement = previewContainer.querySelector('.file-icon');
                if (fileIconElement) {
                    fileIconElement.className = 'fas fa-file-alt file-icon';
                }
            });
        }

        // Show warning for cash payment
const paymentRadios = document.querySelectorAll('input[name="via_pembayaran"]');
const cashWarning = document.getElementById('cashWarning');

if (paymentRadios.length > 0 && cashWarning) {
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Cash') {
                cashWarning.classList.remove('hidden');
            } else {
                cashWarning.classList.add('hidden');
            }
        });
    });
}
    </script>

</body>
</html>