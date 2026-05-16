<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nota Barang Keluar</title>
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                margin: 0;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: auto;
            padding: 10px;
        }

        .nota-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .nota-body {
            margin-top: 10px;
        }

        .nota-body table {
            width: 100%;
            border-collapse: collapse;
        }

        .nota-body td {
            padding: 4px 0;
        }

        .nota-footer {
            text-align: center;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .btn-print {
            text-align: center;
            margin-top: 10px;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="nota">
        <div class="nota-header">
            <h2 style="margin: 0;">Distribution Centre Teh Boston</h2>
            <small>Nota Pengeluaran Barang</small>
        </div>

        <div class="nota-body">
            <table>
                <tr>
                    <td><strong>ID:</strong></td>
                    <td>{{ $header->id_transaksi }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($header->tanggal_transaksi)->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Franchise:</strong></td>
                    <td>{{ $data[0]->nama_franchise }}</td>
                </tr>
                <tr>
                    <td><strong>Alamat:</strong></td>
                    <td>{{ $data[0]->alamat_usaha }}</td>
                </tr>
            </table>

            <hr style="border-top: 1px dashed #000; margin: 8px 0;">

            <table>
                <thead>
                    <tr>
                        <td><strong>Nama Paket</strong></td>
                        <td><strong>Qty</strong></td>
                        <td><strong>Harga</strong></td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $paketList = $data->unique('id_pengeluaran');
                    @endphp

                    @foreach ($paketList as $item)
                    <tr>
                        <td>{{ $item->nama_paket }}</td>
                        <td>{{ $item->jumlah_paket }}</td>
                        <td>Rp. {{ number_format($item->subtotal_paket, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr style="border-top: 1px dashed #000; margin: 8px 0;">

            <table>
                <thead>
                    <tr>
                        <td><strong>Nama Bahan Baku</strong></td>
                        <td><strong>Qty</strong></td>
                        <td><strong>Satuan</strong></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->nama_bahan }}</td>
                        <td>{{ $item->jumlah_bahan }}</td>
                        <td>{{ $item->satuan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                <p>Total : Rp. {{ number_format($total->total, 0, ',', '.') }}<p>
            </div>
        </div>

        <div class="nota-footer">
            <p>Terima kasih!</p>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });

        window.onafterprint = function () {
            window.location.href = "{{ url('gudang/riwayat#keluar') }}";
        };
    </script>
</body>
</html>
