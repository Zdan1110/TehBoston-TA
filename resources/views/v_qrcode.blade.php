<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Form ID Pendaftar</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-cyan-200 to-teal-600 p-6">
  <!-- Card Form -->
  <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative z-10">
    <div class="flex justify-center mb-6">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8W7oiRio5Eh4_ppE0Pour4OVey07Wh2W8Ag&s"
           alt="Logo"
           class="w-20 h-20 object-contain drop-shadow-md" />
    </div>
    <h1 class="text-3xl font-extrabold text-teal-700 mb-8 text-center">ID untuk Melihat Status Pendaftaran</h1>
    @if (session('success'))
      <div 
          x-data="{ show: true }" 
          x-init="setTimeout(() => show = false, 3000)" 
          x-show="show"
          x-transition
          class="alert alert-succes fixed top-5 left-1/2 transform -translate-x-1/2 z-50 px-4 py-2 bg-red-600 text-black rounded shadow"
      >
        {{ session('success') }}
      </div>
    @endif
      @if (session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
      @endif
    <form class="flex flex-col space-y-6">

        <h2 class="text-2xl font-extrabold text-teal-700 mb-8 text-center">ID Calon : {{ $idcalon->id_calon }}</h2>

      <!-- Kembali -->
      <a href="/home"
         class="text-center px-6 py-2 rounded-full bg-teal-600 border border-teal-600 text-white font-semibold
                transition-all duration-300 hover:bg-teal-700 hover:shadow-lg hover:scale-105">
        ← Kembali ke Beranda
      </a>
    </form>
  </div>

  <!-- Modal QR Scanner -->
  <div id="qr-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-80">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-teal-700">Scan QR Code</h2>
      </div>
      <div id="reader" class="w-full"></div>
    </div>
  </div>

  <!-- QR Code Scanner Script -->
<script src="https://unpkg.com/html5-qrcode@2.3.7/html5-qrcode.min.js"></script>


</body>
</html>
