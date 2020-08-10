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
            @php
                $kodeTrx = str_replace('/', '-', $transaksi->kode_transaksi);
            @endphp
            <form action="{{ route('transaksi.update', $kodeTrx) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card-body">
                    <label for="" class="form-control-label">Kode Transaksi</label>
                    <input type="text" name="kode_transaksi" value="{{$transaksi->kode_transaksi}}" class="form-control" readonly>
                    @error('kode_transaksi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Status</label>
                    <input type="text" name="status" value="{{$transaksi->status == 'Check In' ? 'Check In' : 'Booking'}}" class="form-control" readonly>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Nama Tamu</label>
                    <select name="id_tamu" class="form-control select2  @error('id_tamu') is-invalid @enderror">
                        <option value="">-- Pilih Tamu --</option>
                        @foreach ($tamu as $item)
                            <option value="{{$item->id}}" {{old('id_tamu', $transaksi->id_tamu) == $item->id ? 'selected' : ''}} > {{$item->nama}} </option>
                        @endforeach
                    </select>
                    @error('id_tamu')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <label for="" class="form-control-label">Tanggal Check In</label>
                    <input type="date" name="tgl_checkin" value="{{old('tgl_checkin', $transaksi->tgl_checkin)}}" class="form-control datepicker @error('tgl_checkin') is-invalid @enderror" id="tgl_checkin">
                    @error('tgl_checkin')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Tanggal Check Out</label>
                    <input type="date" name="tgl_checkout" value="{{old('tgl_checkout', $transaksi->tgl_checkout)}}" class="form-control datepicker @error('tgl_checkout') is-invalid @enderror" id="tgl_checkout">
                    @error('tgl_checkout')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Nomor Kamar</label>
                    <select name="id_kamar" id="id_kamar" class="form-control select2" data-url="{{url('transaksi/get-kamar')}}">
                        <option value="">--Pilih Kamar--</option>
                        @foreach ($kamar as $item)
                            <option value="{{$item->id}}" {{old('id_kamar', $transaksi->id_kamar) == $item->id ? 'selected' : ''}} >{{$item->no_kamar}}</option>
                        @endforeach
                    </select>
                    @error('id_kamar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <label for="" class="form-control-label">Keterangan</label>
                    <input type="text" name="keterangan" value="{{old('keterangan', $transaksi->keterangan)}}" class="form-control @error('keterangan') is-invalid @enderror">
                    @error('keterangan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                    <button class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection