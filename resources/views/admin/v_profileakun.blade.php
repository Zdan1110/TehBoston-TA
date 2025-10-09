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
              green: '#2e7d32',
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
    .password-dots {
      letter-spacing: 0.2em;
    }
  </style>
</head>
<body class="bg-boston-light">
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl">
      <!-- Profile Card -->
      <div class="flex flex-col lg:flex-row profile-card overflow-hidden bg-white">
        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 bg-boston-dark p-8 flex flex-col items-center text-center relative">
          <div class="absolute top-4 right-4 text-boston-green opacity-20">
            <i class="fas fa-leaf text-6xl"></i>
          </div>

          

          <!-- Username dan Email -->
          <div class="mb-8">
            <h1 class="text-2xl font-bold text-boston-gold mb-1">{{ $admin->username ?? 'Guest' }}</h1>
            <p class="text-boston-green">{{ $admin->email ?? '-' }}</p>
          </div>

          <div class="divider w-full my-4"></div>

          <!-- Logo -->
          <div class="mt-auto">
            <div class="text-3xl font-bold text-boston-green mb-1">TEH BOSTON</div>
            <p class="text-xs text-boston-gold">Minuman Berkualitas, Rasa Istimewa</p>
          </div>
        </div>

        <!-- Detail -->
        <div class="w-full lg:w-2/3 p-8 bg-white">
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-boston-dark">
              <span class="text-boston-green">PROFIL</span> ADMIN
            </h2>
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-boston-green text-white rounded-full text-sm hover:bg-boston-dark transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>

          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-user mr-2"></i>Nama Lengkap
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $admin->nama ?? '-' }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $admin->email ?? '-' }}
                </div>
              </div>
            </div>

            <!-- Kolom 2 -->
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-phone-alt mr-2"></i>No. Handphone
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green">
                  {{ $admin->no_hp ?? '-' }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-boston-green mb-1">
                  <i class="fas fa-key mr-2"></i>Password
                </label>
                <div class="p-3 bg-boston-light rounded-lg text-boston-dark border-l-4 border-boston-green password-dots">
                  ••••••••••••
                </div>
                <p class="text-xs text-gray-500 mt-1">Password disembunyikan untuk keamanan</p>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="mt-8 pt-6 flex flex-wrap justify-between items-center">
            <div class="flex items-center mb-3 md:mb-0">
              <div class="w-10 h-10 rounded-full bg-boston-gold flex items-center justify-center mr-3">
                <i class="fas fa-user-shield text-boston-dark text-lg"></i>
              </div>
              <div>
                <div class="text-xs text-boston-green">Role</div>
                <div class="font-medium text-boston-dark">Administrator</div>
              </div>
            </div>

            <div class="flex items-center">
              <div class="w-10 h-10 rounded-full bg-boston-green flex items-center justify-center mr-3">
                <i class="fas fa-calendar-alt text-white text-lg"></i>
              </div>
              <div class="text-right">
                <div class="text-xs text-boston-green">Terdaftar Sejak</div>
                <div class="font-medium text-boston-dark">
                  @if($admin && $admin->created_at)
                    {{ \Carbon\Carbon::parse($admin->created_at)->format('d F Y') }}
                  @else
                    -
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- End Profile Card -->
    </div>
  </div>
</body>
</html>
