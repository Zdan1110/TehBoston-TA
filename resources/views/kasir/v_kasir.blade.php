@section ('Title')
Kasir
@endsection
@extends('kasir.template_kasir')
@section('content')
@section('content')

<style>
  .main {
    padding: 20px 40px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .menu {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
  }

  .menu-item {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    width: 120px;
    text-align: center;
    cursor: pointer;
    transition: transform 0.2s ease;
    background-color: #fff;
  }

  .menu-item:hover {
    transform: scale(1.05);
  }

  .menu-item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
  }
  
  .btn-hapus {
  background-color: #dc3545; /* Merah Bootstrap */
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.2s ease;
  }

  .btn-hapus:hover {
    background-color: #c82333;
  }

  .order-section {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 20px;
}
.form-pelanggan,
.pesanan {
  flex: 1 1 300px;
}


  .form-pelanggan, .pesanan {
    flex: 1;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
  }

  .pembayaran-container {
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 20px;
    background-color: #f9f9f9;
  }

  .checkout-btn {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 20px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
  }

  th, td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
  }

  th {
    background-color: #f0f0f0;
  }

  .alert {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 9999;
  background-color: #ff4d4d; /* Default merah */
  color: white;
  padding: 12px 20px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: opacity 0.4s ease;
}

.alert.success {
  background-color: #28a745; /* Hijau untuk sukses */
}

.alert.hidden {
  opacity: 0;
  pointer-events: none;
}

/* Tambahkan highlight untuk best seller */
.menu-item.best-seller {
  border: 2px solid gold;
  position: relative;
}

/* Gaya pita Best Seller */
.ribbon {
  position: absolute;
  top: -8px;
  left: -8px;
  background: gold;
  color: black;
  font-weight: bold;
  font-size: 12px;
  padding: 4px 6px;
  border-radius: 4px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.2);
  z-index: 10;
}

@media (max-width: 768px) {
    /* ... (CSS lainnya jangan diubah) ... */

    .menu-item {
        /* Mengubah lebar untuk membuat 3 kolom */
        width: calc(33.33% - 11px); /* Kalkulasi untuk 3 kolom dengan gap 16px */
        
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    /* Pastikan CSS untuk gambar dan teks masih ada */
    .menu-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 1 / 1;
        object-fit: cover;
    }

    .menu-item p {
        margin-top: 8px;
        font-size: 12px; /* Anda mungkin perlu sedikit mengecilkan font */
        line-height: 1.2;
    }
    
    .product-name {
        display: block;
        min-height: 29px; /* Disesuaikan untuk font 12px */
    }

    .product-price {
        font-weight: bold;
    }
}

</style>

      <div class="menu">
      @foreach ($kasir as $data)
      @php
        $isBestSeller = ($data->nama_produk === $bestSellers);
      @endphp
      <div class="menu-item {{ $isBestSeller ? 'best-seller' : '' }}" onclick="addToOrder('{{ $data->nama_produk }}', {{ $data->harga }})">
        @if($isBestSeller)
          <div class="ribbon">Best Seller</div>
        @endif
        <img src="{{ asset('uploads/produk/'.$data->gambar_produk) }}" alt="{{ $data->nama_produk }}">
        <p>{{ $data->nama_produk }}<br>Rp{{ number_format($data->harga, 0, ',', '.') }}</p>
      </div>
    @endforeach

    </div>

    <div class="order-section">
      <div class="form-pelanggan">
        <h3>Nama Pelanggan</h3>
        <input type="text" id="kode-pelanggan" placeholder="Masukkan Nama Pelanggan" style="padding: 8px; width: 100%; border-radius: 6px; border: 1px solid #ccc;">
      </div>
      
      <div class="pesanan">
        <h3>Pesanan Anda</h3>
        <ul id="order-list"></ul>
        <div id="total">Total: Rp0</div>
      </div>
    </div>

    <div class="pembayaran-container">
      <label for="bayar">Bayar:</label>
      <input type="number" id="bayar" oninput="updateKembalian()" placeholder="Masukkan jumlah bayar">

      <label for="kembalian-input">Kembalian:</label>
      <input type="text" id="kembalian-input" placeholder="Rp0" readonly>
    </div>

    <button type="button" class="checkout-btn" onclick="checkoutOrder()" href="/print">Checkout</button>

    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Pelanggan</th>
          <th>Menu</th>
          <th>Jumlah/Menu</th>
          <th>Total Harga/Menu</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
      @php $no = 1; @endphp
      @foreach($riwayat as $item)
        <tr>
          <td>{{ $no++ }}</td>
          <td>{{ $item->pelanggan }}</td>
          <td>{{ $item->nama_produk }}</td>
          <td>{{ $item->jumlah }}</td>
          <td>{{ $item->harga }}</td>
          <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y H:i') }}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <div id="custom-alert" class="alert hidden">
  <span id="alert-message"></span>
  </div>

@endsection