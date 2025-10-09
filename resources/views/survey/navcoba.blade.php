{{-- resources/views/survey/navcoba.blade.php --}}
<div class="sidebar-brand text-center py-4">
    {{-- Hapus "rounded-circle" dan "object-fit: cover" jika ingin logo tampil seperti di gambar terbaru Anda --}}
    <img src="{{ asset('gambar/LOGO.png') }}" alt="Logo Aplikasi" class="img-fluid mb-3" style="max-width: 120px; height: auto;">
    <h5 class="mb-0 text-white">{{ Auth::user()->name ?? 'Surveyor' }}</h5>
    <p class="text-white-50 small">Peran Survey</p>
</div>
<ul class="nav flex-column">
  
    {{-- Item Data Calon Mitra --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->is('survey/tabelcalon') ? 'active' : '' }}" href="{{ route('survey.calon') }}">
            <i class="fas fa-users"></i> Data Calon Mitra
        </a>
    </li>
    {{-- Item Laporan Survey --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->is('survey/datasurvey') ? 'active' : '' }}"  href="{{ route('datasurvey') }}">
            <i class="fas fa-file-alt" ></i> Laporan Survey
        </a>
    </li>
    {{-- Item Keluar dengan form logout --}}
    <li class="nav-item mt-4">
        <a class="nav-link" href="/logout">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </a>
        <form id="logout-form" action="/logout" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>