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
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="budget">Kode Pemesanan</th>
                    <th scope="col" class="sort" data-sort="budget">Nomor Kamar</th>
                    <th scope="col" class="sort" data-sort="name">Nama Tamu</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check In</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check Out</th>
                    <th scope="col" class="sort" data-sort="name">Status</th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                    @endphp
                    @foreach ($data as $value)
                    <?php
                            $getNoKamar = \DB::table('detail_transaksi as dt')->join('kamar as k','dt.id_kamar','k.id')->select('no_kamar')->where('dt.kode_transaksi',$value->kode_transaksi)->get();
                    ?>
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{$value->kode_transaksi}}</td>
                            <td>
                                @foreach ($getNoKamar as $item)
                                    <span class="badge badge-info">{{$item->no_kamar}}</span>
                                @endforeach
                            </td>
                            <td>{{$value->nama}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkin))}}</td>
                            <td>{{date('d-m-Y', strtotime($value->tgl_checkout))}}</td>
                            <td>
                                <span class="badge badge-info">Pemesanan Online : {{$value->status}}</span>
                            </td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                  </tr>
                </tbody>
            </table>
            {{$data->appends(Request::all())->links()}}
            </div>
         </div>
    </div>
</div>
@endsection