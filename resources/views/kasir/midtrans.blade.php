@extends('kasir.template_kasir')

@section('title', 'Pembayaran Midtrans')
@section('page-title', 'Pembayaran Midtrans')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-3xl shadow border p-6 w-full max-w-md text-center">
        <h2 class="text-2xl font-bold mb-2">Pembayaran Transfer</h2>
        <p class="text-slate-500 mb-5">
            Total: <strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
        </p>

        <button id="pay-button" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-4 rounded-2xl font-semibold">
            Bayar Sekarang
        </button>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = "/pembayaran/success/{{ $transaksi->id_transaksi }}";
        },
        onPending: function(result) {
            window.location.href = "/orderstok";
        },
        onError: function(result) {
            alert('Pembayaran gagal.');
        },
        onClose: function() {
            alert('Kamu menutup popup pembayaran.');
        }
    });
};
</script>
@endsection