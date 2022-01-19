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
                    <th scope="col" class="sort" data-sort="name">Tipe Pemesanan</th>
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
                        $detail = \DB::table('detail_transaksi as dt')->select('k.no_kamar')->join('kamar as k','dt.id_kamar','k.id')->where('kode_transaksi',$value->kode_transaksi)->get();
                        $count = count($detail);
                    ?>
                        <tr>
                            <td>{{$no}}</td>
                            <td>
                                @foreach($detail as $c => $d)
                                    <span class="badge badge-success">{{$d->no_kamar}}</span>
                                @endforeach
                            </td>
                            <td>{{$value->tamu->nama}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkin))}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkout))}}</td>
                            <td>{{$value->tipe_pemesanan}}</td>
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