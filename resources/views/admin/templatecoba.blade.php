<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: window.innerWidth > 768, isMobile: window.innerWidth <= 768 }" x-cloak @resize.window="isMobile = (window.innerWidth <= 768); sidebarOpen = (window.innerWidth > 768) ? sidebarOpen : false">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Admin Panel')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link
    rel="icon"
    href="/public/favicon.ico"
    type="image/x-icon"
  />
  
  <!-- CDN Asset -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }} " />
  <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    :root {
      --primary-green: #2E8B57;
      --dark-green: #1F6F4A;
      --light-green: #E8F5E9;
      --card-bg: #FFFFFF;
      --text-dark: #333333;
    }
    
      /* Batasi lebar kolom titik koordinat */
  td, th {
    max-width: 200px; /* atur sesuai kebutuhan */
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Kalau mau khusus untuk kolom titik koordinat */
  td.titik-koordinat {
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
    
    /* Custom Styles */
    .fixed-topbar {
      position: fixed;
      top: 0;
      right: 0;
      left: 0;
      height: 72px;
      z-index: 40;
      background: white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: left 0.3s ease-in-out;
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.95);
    }
    
    .fixed-topbar-content {
      height: 100%;
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
    }
    
    .main-content-container {
      margin-top: 72px;
      padding: 1.5rem;
      min-height: calc(100vh - 72px);
      overflow-y: auto;
    }
    
    /* Sidebar adjustment */
    .sidebar-container {
      position: fixed;
      top: 0;
      bottom: 0;
      z-index: 50;
      transition: transform 0.3s ease-in-out;
    }

    .table-responsive {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    table {
      min-width: 900px; /* Cegah kolom "meledak" di layar kecil */
      border-collapse: collapse;
    }

    th, td {
      white-space: nowrap;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .sidebar-container {
        transform: translateX(-100%);
      }
      .sidebar-container.open {
        transform: translateX(0);
      }
      .fixed-topbar {
        left: 0 !important;
      }
      .sidebar-overlay {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 49;
      }

      .table-responsive {
        border: 1px solid #dee2e6;
      }

      table {
        min-width: 900px;
        border-collapse: collapse;
      }

      table thead {
        display: none;
      }

      table tbody, table tr, table td {
        display: block;
        width: 100%;
      }

      table tr {
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        padding: 1rem;
        border-radius: 8px;
      }

      th, td {
        white-space: nowrap;
      }

      table td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #444;
      }
    }
    
    /* Profile dropdown */
    .profile-dropdown {
      transition: all 0.3s ease;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    /* Notifications dropdown */
    .notifications-dropdown {
      max-height: 400px;
      overflow-y: auto;
    }
    
    /* Live clock */
    .live-clock {
      background-color: var(--light-green);
      color: var(--dark-green);
      padding: 4px 10px;
      border-radius: 20px;
      font-weight: 600;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* Animation for alerts */
    @keyframes slideIn {
      from { transform: translateY(-20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    .alert {
      animation: slideIn 0.3s ease-out;
    }
    
    /* Modern button */
    .btn-modern {
      background: var(--primary-green);
      color: white;
      border-radius: 8px;
      padding: 8px 20px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
    }
    
    .btn-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
      background: var(--dark-green);
    }
    
    /* Table styles */
    .table-container {
      overflow-x: auto;
      max-width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
  </style>
</head>
<body class="bg-gray-50" x-data x-cloak>

<!-- Overlay for mobile sidebar -->
<div class="sidebar-overlay" x-show="isMobile && sidebarOpen" @click="sidebarOpen = false" style="display: none;"></div>

<div class="flex min-h-screen">
  
  <!-- Sidebar -->
  <div class="sidebar-container" 
     :class="[sidebarOpen ? 'w-64' : 'w-20', isMobile && sidebarOpen ? 'open' : '']" 
     x-show="sidebarOpen || !isMobile"
     x-transition>
    @include('admin.navcoba')
  </div>

  <!-- Main Content Area -->
  <div class="flex-1" :class="sidebarOpen ? 'md:ml-64' : 'md:ml-20'">
    <!-- Fixed Topbar -->
    <header class="fixed-topbar" :class="sidebarOpen ? 'md:left-64' : 'md:left-20'">
      <div class="fixed-topbar-content">
        <!-- Left Section: Toggle Button and Title -->
        <div class="flex items-center">
          <!-- Toggle Button for Mobile -->
          <button @click="sidebarOpen = !sidebarOpen" class="mr-3 text-green-900 focus:outline-none md:hidden">
            <i class="fas fa-bars text-xl"></i>
          </button>
          <!-- Toggle Button for Desktop (Optional) -->
          <button @click="sidebarOpen = !sidebarOpen" class="mr-3 text-green-900 focus:outline-none hidden md:block">
            <i class="fas fa-bars text-xl"></i>
          </button>
          <h1 class="text-lg font-semibold text-green-900">@yield('title', 'Admin Panel')</h1>
        </div>

        <!-- Profile Actions -->
        <div class="flex items-center gap-6 ml-auto shrink-0">
          <div id="liveClock" class="live-clock text-sm font-semibold tracking-widest hidden sm:block"></div>
          <div class="relative">
            <button id="bell-btn" class="relative text-green-800 hover:text-green-900 transition">
              <i class="fas fa-bell text-xl"></i>
              <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border border-white animate-pulse"></span>
            </button>

            <div id="notifikasi-list" class="absolute right-0 mt-2 w-72 bg-white border rounded-lg shadow-lg z-50 hidden notifications-dropdown">
              <div class="p-3 border-b">
                <h4 class="font-semibold text-gray-800">Notifikasi</h4>
              </div>
              <!-- Notifikasi akan muncul di sini -->
            </div>
          </div>
          <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
              <img src="https://i.pravatar.cc/40?u={{ Auth::id() }}" class="w-8 h-8 rounded-full border-2 border-green-300 object-cover" alt="Profile">
              <span class="text-sm font-medium text-gray-700 hidden md:inline">{{ Auth::user()->name ?? 'Admin' }}</span>
              <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform hidden md:inline" :class="{'transform rotate-180': open}"></i>
            </button>
            <div x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 profile-dropdown">
              <a href="/admin/profiles" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                <i class="fas fa-user-circle mr-2 text-green-600"></i> Profil
              </a>
              <a class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors flex items-center" href="{{ route('logout') }}">
                <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> Logout
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="main-content-container">
      @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show rounded-lg mb-4" role="alert">
        <div class="flex items-start">
          <i class="fas fa-check-circle me-3 mt-1 text-green-600"></i>
          <div>
            {{ session('success') }}
          </div>
          <button type="button" class="btn-close ml-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
      @endif

      @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show rounded-lg mb-4" role="alert">
        <div class="flex items-start">
          <i class="fas fa-times-circle me-3 mt-1 text-red-500"></i>
          <div>
            {{ session('error') }}
          </div>
          <button type="button" class="btn-close ml-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
      @endif

      @if (session('delete'))
      <div class="alert alert-warning alert-dismissible fade show rounded-lg mb-4" role="alert">
        <div class="flex items-start">
          <i class="fas fa-exclamation-triangle me-3 mt-1 text-yellow-500"></i>
          <div>
            {{ session('delete') }}
          </div>
          <button type="button" class="btn-close ml-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
      @endif
      
      <div class="w-full overflow-x-auto">
        @yield('content')
      </div>
    </div>
  </div>
</div>

<script>
  function updateClock() {
    const now = new Date();
    const time = now.toLocaleTimeString('id-ID', { hour12: false });
    const date = now.toLocaleDateString('id-ID', { 
      weekday: 'long', 
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    });
    
    document.getElementById('liveClock').innerHTML = `
      <span class="hidden md:inline">${date}</span>
      <span>${time}</span>
    `;
  }
  setInterval(updateClock, 1000);
  updateClock();
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JQuery (wajib jika Bootstrap 4) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  $(document).ready(function () {
    $('#search').on('keyup', function () {
      let query = $(this).val();
      $.ajax({
        url: "{{ route('admincalon') }}", // pastikan ini sesuai route index1()
        type: "GET",
        data: { search: query },
        success: function (data) {
          $('#table-container').html(data);
        }
      });
    });
  });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const bellButton = document.getElementById("bell-btn");
  const notifikasiContainer = document.getElementById("notifikasi-list");

  bellButton.addEventListener("click", () => {
    fetch("{{ route('admin.notifikasi') }}")
      .then(res => res.json())
      .then(data => {
        notifikasiContainer.innerHTML = "";
        if (data.length === 0) {
          notifikasiContainer.innerHTML = `<div class="p-4 text-center">
            <i class="fas fa-bell-slash text-2xl text-gray-400 mb-2"></i>
            <p class='text-sm text-gray-500'>Tidak ada notifikasi</p>
          </div>`;
        } else {
          data.forEach(item => {
            const el = document.createElement("div");
            el.className = "p-3 border-b border-gray-100 text-sm text-gray-700 hover:bg-gray-50 transition-colors";
            el.innerHTML = `
              <div class="flex">
                <div class="mr-3 mt-1">
                  <i class="fas fa-bell text-green-500"></i>
                </div>
                <div>
                  <strong class="block">${item.judul}</strong>
                  <span class="text-xs text-gray-500">${item.pesan}</span>
                  <div class="text-xs text-gray-400 mt-1">${item.created_at}</div>
                </div>
              </div>`;
            notifikasiContainer.appendChild(el);
          });
          
          // Add view all link
          const viewAll = document.createElement("a");
          viewAll.className = "block p-3 text-center text-sm text-green-600 font-medium hover:bg-gray-50 transition-colors";
          viewAll.href = "#";
          viewAll.innerHTML = "Lihat Semua Notifikasi";
          notifikasiContainer.appendChild(viewAll);
        }
      });
  });
});
</script>

<script>
  // Tampilkan / sembunyikan dropdown
  document.getElementById('bell-btn').addEventListener('click', function () {
    const box = document.getElementById('notifikasi-list');
    box.classList.toggle('hidden');
  });
  
  // Close notifications when clicking outside
  document.addEventListener('click', function(event) {
    const bellBtn = document.getElementById('bell-btn');
    const notifications = document.getElementById('notifikasi-list');
    
    if (!bellBtn.contains(event.target) && !notifications.contains(event.target)) {
      notifications.classList.add('hidden');
    }
  });
</script>

</body>
</html>