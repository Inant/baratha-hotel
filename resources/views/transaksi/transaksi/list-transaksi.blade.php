@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-2">
                    <h3 class="mb-0">{{$pageInfo}}</h3>
                    </div>
                </div>
                <div class="col-12">
                    <form action="{{ route('transaksi.index') }}">
                        <div class="row justify-content-end">
                            <div class="col-4">
                                <select name="keyTamu" id="keyTamu" class="form-control select2" width="100%">
                                    <option value="">Semua Tamu</option>
                                    @foreach ($tamu as $item)
                                        <option value="{{$item->id}}" {{Request::get('keyTamu') == $item->id ? 'selected' : ''}} > {{$item->nama}} </option>
                                    @endforeach
                                </select>
                            </div>
<!--                             <div class="col-3">
                                <select name="kamar" id="kamar" class="form-control select2" width="100%">
                                    <option value="">Semua Kamar</option>
                                    @foreach ($kamar as $item)
                                        <option value="{{$item->id}}" {{Request::get('kamar') == $item->id ? 'selected' : ''}} > {{$item->no_kamar}} </option>
                                    @endforeach
                                </select>
                            </div>
 -->                            <div class="col-3">
                                <select name="status" id="status" class="form-control select2" width="100%">
                                    <option value="">Semua Status</option>
                                    <option value="Booking" {{Request::get('status') == 'Booking' ? 'selected' : ''}} > Booking</option>
                                    <option value="Check In" {{Request::get('status') == 'Check In' ? 'selected' : ''}} > Check In</option>
                                    <option value="Pending" {{Request::get('status') == 'Pending' ? 'selected' : ''}} > Pemesanan Online : Pending</option>
                                </select>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-3 ml-2 mb-3">
                <div class="col-3">
                    <?php 
                        $idTamu = isset($_GET['id_tamu']) ? "?id_tamu=".$_GET['id_tamu'] : "";
                    ?>
                    <a href="{{ url('transaksi/check-in'.$idTamu) }}">
                        <button class="btn btn-sm btn-success">Check In</button>
                    </a>
                    <a href="{{ url('transaksi/booking'.$idTamu) }}" class="ml-4">
                        <button class="btn btn-sm btn-secondary">Booking</button>
                    </a>
                </div>
                {{-- <div class="col-1"> --}}
                {{-- </div> --}}
            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="table-responsive" style="min-height:180px">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="budget">Nomor Kamar</th>
                    <th scope="col" class="sort" data-sort="name">Nama Tamu</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check In</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check Out</th>
                    <th scope="col" class="sort" data-sort="name">Status</th>
                    <th scope="col" class="sort" data-sort="name">Tipe Pemesanan</th>
                    <th scope="col" class="sort" data-sort="name">Keterangan</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                    @endphp
                    @foreach ($transaksi as $value)
                    <?php
                            $getNoKamar = \DB::table('detail_transaksi as dt')->join('kamar as k','dt.id_kamar','k.id')->select('no_kamar')->where('dt.kode_transaksi',$value->kode_transaksi)->get();
                    ?>
                        <tr>
                            <td>{{$no}}</td>
                            <td>
                                @foreach ($getNoKamar as $item)
                                    @if ($value->status == 'Check In')
                                        <span class="badge badge-success">{{$item->no_kamar}}</span>
                                    @elseif($value->status == 'Booking')
                                        <span class="badge badge-secondary">{{$item->no_kamar}}</span>
                                    @elseif($value->status == 'Pending')
                                        <span class="badge badge-info">{{$item->no_kamar}}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>{{$value->nama}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkin))}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkout))}}</td>
                            <td>
                              @if ($value->status == 'Check In')
                                <span class="badge badge-success">{{$value->status}}</span>
                              @elseif($value->status == 'Booking')
                                <span class="badge badge-secondary">{{$value->status}}</span>
                              @elseif($value->status == 'Pending')
                                <span class="badge badge-info">Pemesanan Online : {{$value->status}}</span>
                              @endif
                            </td>
                            <td>{{$value->tipe_pemesanan}}</td>
                            <td>{{$value->keterangan}}</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @php
                                            $kodeTrx = str_replace('/', '-', $value->kode_transaksi)
                                        @endphp
                                        <a class="dropdown-item" href="{{ route('transaksi.edit', $kodeTrx) }}">Edit</a>
                                        {{-- <a class="dropdown-item" href="{{ route('transaksi.invoice', $kodeTrx) }}" target="_blank">Invoice</a> --}}
                                        @if ($value->status == 'Check In' && $value->tgl_checkout <= date('Y-m-d'))
                                            <a class="dropdown-item" href="{{ route('transaksi.checkout', $kodeTrx) }}">Check Out</a>
                                        @elseIf($value->status == 'Booking' && $value->tgl_checkin <= date('Y-m-d'))
                                            <a class="dropdown-item" href="{{ route('transaksi.checkin-booking', $kodeTrx) }}">Check In</a>
                                        @endif
                                        @if ($value->status == 'Check In')
                                            <form action="{{ route('transaksi.destroy', $kodeTrx) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">Hapus</button>
                                            </form>
                                        @elseif($value->status == 'Booking')
                                            <form action="{{ route('transaksi.destroy', $kodeTrx) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">Batal</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                  </tr>
                </tbody>
            </table>
            {{$transaksi->appends(Request::all())->links()}}
            </div>
         </div>
    </div>
</div>
@endsection