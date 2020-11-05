@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">{{$pageInfo}}</h3>
                </div>
                <div class="col-4 text-right">
                    <form action="{{ route('transaksi.index') }}" class="navbar-search navbar-search-light" id="navbar-search-main">
                        <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input name="keyword" class="form-control" value="{{Request::get('keyword')}}" placeholder="Search" type="text">
                        </div>
                        </div>
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </form>
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
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="budget">Kode Transaksi</th>
                    <th scope="col" class="sort" data-sort="budget">Jenis Pembayaran</th>
                    <th scope="col" class="sort" data-sort="budget">Bukti</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                  {{-- @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                  @endphp --}}
                  @foreach ($data as $value)
                      <tr>
                        @php 
                            $kode = str_replace('/', '-', $value->kode_transaksi);
                        @endphp
                        <td>{{$loop->iteration}}</td>
                        <td>{{$kode}}</td>
                        <td>{{$value->jenis_pembayaran}}</td>
                        <td>
                          <img src="http://localhost:8080/baratha-hotel-api/public/bukti-pembayaran/{{$value->bukti}}" alt="" width="200px">
                        </td>
                        <td class="text-right">
                          <a class="btn btn-primary" href="{{ url('transaksi/online/detail/'.$kode) }}">
                            <span class="fa fa-search"></span>
                            Detail
                          </a>
                        </td>
                      </tr>
                      {{-- @php
                          $no++
                      @endphp --}}
                  @endforeach
                </tbody>
              </table>
              {{-- {{$kategori->appends(Request::all())->links()}} --}}
            </div>
         </div>
    </div>
</div>
@endsection