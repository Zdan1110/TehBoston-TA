@extends('kasir.template_kasir')

@section('title', 'Riwayat Pesanan')
@section('page-title', 'Riwayat Pesanan')

@section('content')
<div class="p-4">

    @if(session('success'))
        <div class="mb-4 flex items-center justify-between bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow">
            <div>{{ session('success') }}</div>
            <button onclick="this.parentElement.remove()" class="ml-4 font-bold hover:text-green-900">✕</button>
        </div>
    @endif

    <div class="w-full overflow-x-auto bg-white rounded-2xl shadow border border-gray-200">
        <table class="min-w-[900px] md:min-w-full text-xs md:text-sm text-center">
            
            {{-- Header --}}
            <thead class="bg-green-100 text-gray-700 font-semibold">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">No</th>
                    <th class="px-4 py-3 whitespace-nowrap">Tanggal & Jam</th>
                    <th class="px-4 py-3 whitespace-nowrap">Nama Paket</th>
                    <th class="px-4 py-3 whitespace-nowrap">Bahan Baku</th>
                    <th class="px-4 py-3 whitespace-nowrap">Total</th>
                    <th class="px-4 py-3 whitespace-nowrap">Metode Pembayaran</th>
                    <th class="px-4 py-3 whitespace-nowrap">Pembayaran</th>
                    <th class="px-4 py-3 whitespace-nowrap">Status</th>
                </tr>
            </thead>

            {{-- Body --}}
            <tbody class="divide-y">
                @forelse($pesanan as $row)
                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3 whitespace-nowrap">{{ $loop->iteration }}</td>

                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($row['tanggal_transaksi'])->format('d-m-Y H:i') }}
                        </td>

                        {{-- Paket --}}
                        <td class="px-4 py-3 text-left whitespace-nowrap">
                            @foreach($row['paket'] as $paket)
                                <div class="mb-1">
                                    <span class="font-semibold">• {{ $paket['nama_paket'] }}</span>
                                    <span class="text-gray-500">
                                        ({{ $paket['jumlah_paket'] }})
                                    </span>
                                </div>
                            @endforeach
                        </td>

                        {{-- Bahan --}}
                        <td class="px-4 py-3 text-left whitespace-nowrap">
                            @foreach($row['paket'] as $paket)
                                <div class="mb-2">
                                    <div class="font-semibold text-sm text-gray-700">
                                        {{ $paket['nama_paket'] }}
                                    </div>

                                    @foreach($paket['bahan'] as $bahan)
                                        <div class="text-gray-500 text-sm">
                                            • {{ $bahan['nama_bahan'] }}
                                            ({{ $bahan['jumlah_bahan'] }} {{ $bahan['satuan'] }})
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </td>

                        <td class="px-4 py-3 font-semibold text-emerald-600 whitespace-nowrap">
                            Rp {{ number_format($row['total'], 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $row['metode_pembayaran'] ?? '-' }}
                        </td>

                        {{-- Status Pembayaran --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($row['status_pembayaran'] == 'settlement')
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                    Lunas
                                </span>

                            @elseif($row['status_pembayaran'] == 'pending')
                                <div class="flex flex-col items-center gap-2">
                                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                        Pending
                                    </span>

                                    @if(strtolower($row['metode_pembayaran']) == 'transfer')
                                        <button type="button"
                                            class="btn-bayar-midtrans px-3 py-1 text-xs rounded-lg bg-blue-500 hover:bg-blue-600 text-white shadow"
                                            data-id="{{ $row['id_transaksi'] }}">
                                            Bayar
                                        </button>
                                    @endif
                                </div>

                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                    {{ $row['status_pembayaran'] ?? '-' }}
                                </span>
                            @endif
                        </td>

                        {{-- Status Pesanan --}}
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($row['status_transaksi'] == 'Sedang Di Proses')
                                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">
                                    Sedang Di Proses
                                </span>
                            @elseif($row['status_transaksi'] == 'Dikirim')
                                <span class="px-3 py-1 text-xs rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                                    Dikirim
                                </span>
                            @elseif($row['status_transaksi'] == 'Selesai')
                                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                    Selesai
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                    {{ $row['status_transaksi'] ?? '-' }}
                                </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-6 text-gray-400 whitespace-nowrap">
                            Belum ada riwayat pesanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.btn-bayar-midtrans');

        if (!button) return;

        const idTransaksi = button.dataset.id;

        fetch(`/pembayaran/midtrans/${idTransaksi}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = `/pembayaran/success/${idTransaksi}`;
                    },
                    onPending: function(result) {
                        window.location.reload();
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal.');
                    },
                    onClose: function() {
                        alert('Kamu menutup popup pembayaran.');
                    }
                });
            } else {
                alert(data.message || 'Gagal membuat pembayaran.');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Terjadi kesalahan saat menghubungi Midtrans.');
        });
    });
</script>
@endsection