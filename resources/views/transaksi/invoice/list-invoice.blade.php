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
                    <div class="col-8 offset-4">
                        <form action="{{ route('transaksi.list-invoice') }}">
                            <div class="row">
                                <div class="col-4">
                                    <select name="keyTamu" id="keyTamu" class="form-control select2" width="100%">
                                        <option value="">Semua Tamu</option>
                                        @foreach ($tamu as $item)
                                            <option value="{{$item->id}}" {{Request::get('keyTamu') == $item->id ? 'selected' : ''}} > {{$item->nama}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select name="kamar" id="kamar" class="form-control select2" width="100%">
                                        <option value="">Semua Kamar</option>
                                        @foreach ($kamar as $item)
                                            <option value="{{$item->id}}" {{Request::get('kamar') == $item->id ? 'selected' : ''}} > {{$item->no_kamar}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select name="status" id="status" class="form-control select2" width="100%">
                                        <option value="">Semua Status</option>
                                        <option value="Booking" {{Request::get('status') == 'Booking' ? 'selected' : ''}} > Booking</option>
                                        <option value="Check In" {{Request::get('status') == 'Check In' ? 'selected' : ''}} > Check In</option>
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
            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="budget">Nomor Kamar</th>
                    <th scope="col" class="sort" data-sort="name">Nama Tamu</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check In</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check Out</th>
                    <th scope="col" class="sort" data-sort="name">Status</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                    @endphp
                    @foreach ($transaksi as $value)
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{$value->kamar->no_kamar}}</td>
                            <td>{{$value->tamu->nama}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkin))}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkout))}}</td>
                            <td>
                              @if ($value->status == 'Check In')
                                <span class="badge badge-success">{{$value->status}}</span>
                              @elseif($value->status == 'Booking')
                                <span class="badge badge-secondary">{{$value->status}}</span>
                              @endif
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @php
                                            $kodeTrx = str_replace('/', '-', $value->kode_transaksi);
                                            $cek = \App\Pembayaran::where('kode_transaksi', $value->kode_transaksi)->count();
                                        @endphp
                                        <a class="dropdown-item" href="{{ route('transaksi.edit-invoice', $kodeTrx) }}">Edit Invoice</a>
                                        @if ($cek > 0)
                                            <a class="dropdown-item" href="{{ route('transaksi.invoice', $kodeTrx) }}" target="_blank">Cetak Invoice</a>
                                            <a class="dropdown-item" href="{{ route('transaksi.paid', $kodeTrx) }}" onclick="return confirm('{{ __("Apakah anda yakin? (Pastikan sudah mencetak invoice.)") }}')">Telah Terbayar</a>
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