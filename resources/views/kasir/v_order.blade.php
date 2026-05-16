@extends('kasir.template_kasir')

@section('title', 'Order Bahan Baku')
@section('page-title', 'Order Bahan Baku')

@section('content')
    <div class="min-h-screen md:p-6">
        <div class="grid grid-cols-12 gap-4 h-[calc(100vh-2rem)]">

            <!-- Main Content -->
            <main class="col-span-12 lg:col-span-9 bg-white rounded-3xl p-4 md:p-5 shadow-sm border border-stone-200 overflow-hidden flex flex-col">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                    <a href="{{ route('stok.franchise') }}"
                    class="w-16 h-10 flex items-center justify-center rounded-xl bg-white hover:bg-gray-200 border border-stone-200">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>

                    <div class="w-full text-center">
                        <h2 class="text-2xl font-bold">Paket Bahan Baku</h2>
                        <p class="text-sm text-slate-500">Pilih Paket Untuk Memesan Bahan Baku</p>
                    </div>


                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:w-80">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/3 -translate-y-1/2 text-slate-400"></i>
                            <input
                                type="text"
                                placeholder="Cari Paket..."
                                class="w-full pl-11 pr-4 py-3 rounded-2xl bg-stone-100 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-amber-400"
                            >
                        </div>

                        <button type="button"
                                onclick="openOrderDetail()"
                                class="lg:hidden w-12 h-12 rounded-2xl bg-green-500 text-white flex items-center justify-center shadow">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="overflow-y-auto pr-1">
                    <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @forelse($paket as $row)
                            @php
                                $bahanList = $row->bahan ? explode(', ', $row->bahan) : [];
                                $jumlahList = $row->jumlah ? explode(', ', $row->jumlah) : [];
                                $satuanList = $row->satuan ? explode(', ', $row->satuan) : [];
                            @endphp

                            <div class="bg-white border border-stone-200 rounded-3xl p-3 shadow-sm hover:shadow-md transition flex flex-col h-full">
                                
                                <div class="relative h-40 md:h-56 lg:h-64 overflow-hidden rounded-2xl">
                                    @if($row->gambar_paket)
                                        <img src="{{ asset('uploads/paket/' . $row->gambar_paket) }}"
                                            alt="{{ $row->nama_paket }}"
                                            class="w-full h-full object-cover lg:object-contain rounded-2xl">
                                    @else
                                        <div class="w-full h-full bg-stone-100 rounded-2xl flex items-center justify-center text-slate-400">
                                            Tidak ada gambar
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-3 flex flex-col flex-1">

                                    <h3 class="font-semibold text-base leading-tight">
                                        {{ $row->nama_paket }}
                                    </h3>

                                    <div class="text-sm text-slate-500 mt-2 space-y-1">
                                        @foreach($bahanList as $i => $bahan)
                                            <div>
                                                <strong>•</strong>
                                                {{ $bahan }}
                                                -
                                                {{ $jumlahList[$i] ?? '-' }}
                                                {{ $satuanList[$i] ?? '' }}
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($row->max_pesan <= 0)
                                        <div class="mt-3 text-sm font-semibold text-red-600 bg-red-50 border border-red-200 rounded-xl px-3 py-2">
                                            Stok bahan baku di gudang pusat sedang kosong
                                        </div>
                                    @else
                                        <div class="mt-3 text-xs text-gray-800">
                                            Tersedia : {{ $row->max_pesan }} paket
                                        </div>
                                    @endif

                                    <div class="mt-auto pt-4 flex flex-col gap-4 md:gap-2 md:flex-row md:items-center md:justify-between">
                                        
                                        <span class="text-emerald-600 font-bold text-lg">
                                            Rp {{ number_format($row->harga, 0, ',', '.') }}
                                        </span>

                                        <div class="flex items-center justify-center gap-3 md:justify-end"
                                            data-id="{{ $row->id_paket }}"
                                            data-nama="{{ $row->nama_paket }}"
                                            data-harga="{{ $row->harga }}"
                                            data-max="{{ $row->max_pesan }}">

                                            <button type="button"
                                                    class="btn-minus w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 border border-stone-200">
                                                <i class="fa-solid fa-minus text-xs"></i>
                                            </button>

                                            <span class="qty font-medium">0</span>

                                            <button type="button"
                                                class="btn-plus w-8 h-8 rounded-full text-white
                                                    {{ $row->max_pesan <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }}"
                                                {{ $row->max_pesan <= 0 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-plus text-xs"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            @empty
                                <div class="col-span-3 text-center text-slate-500 py-10">
                                    Tidak ada data paket.
                                </div>
                        @endforelse
                    </div>
                </div>
            </main>

            <!-- Order Detail -->
            <aside id="orderDetailPanel"
                class="fixed top-0 right-0 z-50 md:z-0 h-screen w-[85%] max-w-sm bg-white p-5 shadow-xl border-l border-stone-200 flex flex-col
                transform translate-x-full transition-transform duration-300
                lg:static lg:translate-x-0 lg:col-span-3 lg:h-[calc(100vh-2rem)] lg:max-h-[calc(100vh-2rem)] lg:w-auto lg:max-w-none lg:rounded-3xl lg:shadow-sm lg:border">

                <div class="lg:hidden flex justify-end mb-3">
                    <button type="button" onclick="closeOrderDetail()" class="w-10 h-10 border border-stone-200 rounded-full bg-stone-100">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold">{{ $alamatfranchise->nama_franchise }}</h3>
                        <p class="text-sm text-slate-500"><span class="font-semibold">Alamat Franchise :</span> {{ $alamatfranchise->alamat_usaha }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-emerald-700 text-white flex items-center justify-center font-semibold">
                        A4
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto pr-1">
                    <div class="space-y-4">
                        <h4 class="font-semibold mb-3">Order Detail</h4>

                        <div id="orderItems" class="space-y-4">
                            <p class="text-sm text-slate-400">Belum ada paket dipilih.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-stone-200 space-y-3 mt-auto">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Items</span>
                        <span id="totalItems" class="font-medium">0</span>
                    </div>

                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span id="totalHarga">Rp 0</span>
                    </div>

                    <form action="{{ route('order.store') }}" method="POST" onsubmit="return submitOrder()">
                        @csrf

                        <input type="hidden" name="cart" id="cartInput">
                        <input type="hidden" name="total" id="totalInput">
                        <input type="hidden" name="id_franchise" value="{{ $alamatfranchise->id_franchise }}">

                        <button type="submit"
                            class="w-full mt-2 bg-green-500 hover:bg-green-700 text-white font-semibold py-4 rounded-2xl transition flex items-center justify-center gap-2">
                            Order
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </aside>
            <div id="orderOverlay"
                onclick="closeOrderDetail()"
                class="fixed inset-0 z-40 bg-black/40 hidden lg:hidden">
            </div>
        </div>
    </div>
@endsection
<script>
function openOrderDetail() {
    document.getElementById('orderDetailPanel').classList.remove('translate-x-full');
    document.getElementById('orderOverlay').classList.remove('hidden');
}

function closeOrderDetail() {
    document.getElementById('orderDetailPanel').classList.add('translate-x-full');
    document.getElementById('orderOverlay').classList.add('hidden');
}

let cart = {};

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function renderOrderDetail() {
    const orderItems = document.getElementById('orderItems');
    const totalItemsEl = document.getElementById('totalItems');
    const totalHargaEl = document.getElementById('totalHarga');

    let html = '';
    let totalItems = 0;
    let totalHarga = 0;

    Object.values(cart).forEach(item => {
        if (item.qty > 0) {
            totalItems += item.qty;
            totalHarga += item.qty * item.harga;

            html += `
                <div class="border-b border-stone-200 pb-3">
                    <div class="flex justify-between gap-3">
                        <div>
                            <p class="font-medium">${item.nama} <span class="text-slate-400">x${item.qty}</span></p>
                            <p class="text-xs text-slate-500 mt-1">${formatRupiah(item.harga)} / paket</p>
                        </div>
                        <span class="font-semibold">${formatRupiah(item.qty * item.harga)}</span>
                    </div>
                </div>
            `;
        }
    });

    if (html === '') {
        html = `<p class="text-sm text-slate-400">Belum ada paket dipilih.</p>`;
    }

    orderItems.innerHTML = html;
    totalItemsEl.innerText = totalItems;
    totalHargaEl.innerText = formatRupiah(totalHarga);
}

document.addEventListener('click', function(e) {
    const plusBtn = e.target.closest('.btn-plus');
    const minusBtn = e.target.closest('.btn-minus');

    if (plusBtn) {
        const parent = plusBtn.closest('[data-id]');
        const id = parent.dataset.id;
        const nama = parent.dataset.nama;
        const harga = parseInt(parent.dataset.harga);
        const max = parseInt(parent.dataset.max);
        const qtyEl = parent.querySelector('.qty');

        if (!cart[id]) {
            cart[id] = {
                nama: nama,
                harga: harga,
                qty: 0
            };
        }

        if (cart[id].qty >= max) {
            alert('Stok hanya cukup untuk ' + max + ' paket.');
            return;
        }

        cart[id].qty++;
        qtyEl.innerText = cart[id].qty;

        renderOrderDetail();
    }

    if (minusBtn) {
        const parent = minusBtn.closest('[data-id]');
        const id = parent.dataset.id;
        const qtyEl = parent.querySelector('.qty');

        if (cart[id] && cart[id].qty > 0) {
            cart[id].qty--;
            qtyEl.innerText = cart[id].qty;

            if (cart[id].qty === 0) {
                delete cart[id];
            }

            renderOrderDetail();
        }
    }
});

function submitOrder() {
    const items = Object.entries(cart).map(([id, item]) => ({
        id_paket: id,
        jumlah: item.qty,
        harga: item.harga
    })).filter(item => item.jumlah > 0);

    if (items.length === 0) {
        alert('Pilih minimal 1 paket terlebih dahulu.');
        return false;
    }

    let total = 0;
    items.forEach(item => {
        total += item.jumlah * item.harga;
    });

    document.getElementById('cartInput').value = JSON.stringify(items);
    document.getElementById('totalInput').value = total;

    return true;
}
</script>