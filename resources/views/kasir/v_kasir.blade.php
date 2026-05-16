@extends('kasir.template_kasir')
@section('title', 'Kasir')
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
  background-color: #dc3545; 
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
  background-color: #ff4d4d; 
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
  background-color: #28a745; 
}

.alert.hidden {
  opacity: 0;
  pointer-events: none;
}

.menu-item.best-seller {
  border: 2px solid gold;
  position: relative;
}

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

.btn-jumlah {
    width: 32px;
    height: 32px;
    border: 1px solid #000;
    border-radius: 50%;
    background: white;
    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 18px;
    font-weight: bold;
    line-height: 1;

    margin-top: 5px;
    padding-bottom: 4px;
}

.btn-jumlah:hover {
    background: #f0f0f0;
}

@media (max-width: 768px) {

    .menu-item {
        width: calc(33.33% - 11px); 
        
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .menu-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 1 / 1;
        object-fit: cover;
    }

    .menu-item p {
        margin-top: 8px;
        font-size: 12px; 
        line-height: 1.2;
    }
    
    .product-name {
        display: block;
        min-height: 29px;
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
          <th>Menu</th>
          <th>Jumlah/Menu</th>
          <th>Total Harga/Menu</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        @php
            $grouped = $riwayat->groupBy('id_penjualan');
            $no = 1;
        @endphp

        @foreach($grouped as $id_penjualan => $items)

        @php
            $first = $items->first();
        @endphp

        <tr>
            <td>{{ $no++ }}</td>

            {{-- MENU --}}
            <td class="text-start">
                @foreach($items as $item)
                    <div>
                        • {{ $item->nama_produk }}
                    </div>
                @endforeach
            </td>

            <td class="text-start">
                @foreach($items as $item)
                    <div>
                        • {{ $item->jumlah }}
                    </div>
                @endforeach
            </td>

            <td class="text-start">
                @foreach($items as $item)
                    <div>
                        • Rp{{ number_format((int) $item->harga, 0, ',', '.') }}
                    </div>
                @endforeach
            </td>

            <td>
                {{ \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y H:i') }}
            </td>
        </tr>

        @endforeach
      </tbody>
    </table>
  </div>
  <div id="custom-alert" class="alert hidden">
  <span id="alert-message"></span>
  </div>

@endsection