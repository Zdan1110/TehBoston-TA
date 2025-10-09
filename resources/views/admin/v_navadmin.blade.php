<div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="/dashboard" class="logo">
              <img
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8W7oiRio5Eh4_ppE0Pour4OVey07Wh2W8Ag&s"
                alt="navbar brand"
                class="navbar-brand"
                height="50px"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-ldaeft"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <a
                  data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li class="{{ Request::is('admin') ? 'active' : '' }}">
                      <a href="/admin">
                        <span class="sub-item">Dashboard Admin</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#tables">
                  <i class="fas fa-table"></i>
                  <p>Tables</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse show" id="tables">
                  <ul class="nav nav-collapse">
                    <li class="{{ Request::is('admin/tabelcalon') ? 'active' : '' }}">
                      <a href="/admin/tabelcalon">
                        <span class="sub-item">Data Calon</span>
                      </a>
                    </li>
                    <li class="{{ Request::is('admin/tabelakun') ? 'active' : '' }}">
                      <a href="/admin/tabelakun">
                        <span class="sub-item">Data Akun</span>
                      </a>
                    </li>
                    <li class="{{ Request::is('admin/tabelproduk') ? 'active' : '' }}">
                      <a href="/admin/tabelproduk">
                        <span class="sub-item">Data Produk</span>
                      </a>
                    </li>
                      <li class="{{ Request::is('admin/tabelfranchise') ? 'active' : '' }}">
                      <a href="/admin/tabelfranchise">
                        <span class="sub-item">Data Franchise</span>
                      </a>
                    </li>
                    <li class="{{ Request::is('admin/tabelfranchisebaru') ? 'active' : '' }}">
                      <a href="/admin/tabelfranchisebaru">
                        <span class="sub-item">Data Pendaftaran Franchise Baru</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
             
              <li class="nav-item">
                <a href="../../../documentation/index.html">
                  <i class="fas fa-file"></i>
                  <p>Pelaporan</p>
                  <span class="badge badge-secondary">1</span>
                </a>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#submenu">
                  <i class="fas fa-bars"></i>
                  <p>Menu Levels</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="submenu">
                  <ul class="nav nav-collapse">
                    <li>
                      <a data-bs-toggle="collapse" href="#subnav1">
                        <span class="sub-item">Level 1</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse" id="subnav1">
                        <ul class="nav nav-collapse subnav">
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li>
                      <a data-bs-toggle="collapse" href="#subnav2">
                        <span class="sub-item">Level 1</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse" id="subnav2">
                        <ul class="nav nav-collapse subnav">
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li>
                      <a href="#">
                        <span class="sub-item">Level 1</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>