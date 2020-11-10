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
              </div>
            </div>
            <div class="card-body py-0 row">
                <div class="col-12">
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
            </div>
            @foreach($data as $item)
            <form action="{{ url('transaksi/online/accept') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kode" value="{{$item->kode_transaksi}}">
                <div class="card-body">
                    <label for="" class="form-control-label">Kode Transaksi</label>
                    <input type="text" name="kode_transaksi" value="{{ $item->kode_transaksi }}" disabled class="form-control @error('kode_transaksi') is-invalid @enderror">
                    <br>
                    <label for="" class="form-control-label">GrandTotal</label>
                    <input type="text" name="grand_total" value="Rp. {{$item->grandtotal}}" disabled class="form-control @error('grand_total') is-invalid @enderror">
                    <br>
                    <label for="" class="form-control-label">Dibayar</label>
                    <input type="text" name="grand_total" value="Rp. {{$item->bayar}}" disabled class="form-control @error('grand_total') is-invalid @enderror">
                    <br>
                    <label for="" class="form-control-label">Sisa yang harus dibayar</label>
                    <input type="text" name="grand_total" value="Rp. {{$item->grandtotal - $item->bayar }}" disabled class="form-control @error('grand_total') is-invalid @enderror">
                    <br>
                    <label for="" class="form-control-label">Bukti pembayaran</label>
                    <br>
                    <img src="http://localhost:8080/baratha-hotel-api/public/bukti-pembayaran/{{$item->bukti}}" alt="" width="350px">
                    <br><br>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Terima</button>
                    <a href="../" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
            @endforeach
        </div>
    </div>
</div>
@endsection