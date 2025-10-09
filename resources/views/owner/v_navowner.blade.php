  <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gradient-to-b from-green-900 to-green-800 text-white h-screen fixed transition-all duration-300 ease-in-out overflow-y-auto z-40 shadow-xl">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-4 py-5 border-b border-green-700 bg-green-900">
      <div class="flex items-center space-x-3">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8W7oiRio5Eh4_ppE0Pour4OVey07Wh2W8Ag&s" class="h-10 rounded-full border-2 border-green-300" alt="Logo">
        <span class="text-xl font-bold tracking-wide whitespace-nowrap" x-show="sidebarOpen" x-transition>owner Panel</span>
      </div>
      <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:text-green-200 transition-colors p-1 rounded-full hover:bg-green-700">
        <i :class="sidebarOpen ? 'fas fa-angle-left' : 'fas fa-angle-right'"></i>
      </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-4 px-2 space-y-1">
      <!-- Dashboard -->
      <a href="/owner" class="flex items-center px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200 {{ Request::is('owner') ? 'bg-green-700 font-medium shadow-md' : '' }}">
        <i class="fas fa-gauge text-lg w-6 text-center"></i>
        <span class="ml-3" x-show="sidebarOpen" x-transition>Dashboard</span>
      </a>

      <!-- Data Master -->
      <div x-data="{ open: false }" class="mt-1">
        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200">
          <span class="flex items-center">
            <i class="fas fa-database text-lg w-6 text-center"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Data Master</span>
          </span>
          <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
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
          <a href="/owner/tabelcalon" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 {{ Request::is('owner/tabelcalon') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-user-group text-sm w-5 text-center"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Calon</span>
          </a>
          <a href="/owner/tabelakun" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 {{ Request::is('owner/tabelakun') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-id-card text-sm w-5 text-center"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Akun</span>
          </a>
          <a href="/owner/tabelproduk" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 {{ Request::is('owner/tabelproduk') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-cubes text-sm w-5 text-center"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Produk</span>
          </a>
          <a href="/owner/tabelfranchise" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 {{ Request::is('owner/tabelfranchise') ? 'bg-green-700 font-medium shadow-md' : '' }}">
            <i class="fas fa-store-alt text-sm w-5 text-center"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Data Franchise</span>
          </a>
          <a href="/owner/tabelfranchisebaru" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200">
            <i class="fas fa-store text-sm w-5 text-center"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Franchise Baru</span>
          </a>
          <!-- Added Ulasan Menu -->
      
        </div>
      </div>

      <!-- Pelaporan -->
      <!-- <div x-data="{ open: false }" class="mt-1">
        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-3 rounded-lg mx-2 hover:bg-green-700 transition-all duration-200">
          <span class="flex items-center">
            <i class="fas fa-chart-bar text-lg w-6 text-center"></i>
            <span class="ml-3" x-show="sidebarOpen" x-transition>Pelaporan</span>
          </span>
          <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
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
          <a href="/owner/transaksi" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200">
            <i class="fas fa-money-bill-wave text-sm w-5 text-center"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Transaksi</span>
          </a>
         
          <a href="/owner/datasurvey" class="flex items-center px-3 py-2 rounded-lg hover:bg-green-700 transition-all duration-200">
            <i class="fas fa-poll text-sm w-5 text-center"></i>
            <span class="ml-2" x-show="sidebarOpen" x-transition>Hasil Survey</span>
          </a>
        </div>
      </div> -->

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
      width: 5px;
    }
    aside::-webkit-scrollbar-track {
      background: rgba(255,255,255,0.1);
    }
    aside::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.2);
      border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb:hover {
      background: rgba(255,255,255,0.3);
    }
    
    /* Smooth transitions */
    .transition-all {
      transition-property: all;
    }
    
    /* Active menu item glow */
    .shadow-md {
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
  </style>