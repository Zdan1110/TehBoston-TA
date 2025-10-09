@section ('Title')
Tabel Produk
@endsection
@extends('admin.v_dashboardcoba')
@section('content')
    <div class="wrapper">
      <!-- Sidebar -->
      <!-- End Sidebar -->


          <!-- End Navbar -->

        <div class="container" style="margin-top:1px">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
            </div>
            <div class="row">
              <div class="row">
                <div class="col-sm-6 col-md-3">
                  <div class="card card-stats card-round">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col-icon">
                          <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-users"></i>
                          </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                          <div class="numbers">
                            <p class="card-category">Pendaftar</p>
                            <h4 class="card-title">{{ $jumlahPendaftar }}</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-user-check"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Franchise</p>
                            <h4 class="card-title">{{ $jumlahFranchise }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-success bubble-shadow-small"
                        >
                          <i class="fas fa-luggage-cart"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Pendapatan</p>
                          <h4 class="card-title">{{ 'Rp ' . number_format($pendapatan, 0, ',', '.') }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-secondary bubble-shadow-small"
                        >
                          <i class="far fa-check-circle"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category" >Penjualan tertinggi</p>
                          <p class="card-title" >{{ $topFranchise->nama_franchise }}</p>
                          <h4 class="card-title">{{ 'Rp ' . number_format($topFranchise->total_penjualan, 0, ',', '.') }}</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
                  <!-- Filter Form -->
              <form method="GET" action="{{ url('/admin') }}" class="filter-section" style="margin-bottom: 15px;">
                <label><strong>Filter Bulan:</strong></label>
                <input type="month" name="bulan_awal" value="{{ request('bulan_awal') }}">
                <span>sampai</span>
                <input type="month" name="bulan_akhir" value="{{ request('bulan_akhir') }}">
                <button type="submit">Filter</button>
              </form>

  <div class="row">
    {{-- Chart utama --}}
    <div class="col-md-8" style="width: 1300px">
      <div class="card card-round">
        <div class="card-header">
          <div class="card-head-row">
            <div class="card-tools">
              <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                <span class="btn-label">
                  <i class="fa fa-pencil"></i>
                </span>
                Export
              </a>
              <a href="#" class="btn btn-label-info btn-round btn-sm">
                <span class="btn-label">
                  <i class="fa fa-print"></i>
                </span>
                Print
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          {!! $chart->container() !!}
        </div>
      </div>
    </div>
          </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <h4 class="card-title">Franchise</h4>
                      <div class="card-tools">
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs"
                        >
                          <span class="fa fa-angle-down"></span>
                        </button>
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"
                        >
                          <span class="fa fa-sync-alt"></span>
                        </button>
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs"
                        >
                          <span class="fa fa-times"></span>
                        </button>
                      </div>
                    </div>
                    <p class="card-category">
                     Franchise dengan pendapatan tertinggi  
                    </p>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="table-responsive table-hover table-sales">
                          <table class="table">
                            <tbody>
                              <tr>
                                <td>
                                    Agus
                                </td>
                                <td>Sukamelang</td>
                                <td class="text-end">2.320</td>
                                <td class="text-end">42.18%</td>
                              </tr>
                              <tr>
                                <td>
                                  Asep
                                </td>
                                <td>Cigadung</td>
                                <td class="text-end">240</td>
                                <td class="text-end">4.36%</td>
                              </tr>
                              <tr>
                                <td>
                                  Augy
                                </td>
                                <td>Australia</td>
                                <td class="text-end">119</td>
                                <td class="text-end">2.16%</td>
                              </tr>
                              <tr>
                                <td>
                                  Ziddan
                                </td>
                                <td>Russia</td>
                                <td class="text-end">1.081</td>
                                <td class="text-end">19.65%</td>
                              </tr>
                              <tr>
                                <td>
                                  Syafiq
                                </td>
                                <td>Pegaden</td>
                                <td class="text-end">1.100</td>
                                <td class="text-end">20%</td>
                              </tr>
                              <tr>
                                <td>
                                  Alwi
                                </td>
                                <td>Perumnas</td>
                                <td class="text-end">640</td>
                                <td class="text-end">11.63%</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mapcontainer">
                          <div
                            id="world-map"
                            class="w-100"
                            style="height: 300px"
                          ></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="card card-round">
                  <div class="card-body">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Pendaftar Mitra Terbaru</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-list py-4">
                      <div class="item-list">
                        <div class="avatar">
                          <img
                          src="{{ asset('kaiadmin-lite-1.2.0/assets/img/jm_denis.jpg') }}"

                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Dadang</div>
                          <div class="status">Cibogo</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white"
                            >CF</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Adrian</div>
                          <div class="status">Sidodadi</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="kaiadmin-lite-1.2.0/assets/img/talha.jpg"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Acung</div>
                          <div class="status">Kasomalang</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="kaiadmin-lite-1.2.0/assets/img/chadengle.jpg"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Wawayu</div>
                          <div class="status">Panglejar</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white bg-primary"
                            >H</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Hizrian</div>
                          <div class="status">Cipaku</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white bg-secondary"
                            >F</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Farrah</div>
                          <div class="status">Dolog</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Transaksi</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <!-- Projects table -->
                      <table class="table align-items-center mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">Payment Number</th>
                            <th scope="col" class="text-end">Date & Time</th>
                            <th scope="col" class="text-end">Amount</th>
                            <th scope="col" class="text-end">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">Rp200.000</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">Rp250.000</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">Rp2.000.000</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">Rp500.000</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">Rp200.000</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">Rp700.000</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">Rp1.000.000</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

       
      </div>

      <!-- Custom template | don't include it in your project! -->

      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js') }}"></script>

<!-- jQuery Scrollbar -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<!-- Chart JS -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/chart.js/chart.min.js') }}"></script>

<!-- jQuery Sparkline -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

<!-- Chart Circle -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/datatables/datatables.min.js') }}"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<!-- jQuery Vector Maps -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/jsvectormap/world.js') }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

<!-- Kaiadmin JS -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js') }}"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/setting-demo.js') }}"></script>
<script src="{{ asset('kaiadmin-lite-1.2.0/assets/js/demo.js') }}"></script>

<!-- CDN for ApexCharts (if needed) -->
<script src="{{ $chart->cdn() }}"></script>

<script>
  $(document).ready(function () {
    $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
      type: "line",
      height: "70",
      width: "100%",
      lineWidth: "2",
      lineColor: "#177dff",
      fillColor: "rgba(23, 125, 255, 0.14)",
    });

    $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
      type: "line",
      height: "70",
      width: "100%",
      lineWidth: "2",
      lineColor: "#f3545d",
      fillColor: "rgba(243, 84, 93, .14)",
    });

    $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
      type: "line",
      height: "70",
      width: "100%",
      lineWidth: "2",
      lineColor: "#ffa534",
      fillColor: "rgba(255, 165, 52, .14)",
    });

    var options = {
      chart: {
        type: 'line',
        height: 160
      },
      series: [{
        name: 'Users',
        data: [10, 12, 15, 18, 17, 20]
      }],
      xaxis: {
        categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
      }
    }

    var chart = new ApexCharts(document.querySelector("#lineChart"), options);
    chart.render();
  });
</script>

<!-- Render Laravel Chart -->
{!! $chart->script() !!}

</body>
</html>
@endsection

