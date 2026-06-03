<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Toko Teh Boston</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f0f9eb 0%, #e6f4f1 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .nota-container {
            background:rgb(240, 240, 240);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact; /* untuk browser non-Webkit */
        }
        
        .nota-header {
            background:rgb(240, 240, 240);
            padding: 25px 30px;
            text-align: center;
            position: relative;
            color: black;
        }
        
        .logo {
            font-size: 3.5rem;
            margin-bottom: 10px;
            color: #e0f7e0;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }
        
        .nota-header h1 {
            font-size: 2.2rem;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .nota-header p {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .customer-info {
            padding: 20px 30px;
            background: #f8fafc;
            border-bottom: 2px dashed #e2e8f0;
            display: flex;
            align-items: center;
        }
        
        .customer-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg,rgb(0, 0, 0) 0%,rgb(0, 0, 0) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .customer-details {
            flex: 1;
        }
        
        .customer-name {
            font-size: 1.4rem;
            font-weight: 600;
            color:rgb(0, 0, 0);
            margin-bottom: 5px;
        }
        
        .customer-id {
            font-size: 0.9rem;
            color: #718096;
        }
        
        .transaction-info {
            padding: 15px 30px;
            background: #f1f8e9;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            border-bottom: 2px dashed #c5e1a5;
        }
        
        .info-item {
            margin: 8px 0;
            font-size: 0.95rem;
            color:rgb(0, 0, 0);
            display: flex;
            align-items: center;
        }
        
        .info-item i {
            margin-right: 8px;
            color:rgb(0, 0, 0);
            width: 20px;
            text-align: center;
        }
        
        .nota-table {
            width: 100%;
            border-collapse: collapse;
            padding: 0 30px;
        }
        
        .nota-table th {
            background: #edf2f7;
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .nota-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #edf2f7;
            color: #4a5568;
        }
        
        .nota-table tr:last-child td {
            border-bottom: none;
        }
        
        .nota-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .nota-total {
            padding: 20px 30px;
            text-align: right;
            background: #f1f8e9;
            font-size: 0.5rem;
            font-weight: 700;
            color:rgb(0, 0, 0);
            border-top: 2px dashed #c5e1a5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .total-label {
            background: rgb(255, 255, 255)
            display: flex;
            align-items: center;
        }
        
        .total-label i {
            margin-right: 10px;
            font-size: 1.5rem;
        }
        
        .total-amount {
            font-size: 0.5rem;
        }
        
        .payment-method {
            padding: 15px 30px;
            background: #f8fafc;
            border-top: 2px dashed #e2e8f0;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        
        .payment-method i {
            margin-right: 10px;
            color: #5a9e6f;
            font-size: 1.2rem;
        }
        
        .nota-footer {
            padding: 20px 30px;
            text-align: center;
            background:rgb(255, 255, 255);
            color: black;
        }
        
        .footer-text {
            font-size: 10px;
            white-space: nowrap;
            letter-spacing: 0.2px;
            margin-bottom: 10px;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.2rem;
            transition: transform 0.3s;
        }
        
        .social-icons a:hover {
            transform: translateY(-3px);
        }
        
        .qr-code {
            margin-top: 15px;
            padding: 10px;
            background: white;
            border-radius: 8px;
            display: inline-block;
        }

        .btn-print {
            text-align: center;
            margin-top: 10px;
        }

        .btn-print a {
            display: inline-block;
            padding: 8px 14px;
            background: #198754;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        

        @page {
            size: 58mm auto; /* Lebar 58mm, tinggi fleksibel */
            margin: 0;
        }

        @media print {
            html, body {
                width: 58mm;
                margin: 0;
                padding: 0;
                font-size: 10px;
                background: white;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .nota-container {
                width: 58mm;
                margin: 0 auto;
                padding: 0;
            }


            .nota-header,
            .customer-info,
            .transaction-info,
            .nota-total,
            .nota-footer,
            .nota-table th,
            .nota-table tr:nth-child(even) {
                background: rgb(255, 255, 255) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .btn-print {
                display: none;
            }

            /* Hilangkan elemen tidak penting */
            .social-icons,
            .qr-code {
                display: none;
            }

            table, th, td {
                font-size: 10px !important;
            }

            /* Hilangkan elemen visual non-print */
            .no-print, .qr-code, .social-icons {
                display: none !important;
            }

            .nota-table {
                padding: 0 10px !important;
                margin: 0 auto;
                width: calc(100% - 20px); /* agar tidak full 58mm, menyisakan margin */
            }

            .nota-table th,
            .nota-table td {
                padding-left: 5px !important;
                padding-right: 5px !important;
            }

        }

        
        @media (max-width: 500px) {
            .customer-info {
                flex-direction: column;
                text-align: center;
            }
            
            .customer-icon {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .transaction-info {
                flex-direction: column;
            }
            
            .nota-table {
                font-size: 0.9rem;
            }
            
            .nota-table th, .nota-table td {
                padding: 8px 5px;
            }
            
            .nota-total {
                font-size: 1.1rem;
            }
            
            .total-amount {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <div class="nota-header">
            <div class="logo">
                <i class="fas fa-mug-hot" style="color:black"></i>
            </div>
            <h1>TEH BOSTON</h1>
            <p>Jl. MT. Haryono Gang Abimanyu Blok Sekarjaya. Kel. Cigadung Kec. Subang | Telp: +62 8514-1606-776</p>
        </div>
        
        @php
            use Carbon\Carbon;

            Carbon::setLocale('id');
            $tanggal = Carbon::parse($data->tanggal);
        @endphp
        <div class="transaction-info">
            <div class="info-item">
                <i class="far fa-calendar"></i>
                <span>{{ $tanggal->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-item">
                <i class="far fa-clock"></i>
                <span>{{ $tanggal->format('H:i') }}</span>
            </div>
            <div class="info-item">
                <i class="far fa-calendar-check"></i>
                <span>{{ $tanggal->translatedFormat('l') }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-receipt"></i>
                No. Nota: {{ $data->id_penjualan }}
            </div>
        </div>
        
        <table class="nota-table">
            <thead>
                <tr>
                    <th style="font-size: 10px;">Produk</th>
                    <th style="font-size: 10px;">Jumlah</th>
                    <th style="font-size: 10px;">Harga</th>
                    <th style="font-size: 10px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datadetail as $detail)
                <tr>
                    <td style="font-size: 10px;">{{ $detail->nama_produk }}</td>
                    <td style="font-size: 10px;">{{ $detail->jumlah }}</td>
                    <td style="font-size: 10px; white-space: nowrap;">Rp {{ number_format($detail->harga / $detail->jumlah, 0, ',', '.') }}</td>
                    <td style="font-size: 10px; white-space: nowrap;">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="nota-total" style="font-size: 10px; display: flex; justify-content: space-between; align-items: center; padding: 8px 30px; border-top: 2px dashed #c5e1a5;">
            <div>
                <p style="font-size: 10px; white-space: nowrap;">TOTAL PEMBAYARAN:</p>
            </div>
            <div>
                <p style="font-size: 10px; white-space: nowrap;">Rp {{ number_format($data->harga, 0, ',', '.') }}</p>
            </div>
        </div>
        
        
        <div class="nota-footer">
            <p class="footer-text">Terima kasih atas kunjungan Anda!</p>
            <p class="footer-text">Teh Boston - Menyegarkan Sejak 2023</p>
        </div>

        <div class="btn-print">
            <a href="{{ url('gudang/riwayat#keluar') }}">Kembali</a>
        </div>
    </div>

    <script>
        let alreadyRedirect = false;

        function kembaliKeStruk() {
            if (alreadyRedirect) return;
            alreadyRedirect = true;

            setTimeout(function () {
                window.location.href = "{{ url('/kasir') }}";
            }, 1000);
        }

        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 500);
        });

        window.onafterprint = function () {
            kembaliKeStruk();
        };
    </script>
</body>
</html>