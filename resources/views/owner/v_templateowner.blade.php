<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true }" x-cloak>
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'owner Panel')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  
  <!-- CDN Asset -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }} " />
  <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    /* Custom Styles */
    .fixed-topbar {
      position: fixed;
      top: 0;
      right: 0;
      left: 0;
      height: 72px; /* Adjust height as needed */
      z-index: 40;
      background: white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: left 0.3s ease-in-out;
    }
    
    .fixed-topbar-content {
      height: 100%;
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
    }
    
    .main-content-container {
      margin-top: 72px; /* Same as topbar height */
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
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .fixed-topbar {
        left: 0 !important;
      }
    }
    
    /* Existing styles */
    .table-container {
      overflow-x: auto;
      max-width: 100%;
    }
    
    .profile-actions {
      position: sticky;
      right: 0;
      background: white;
      padding-left: 20px;
      z-index: 50;
    }

    .card.pendaftar-baru {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    /* ... (keep your existing styles) ... */
  </style>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }" x-cloak>

<div class="flex min-h-screen">
  
  <!-- Sidebar -->
  <div class="sidebar-container" :class="sidebarOpen ? 'w-64' : 'w-20'">
    @include('owner.v_navowner')
  </div>

  <!-- Main Content Area -->
  <div class="flex-1" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
    <!-- Fixed Topbar -->
    <header class="fixed-topbar" :class="sidebarOpen ? 'left-64' : 'left-20'">
      <div class="fixed-topbar-content">

        <!-- Profile Actions -->
        <div class="flex items-center gap-6 ml-auto shrink-0">
          <div id="liveClock" class="text-sm font-semibold text-green-900 tracking-widest"></div>
          <button class="relative text-green-800 hover:text-green-900 transition">
            <i class="fas fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 h-2 w-2 bg-red-500 rounded-full border border-white"></span>
          </button>
          <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
              <img src="https://i.pravatar.cc/32" class="w-8 h-8 rounded-full border" alt="Profile">
              <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'owner' }}</span>
              <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform" :class="{'transform rotate-180': open}"></i>
            </button>
            <div x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-50">
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
              <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('logout') }}">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="main-content-container">
      @if (session('success'))
      <div class="alert alert-success alert-dismissible popup-top fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if (session('error'))
      <div class="alert alert-danger alert-dismissible popup-top fade show" role="alert">
          <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
    document.getElementById('liveClock').textContent = time;
  }
  setInterval(updateClock, 1000);
  updateClock();
</script>

</body>
</html>