<!--
=========================================================
* Argon Dashboard - v1.2.0
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard


* Copyright  Creative Tim (http://www.creative-tim.com)
* Coded by www.creative-tim.com



=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>{{isset($pageInfo) ? $pageInfo.' - ' : ''}}Baratha Hotel</title>
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('img/hotel.png') }}" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.min.css') }}" type="text/css">
  <!-- Page plugins -->
  <!-- Argon CSS -->
  <link rel="stylesheet" href="{{ asset('css/argon.css?v=1.2.0') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
</head>

<body>
<div class="body-click {{!is_null(old('kode_menu.0')) && \Auth::user()->level=='Waiters' ? 'show' : ''}}"></div>
<div class="loading">
  <div class="info">
    <img src="{{asset('img/loading.gif')}}" alt="">
    <p>Loading...</p>
  </div>
</div>
  <?php 
    if(\Auth::user()->level!='Waiters'){
  ?>
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header  align-items-center">
        <a class="navbar-brand" href="javascript:void(0)">
          <img src="{{ asset('img/hotel.png') }}" class="navbar-brand-img" alt="...">
        </a>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">

            <li class="nav-item">
              <a class="nav-link {{Request::segment(1) == '' ? 'active' : ''}}" href="{{url('/')}}">
                <i class="ni ni-tv-2 text-primary"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{!empty(Request::segment(2)) && Request::segment(2) == 'pembayaran-online' ? 'active' :''}}" href="{{url('transaksi/pembayaran-online')}}">
                <i class="ni ni-credit-card text-yellow"></i>
                <?php
                  $countPembayaran = \DB::table('transaksi')->select('kode_transaksi')->where('status_bayar','Menunggu Verifikasi')->where('tipe_pemesanan','Online')->count();
                ?>
                <span class="nav-link-text">Pembayaran Online  <span class="badge badge-primary">{{$countPembayaran}}</span></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{!empty(Request::segment(2)) && Request::segment(2) == 'online' ? 'active' :''}}" href="{{url('transaksi/online')}}">
                <i class="ni ni-world-2 text-pink"></i>
                <?php 
                  $countPemesanan = \DB::table('transaksi')->select('kode_transaksi')->where('status_bayar','Belum')->where('tipe_pemesanan','Online')->count();
                ?>
                <span class="nav-link-text">Pemesanan Online <span class="badge badge-primary">{{$countPemesanan}}</span></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link collapsed {{ Request::segment(1) == 'transaksi' && Request::segment(2)!='online' && Request::segment(2) != 'pembayaran-online' ? 'active' : ''}}" href="" data-toggle="collapse" data-target="#transaksi" aria-expanded='false'>
                <i class="ni ni-credit-card text-orange"></i>
                <span class="nav-link-text">Reservasi</span>
              </a>
                <ul class="navbar-nav nav-collapse collapse" id="transaksi">
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('transaksi/tamu')}}">
                      <span class="nav-link-text">Master Tamu</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('transaksi/transaksi')}}">
                      <span class="nav-link-text">Reservasi</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('transaksi/invoice')}}">
                      <span class="nav-link-text">Invoice</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('transaksi/laporan?tipe=general')}}">
                      <span class="nav-link-text">Laporan</span>
                    </a>
                  </li>
                  
                  {{-- <li class="nav-item">
                    <a class="nav-link" href="{{url('transaksi/pembayaran')}}">
                      <span class="nav-link-text">Pembayaran</span>
                    </a>
                  </li> --}}
                </ul>
            </li>
            @if (\Auth::user()->level == 'Owner')
            <li class="nav-item">
              <a class="nav-link {{Request::segment(1) == 'user' ? 'active' : ''}}" href="{{route('user.index')}}">
                <i class="ni ni-single-02 text-cyan"></i>
                <span class="nav-link-text">Master User</span>
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a class="nav-link collapsed {{Request::segment(1) == 'master-kamar' ? 'active' : ''}}" href="" data-toggle="collapse" data-target="#master-kamar" aria-expanded='false'>
                <i class="ni ni-building text-info"></i>
                <span class="nav-link-text">Master Kamar</span>
              </a>
                <ul class="navbar-nav nav-collapse collapse" id="master-kamar">
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('master-kamar/kategori-kamar')}}">
                      <span class="nav-link-text">Kategori Kamar</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('master-kamar/kamar?id_kategori=4')}}">
                      <span class="nav-link-text">Kamar</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('master-kamar/reservation-chart')}}">
                      <span class="nav-link-text">Reservation Chart</span>
                    </a>
                  </li>
                </ul>
            </li>
            
            <li class="nav-item">
              <a class="nav-link collapsed {{Request::segment(1) == 'master-service' ? 'active' : ''}}" href="" data-toggle="collapse" data-target="#master-service" aria-expanded='false'>
                <i class="ni ni-support-16 text-purple"></i>
                <span class="nav-link-text">Master Fasilitas</span>
              </a>
                <ul class="navbar-nav nav-collapse collapse" id="master-service">
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('master-fasilitas/kategori-fasilitas')}}">
                      <span class="nav-link-text">Kategori Fasilitas</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('master-fasilitas/fasilitas')}}">
                      <span class="nav-link-text">Fasilitas</span>
                    </a>
                  </li>
                </ul>
            </li>


          </ul>
          <!-- Divider -->
          {{-- <ul class="navbar-nav lima-section">
            <li class="nav-item">
              <a class="nav-link active active-pro" href="upgrade.html">
                <span class="nav-link-text text-success"><  By Lima Digital /></span>
              </a>
            </li>
          </ul> --}}
        </div>
      </div>
    </div>
  </nav>
  <?php } ?>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <div class="bg-top">
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary-opacity border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php 
         if(\Auth::user()->level!='Waiters'){
        ?>
        <div class="mr-sm-3">
          <a href="" class="fullpage-version"><span class="fa fa-chevron-left"></span></a>
        </div>
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center  ml-md-auto ">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
            <li class="nav-item d-sm-none">
              <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                <i class="ni ni-zoom-split-in"></i>
              </a>
            </li>
            {{-- <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ni ni-bell-55"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
                <!-- Dropdown header -->
                <div class="px-3 py-3">
                  <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">13</strong> notifications.</h6>
                </div>
                <!-- List group -->
                <div class="list-group list-group-flush">
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="{{ asset('img/theme/team-1.jpg') }}" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">{{\Auth::user()->nama}}</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                      </div>
                    </div>
                  </a>
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="{{ asset('img/theme/team-2.jpg') }}" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">{{\Auth::user()->nama}}</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>3 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">A new issue has been reported for Argon.</p>
                      </div>
                    </div>
                  </a>
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="{{ asset('img/theme/team-3.jpg') }}" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>5 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">Your posts have been liked a lot.</p>
                      </div>
                    </div>
                  </a>
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="{{ asset('img/theme/team-4.jpg') }}" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">Let's meet at Starbucks at 11:30. Wdyt?</p>
                      </div>
                    </div>
                  </a>
                  <a href="#!" class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="{{ asset('img/theme/team-5.jpg') }}" class="avatar rounded-circle">
                      </div>
                      <div class="col ml--2">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <h4 class="mb-0 text-sm">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>3 hrs ago</small>
                          </div>
                        </div>
                        <p class="text-sm mb-0">A new issue has been reported for Argon.</p>
                      </div>
                    </div>
                  </a>
                </div>
                <!-- View all -->
                <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
              </div>
            </li> --}}
          </ul>
      <?php } ?>

          <ul class="navbar-nav align-items-center  ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  {{-- <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="{{ asset('img/theme/team-4.jpg') }}">
                  </span> --}}
                  <div class="media-body  ml-2 d-lg-block">
                    <i class="ni ni-circle-08"></i>
                    <span class="mb-0 text-sm  font-weight-bold">{{\Auth::user()->nama}}</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu  dropdown-menu-right ">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                <a href="{{ route('user.edit', \Auth::user()->id) }}" class="dropdown-item">
                  <i class="ni ni-single-02"></i>
                  <span>Edit Profile</span>
                </a>
                <a href="{{ route('user.ganti-password') }}" class="dropdown-item">
                  <i class="ni ni-settings-gear-65"></i>
                  <span>Ganti Password</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#!" class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
    <!-- Header -->
    <div class="header bg-primary-opacity pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="ni {{$icon}}"></i></a></li>
                  <?php
                    $segments = count(Request::segments());
                    if($segments==0){
                        echo "<li class='breadcrumb-item active'>Baratha Hotel</li>";
                    }
                    else{
                        for ($i=1; $i <= $segments ; $i++) { 
                            $text = ucwords(str_replace('-',' ',Request::segment($i)));
                            $text = $text == 'Transaksi' ? 'Reservasi' : $text;
                            if($i==$segments){
                                echo "<li class='breadcrumb-item active'>$text</li>";
                            }
                            else{
                                echo "<li class='breadcrumb-item'><a href='#'>$text</a></li>";
                            }
                        }
                    }
                  ?>
                </ol>
              </nav>
            </div>
            <?php 
                if(isset($btnRight)){
            ?>
            <div class="col-lg-6 col-5 text-right">
              <a href="{{$btnRight['link']}}" class="btn btn-sm btn-success">{{$btnRight['text']}}</a>
            </div>
            <?php } ?>
        </div>
        </div>
      </div>
    </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6 container-content">
    @yield('content')
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center  text-lg-left  text-muted">
              &copy; 2020 <a href="https://limadigital.id/" class="font-weight-bold ml-1" target="_blank">Baratha Hotel By Lima Digital</a>
            </div>
          </div>
<!--           <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
              </li>
            </ul>
          </div>
 -->        </div>

      </footer>
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/js-cookie/js.cookie.js') }}"></script>
  <script src="{{ asset('vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
  <!-- Optional JS -->
  <script src="{{ asset('vendor/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset('vendor/chart.js/dist/Chart.extension.js') }}"></script>
  <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <!-- Argon JS -->
  <script src="{{ asset('js/argon.js?v=1.2.0') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
  @if (Request::segment(1) == '')
      
  {!! $pemasukanChart->script() !!}
  @endif
</body>

</html>