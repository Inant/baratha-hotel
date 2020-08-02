@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-6">
                  <h3 class="mb-0">{{$pageInfo}}</h3>
                </div>
                {{-- <div class="col-6 text-right">
                </div> --}}
              </div>
            </div>
            <div class="card-body">
                <form action="" method="get">
                  <div class="row align-items-center">
                    <div class="col-4">
                        <label for="" class="form-control-label">Dari</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                            </div>
                            <input type="text" class="datepicker form-control" name="dari" value="{{isset($_GET['dari']) ? $_GET['dari'] : $dari}}" required>
                        </div>                
                    </div>
                    <div class="col mt-4">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                      <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                    </div>
                  </div>
                </form>
                @if (isset($_GET['dari']))
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

                      <td class="bg-yellow" width="25px"></td>
                      <td></td>
                      <td>Booking</td>
                      
                      <td class="bg-success" width="25px"></td>
                      <td></td>
                      <td>Available</td>
                    </tr>
                  </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection