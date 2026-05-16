@extends('kasir.template_kasir')

@section('title', 'Pilih Pembayaran')
@section('page-title', 'Pilih Pembayaran')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow border p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-2">Pilih Metode Pembayaran</h2>

        <p class="text-slate-500 mb-5">
            Total Harga : <strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
        </p>

        <div class="mb-5">
            <h3 class="font-bold mb-3">Detail Paket</h3>

            <div class="space-y-3">
            @foreach($detail as $row)
                <div class="border border-stone-200 rounded-2xl p-3">
                    
                    <div class="flex justify-between">
                        <div>
                            <p class="font-semibold">{{ $row['nama_paket'] }}</p>
                            <p class="text-sm text-slate-500">
                                Jumlah Paket : {{ $row['jumlah_paket'] }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="font-bold">
                                Rp {{ number_format($row['harga'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-2 text-sm text-slate-500">
                        @foreach($row['bahan'] as $bahan)
                            <div>
                                • {{ $bahan['nama_bahan'] }} 
                                ({{ $bahan['jumlah'] }} {{ $bahan['satuan'] }})
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
            </div>
        </div>

        <div class="space-y-3">
            <form action="{{ route('pembayaran.tunai', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                <button class="w-full bg-green-500 hover:bg-green-600 text-white py-4 rounded-2xl font-semibold">
                    Bayar Tunai
                </button>
            </form>

            <button type="button"
                id="pay-button"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-4 rounded-2xl font-semibold">
                Transfer
            </button>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
document.getElementById('pay-button').addEventListener('click', function () {
    fetch("{{ route('pembayaran.midtrans', $transaksi->id_transaksi) }}", {
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
                    window.location.href = "/pembayaran/success/{{ $transaksi->id_transaksi }}";
                },
                onPending: function(result) {
                    window.location.href = "/riwayat/pesanan";
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
        console.log(error);
        alert('Terjadi kesalahan saat menghubungi Midtrans.');
    });
});
</script>
@endsection