<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cek Status Pendaftaran - Teh Boston</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/html5-qrcode"></script>
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    :root {
      --primary: #2e7d32;
      --primary-light: #4CAF50;
      --primary-dark: #1b5e20;
      --accent: #FFC107;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    }
    
    .card {
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      overflow: hidden;
    }
    
    .wave {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
      transform: rotate(180deg);
      z-index: 0;
    }
    
    .wave svg {
      position: relative;
      display: block;
      width: calc(100% + 1.3px);
      height: 100px;
    }
    
    .wave .shape-fill {
      fill: #2e7d32;
    }
    
    .tealeaf {
      position: absolute;
      width: 120px;
      height: 120px;
      opacity: 0.1;
      z-index: 0;
    }
    
    .tealeaf-1 {
      top: 10%;
      left: 5%;
      transform: rotate(15deg);
    }
    
    .tealeaf-2 {
      bottom: 20%;
      right: 5%;
      transform: rotate(-25deg);
    }
    
    .input-highlight {
      position: relative;
      display: inline-block;
      width: 100%;
    }
    
    .input-highlight::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      height: 2px;
      width: 0;
      background-color: var(--primary);
      transition: width 0.3s ease;
    }
    
    .input-highlight:focus-within::after {
      width: 100%;
    }
    
    .btn-primary {
      background: linear-gradient(to right, var(--primary), var(--primary-dark));
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(46, 125, 50, 0.4);
    }
    
    .btn-outline {
      border: 2px solid var(--primary);
      color: var(--primary);
      transition: all 0.3s ease;
    }
    
    .btn-outline:hover {
      background: var(--primary);
      color: white;
    }
    
    .qr-modal {
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
    }
    
    .qr-container {
      animation: scaleIn 0.3s ease;
    }
    
    @keyframes scaleIn {
      from {
        transform: scale(0.9);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
    
    .logo-container {
      position: relative;
      width: 100px;
      height: 100px;
      margin: 0 auto;
    }
    
    .logo-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--primary);
      border-radius: 50%;
      z-index: 1;
    }
    
    .logo-img {
      position: relative;
      z-index: 2;
      filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }
  </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-6 relative overflow-hidden">
  <!-- Background elements -->
  <div class="tealeaf tealeaf-1">
    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
      <path fill="#2e7d32" d="M10,50 Q40,10 90,50 Q40,90 10,50 Z" />
    </svg>
  </div>
  
  <div class="tealeaf tealeaf-2">
    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
      <path fill="#2e7d32" d="M10,50 Q40,10 90,50 Q40,90 10,50 Z" />
    </svg>
  </div>
  
  <!-- Wave background -->
  <div class="wave">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
    </svg>
  </div>
  
  <!-- Card Form -->
  <div class="bg-white card p-8 w-full max-w-md relative z-10">
    <div class="logo-container mb-6">
      <div class="logo-bg"></div>
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8W7oiRio5Eh4_ppE0Pour4OVey07Wh2W8Ag&s"
           alt="Logo Teh Boston"
           class="logo-img w-20 h-20 object-contain mx-auto" />
    </div>
    
    <h1 class="text-3xl font-extrabold text-center text-gray-800 mb-2">Cek Status Pendaftaran</h1>
    <p class="text-center text-gray-600 mb-8">Masukkan ID Pendaftar atau Scan QR Code</p>
    
    @if (session('error'))
      <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
          </div>
        </div>
      </div>
    @endif
    
    <form action="{{ route('cek.status') }}" method="POST" class="flex flex-col space-y-6">
      @csrf
      <!-- Input ID -->
      <div class="input-highlight">
      <div class="relative w-full">
        <label for="id_pendaftar" class="text-gray-700 mb-1 block">ID Franchisebaru - Alamat Usaha</label>
        <select
          id="id_pendaftar"
          name="id_pendaftar"
          class="h-12 w-full border-b-2 border-gray-300 bg-transparent text-gray-900 outline-none focus:border-green-600"
          required
        >
          <option value="" disabled selected>( Pilih ID Franchisebaru - Alamat Usaha )</option>
          @foreach($idfranchisebaru as $id)
            <option value="{{ $id->id_franchisebaru }}">{{ $id->id_franchisebaru }} - {{ $id->alamat_usaha }}</option>
          @endforeach
        </select>
      </div>
      </div>

      <!-- Tombol Lihat Status -->
      <button
        type="submit"
        class="px-6 py-3 rounded-full btn-primary text-white font-semibold"
      >
        Lihat Status
      </button>

      <!-- Kembali -->
      <a href="/home"
         class="text-center px-6 py-2.5 rounded-full bg-white border border-green-600 text-green-700 font-medium
                transition-all duration-300 hover:bg-green-50 hover:shadow-sm flex items-center justify-center">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Beranda
      </a>
    </form>
  </div>

  <!-- Modal QR Scanner -->
  <div id="qr-modal" class="fixed inset-0 qr-modal flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-xl shadow-2xl w-80 qr-container">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-green-700">Scan QR Code</h2>
        <button onclick="stopQrScanner()" class="text-gray-500 hover:text-gray-700">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      <div id="reader" class="w-full rounded-lg overflow-hidden"></div>
      <p class="text-center text-sm text-gray-500 mt-3">Arahkan kamera ke QR Code</p>
      <div class="flex items-center justify-between mt-4">
      <hr class="w-full border-gray-300">
      <span class="mx-2 text-gray-400 text-sm">atau</span>
      <hr class="w-full border-gray-300">
      </div>

      <label for="qr-file-input" class="mt-4 flex items-center justify-center text-sm font-medium text-green-700 cursor-pointer hover:underline">
        📁 Unggah Gambar QR Code
      </label>
      <input type="file" accept="image/*" id="qr-file-input" class="hidden">
    </div>
  </div>

  <!-- QR Code Scanner Script -->
  <script>
  let html5QrCode;

  function startQrScanner() {
    const qrModal = document.getElementById('qr-modal');
    qrModal.classList.remove('hidden');

    html5QrCode = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(devices => {
      if (devices && devices.length) {
        html5QrCode.start(
          devices[0].id,
          { fps: 10, qrbox: 250 },
          qrCodeMessage => {
            document.getElementById('id_pendaftar').value = qrCodeMessage;
            stopQrScanner();
          }
        );
      }
    });

    // Tutup modal jika klik luar
    qrModal.addEventListener('click', (event) => {
      if (event.target === qrModal) {
        stopQrScanner();
      }
    });
  }

  function stopQrScanner() {
    if (html5QrCode) {
      return html5QrCode.stop().then(() => {
        html5QrCode.clear();
        html5QrCode = null;
        document.getElementById('qr-modal').classList.add('hidden');
      }).catch(err => {
        console.error("Stop failed", err);
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const inputFile = document.getElementById("qr-file-input");
    inputFile.addEventListener("change", async function (e) {
      const file = e.target.files[0];
      if (!file) return;

      try {
        if (html5QrCode && html5QrCode._isScanning) {
          await html5QrCode.stop();
          await html5QrCode.clear();
        }

        html5QrCode = new Html5Qrcode("reader");

        const result = await html5QrCode.scanFile(file, true);
        document.getElementById('id_pendaftar').value = result;

        html5QrCode.clear();
        html5QrCode = null;
        document.getElementById('qr-modal').classList.add('hidden');
      } catch (err) {
        alert("Gagal membaca QR: " + err);
        if (html5QrCode) {
          html5QrCode.clear();
          html5QrCode = null;
        }
      }

      // Reset input agar bisa upload ulang file yang sama
      e.target.value = '';
    });
  });
</script>
  <script>
    const input = document.querySelector('#id_pendaftar');
    const label = document.querySelector('.label-floating');

    function checkValue() {
      if (input.value.trim() !== '') {
        label.classList.add('peer-focus:text-green-600', 'text-sm', 'top-0');
      } else {
        label.classList.remove('peer-focus:text-green-600', 'text-sm', 'top-0');
      }
    }

    // Cek saat load
    window.addEventListener('DOMContentLoaded', checkValue);

    // Cek saat user ketik
    input.addEventListener('input', checkValue);
  </script>


  
</body>
</html>