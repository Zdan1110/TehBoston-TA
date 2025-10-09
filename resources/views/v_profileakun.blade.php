<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Teh Boston</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            boston: {
              dark: '#1a1a1a',
              green: '#2e7d32',  // Warna hijau khas Teh Boston
              gold: '#D4AF37',
              light: '#f5f5f5'
            }
          }
        }
      }
    }
  </script>
  <style>
    .profile-card {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      border-radius: 12px;
    }
    .divider {
      height: 2px;
      background: linear-gradient(to right, transparent, #D4AF37, transparent);
    }
  </style>
</head>
<body class="bg-boston-light">
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl">
      <!-- Horizontal Profile Card -->
      <div class="flex flex-col lg:flex-row profile-card overflow-hidden bg-white">
        <!-- Profile Sidebar -->
        <div class="w-full lg:w-1/3 bg-boston-dark p-8 flex flex-col items-center text-center relative">
          <!-- Tea Leaf Decoration -->
          <div class="absolute top-4 right-4 text-boston-green opacity-20">
            <i class="fas fa-leaf text-6xl"></i>
          </div>
          
          <!-- Profile Picture -->
          <div class="w-36 h-36 border-4 border-boston-gold rounded-full bg-white shadow-lg overflow-hidden mb-6 mt-4">
            @if(isset($foto->pas_photo))
              <img src="{{ asset('uploads/photo/'.$foto->pas_photo) }}" alt="Profile" class="w-full h-full object-cover">
            @else
              <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500">
                <i class="fas fa-user text-5xl"></i>
              </div>
            @endif
          </div>
          
          <!-- User Info -->
          <div class="mb-8">
            <h1 class="text-2xl font-bold text-boston-gold mb-1">{{ Session::get('user')['username'] ?? 'Guest' }}</h1>
            <p class="text-boston-green">{{ Session::get('user')['email'] ?? '-' }}</p>
          </div>
          
          <!-- Divider -->
          <div class="divider w-full my-4"></div>
          
          <!-- Brand Logo -->
          <div class="mt-auto">
            <div class="text-3xl font-bold text-boston-green mb-1">TEH BOSTON</div>
            <p class="text-xs text-boston-gold">Minuman Berkualitas, Rasa Istimewa</p>
          </div>
        </div>
        
        <!-- Profile Details -->
        <div class="w-full lg:w-2/3 p-8 bg-white">
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-boston-dark">
              <span class="text-boston-green">PROFIL</span> USER
            </h2>
            <a href="/home" class="px-4 py-2 bg-boston-green text-white rounded-full text-sm hover:bg-boston-dark transition flex items-center">
              <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($mitra as $data)
            <!-- Column 1 -->
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-user mr-2"></i>Nama Lengkap
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $data->nama_lengkap ?? '-' }}
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-map-marker-alt mr-2"></i>Provinsi
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $data->provinsi ?? '-' }}
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-city mr-2"></i>Kota
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $data->kota ?? '-' }}
                </div>
              </div>
            </div>
            
            <!-- Column 2 -->
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-map-marked-alt mr-2"></i>Kelurahan
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $data->kelurahan ?? '-' }}
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-home mr-2"></i>Alamat Lengkap
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $data->alamat_lengkap ?? '-' }}
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-phone-alt mr-2"></i>Nomor Handphone
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $data->no_hp ?? '-' }}
                </div>
              </div>
            </div>
            @endforeach
          </div>
          
          
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>