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
                    <div class="col-6 offset-4">
                        <form action="{{ route('transaksi.index') }}">
                            <div class="row">
                                <div class="col-5">
                                    <input name="keyword" class="form-control" placeholder="Cari tamu..." type="text" value="{{Request::get('keyword')}}">
                                </div>
                                <div class="col-5">
                                    <select name="kamar" id="kamar" class="form-control select2" width="100%">
                                        <option value="">Semua Kamar</option>
                                        @foreach ($kamar as $item)
                                            <option value="{{$item->id}}" {{Request::get('kamar') == $item->id ? 'selected' : ''}} > {{$item->no_kamar}} </option>
                                        @endforeach
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
            <div class="row mt-3 ml-2 mb-3">
                <div class="col-2">
                    <a href="{{ url('transaksi/check-in') }}">
                        <button class="btn btn-sm btn-success">Check In</button>
                    </a>
                </div>
            </div>
            @if (session('status'))
                <br>
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
                    <th scope="col" class="sort" data-sort="name">Status</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check In</th>
                    <th scope="col" class="sort" data-sort="name">Tanggal Check Out</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                    @endphp
                    @foreach ($transaksi as $value)
                        @php
                            $no_kamar = App\Kamar::select('no_kamar')->where('id', $value->id_kamar)->get()[0]->no_kamar;
                        @endphp
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{$no_kamar}}</td>
                            <td>{{$value->nama_tamu}}</td>
                            <td>{{$value->tgl_checkin}}</td>
                            <td>{{$value->tgl_checkout}}</td>
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
                                <a class="dropdown-item" href="{{ route('transaksi.edit', $value->kode_transaksi) }}">Edit</a>
                                <form action="{{ route('transaksi.destroy', $value->kode_transaksi) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">Hapus</button>
                                </form>
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