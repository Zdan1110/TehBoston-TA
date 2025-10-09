<aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gradient-to-b from-green-900 to-green-800 text-white h-screen fixed transition-all duration-300 ease-in-out overflow-y-auto z-40 shadow-xl">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-4 py-5 border-b border-green-700 bg-green-900">
      <div class="flex items-center space-x-3">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8W7oiRio5Eh4_ppE0Pour4OVey07Wh2W8Ag&s" class="h-10 rounded-full border-2 border-green-300 shadow-md" alt="Logo">
        <span class="text-xl font-bold tracking-wide whitespace-nowrap" x-show="sidebarOpen" x-transition>Admin Panel</span>
      </div>
      <!-- Close button for mobile -->
      <button @click="sidebarOpen = false" class="md:hidden text-green-300 hover:text-white">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-4 px-2 space-y-1">
      <!-- Dashboard -->
      <a href="/admin" class="flex items-center px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200 group {{ Request::is('admin') ? 'bg-green-700 font-medium shadow-md' : '' }}">
        <i class="fas fa-gauge text-lg w-6 text-center group-hover:text-white"></i>
        <span class="ml-3" x-show="sidebarOpen" x-transition>Dashboard</span>
      </a>

      <!-- Data Master -->
      <div x-data="{ open: false }" class="mt-1">
        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200 group">
          <span class="flex items-center">
            <i class="fas fa-database text-lg w-6 text-center group-hover:text-white"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Data Master</span>
          </span>
          <i class="fas fa-chevron-down text-xs transition-transform duration-200 group-hover:text-white" 
            :class="open ? 'rotate-180' : ''" 
            x-show="sidebarOpen" 
            x-transition></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="ml-8 mt-1 space-y-1" x-cloak>
          <a href="/admin/tabelcalon" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group {{ Request::is('admin/tabelcalon') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-user-group text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Calon</span>
          </a>
          <a href="/admin/tabelakun" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group {{ Request::is('admin/tabelakun') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-id-card text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Akun</span>
          </a>
          <a href="/admin/tabelproduk" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group {{ Request::is('admin/tabelproduk') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-cubes text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Produk</span>
          </a>
          <a href="/admin/tabelfranchise" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group {{ Request::is('admin/tabelfranchise') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-store-alt text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Franchise</span>
          </a>
          <a href="/admin/tabelfranchisebaru" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group {{ Request::is('admin/tabelfranchisebaru') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-store text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Franchise Baru</span>
          </a>
          <!-- Added Ulasan Menu -->
          <a href="/admin/tabelulasan" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group {{ Request::is('admin/tabelulasan') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-star-half-alt text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Ulasan</span>
          </a>
        </div>
      </div>

      <!-- Pelaporan -->
      <div x-data="{ open: false }" class="mt-1">
        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200 group">
          <span class="flex items-center">
            <i class="fas fa-chart-bar text-lg w-6 text-center group-hover:text-white"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Pelaporan</span>
          </span>
          <i class="fas fa-chevron-down text-xs transition-transform duration-200 group-hover:text-white" 
            :class="open ? 'rotate-180' : ''" 
            x-show="sidebarOpen" 
            x-transition></i>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="ml-8 mt-1 space-y-1" x-cloak>
          <a href="/admin/transaksi" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group">
            <i class="fas fa-money-bill-wave text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Transaksi</span>
          </a>
          <a href="/admin/datasurvey" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 group">
            <i class="fas fa-poll text-sm w-5 text-center group-hover:text-white"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Hasil Survey</span>
          </a>
        </div>
      </div>

      <!-- Additional Menu Items -->
      <div class="mt-4 border-t border-green-700 pt-2">
        <a href="/home" class="flex items-center px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200 group">
          <i class="fas fa-home text-lg w-6 text-center group-hover:text-white"></i>
          <span class="ml-3" x-show="sidebarOpen" x-transition>Ke Halaman Home</span>
        </a>
        <a href="/logout" class="flex items-center px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200 group">
          <i class="fas fa-sign-out-alt text-lg w-6 text-center group-hover:text-white"></i>
          <span class="ml-3" x-show="sidebarOpen" x-transition>Keluar</span>
        </a>
      </div>
    </nav>

    <!-- Sidebar Footer (Collapsed Only) -->
    <div class="absolute bottom-0 left-0 right-0 p-2 bg-green-900 text-center" x-show="!sidebarOpen" x-transition>
      <i class="fas fa-ellipsis-h text-green-300"></i>
    </div>
  </aside>

  <style>
    /* Custom scrollbar */
    aside::-webkit-scrollbar {
      width: 6px;
    }
    aside::-webkit-scrollbar-track {
      background: rgba(255,255,255,0.1);
      border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.3);
      border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb:hover {
      background: rgba(255,255,255,0.4);
    }
    
    /* Smooth transitions */
    .transition-all {
      transition-property: all;
    }
    
    /* Active menu item glow */
    .shadow-md {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Menu item hover effect */
    .group:hover .group-hover\:text-white {
      color: white;
    }
  </style>