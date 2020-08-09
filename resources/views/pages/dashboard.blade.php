@extends('common/template')
@section('content')
<!-- Header -->
<div class="header bg-white pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
      </div>
      <!-- Card stats -->
      <div class="row">
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Jumlah Tipe Kamar</h5>
                  <span class="h2 font-weight-bold mb-1"> {{\App\KategoriKamar::count()}} </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                    <i class="ni ni-bullet-list-67"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                <span class="text-nowrap">Since last month</span> --}}
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Jumlah Kamar</h5>
                  <span class="h2 font-weight-bold mb-1"> {{\App\Kamar::count()}} </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                    <i class="ni ni-box-2"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                <span class="text-nowrap">Since last month</span> --}}
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Transaksi</h5>
                  @php
                      $month = date('m');
                      $year = date('Y');
                  @endphp
                  <span class="h2 font-weight-bold mb-1"> {{\App\Transaksi::whereMonth('waktu', $month)->whereYear('waktu', $year)->count()}} </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                    <i class="ni ni-cart"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span> --}}
                {{-- <span class="text-nowrap">*Bulan Ini</span> --}}
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-6">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-2">Pemasukan Bulan Ini</h5>
                  <span class="h2 font-weight-bold mb-1"> {{number_format(\App\Pembayaran::select(\DB::raw('SUM(grandtotal) as pemasukan'))->whereMonth('waktu', $month)->whereYear('waktu', $year)->get()[0]->pemasukan, 0, ',', '.')}} </span>
                </div>
                <div class="col-auto mt-1">
                  <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                    <i class="ni ni-bag-17"></i>
                  </div>
                </div>
              </div>
              <p class="mt-4 mb-0 text-sm">
                {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span> --}}
                {{-- <span class="text-nowrap">*Bulan Ini</span> --}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card bg-default">
          <div class="card-header bg-transparent">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="text-light text-uppercase ls-1 mb-1">Grafik Pendapatan</h6>
                <h5 class="h3 text-white mb-0">Pendapatan Per Bulan</h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            <!-- Chart -->
            {!! $pemasukanChart->container() !!}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header bg-transparent">
            <div class="row align-items-center">
              <div class="col">
                {{-- <h6 class="text-light text-uppercase ls-1 mb-1">Reservation Chart</h6> --}}
                <h5 class="h3 mb-0">Reservation Chart</h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            @php
              $tgl_akhir = date('t');
              $awal = date('d', strtotime($dari));
              $awal = str_replace('0', '', $awal);
            @endphp
            <br>
            <div class="table-responsive">
              {{-- <table class="table table-hover table-striped table-bordered">
                <thead>
                  <tr>
                    <th>No Kamar</th>
                      @for ($i = $awal; $i < $tgl_akhir; $i++)
                      <th>{{$i < 10 ? '0'.$i : $i}}</th>
                      @endfor
                  </tr>
                </thead>
              </table> --}}
              <table class="table table-hover table-bordered table-striped">
                <tbody>
                  @foreach ($kamar as $val)
                      <tr>
                        <td>{{$val->no_kamar}}</td>
                        @for ($i = $awal; $i < $tgl_akhir; $i++)
                          @php
                            $tgl = $i < 10 ? '0'.$i : $i;
                            $month = date('m');
                            $year = date('Y');
                            $current = $year.'-'.$month.'-'.$tgl;
                            $checkIn = \DB::table('transaksi')
                            ->where('id_kamar', $val->id)
                            ->where('tgl_checkin', '<=', $current)
                            ->where('tgl_checkout', '>=', $current)
                            ->where('status', 'Check In')
                            ->count();

                            $booking = \DB::table('transaksi')
                            ->where('id_kamar', $val->id)
                            ->where('tgl_checkin', '<=', $current)
                            ->where('tgl_checkout', '>=', $current)
                            ->where('status', 'Booking')
                            ->count();

                            if ($checkIn == 1) {
                              $class = 'bg-red';
                            }
                            elseif ($booking == 1) {
                              $class = 'bg-yellow';
                            }
                            else{
                              $class = 'bg-success';
                            }
                          @endphp
                          <td class="text-white {{$class}}">{{$i < 10 ? '0'.$i : $i}}</td>
                        @endfor
                      </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <br>
            <table>
              <tr>
                <td class="bg-red" width="25px"></td>
                <td></td>
                <td>Check In</td>

                <td width="30px"></td>

                <td class="bg-yellow" width="25px"></td>
                <td></td>
                <td>Booking</td>

                <td width="30px"></td>

                <td class="bg-success" width="25px"></td>
                <td></td>
                <td>Available</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection