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
            <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <label for="" class="form-control-label">Kode Transaksi</label>
                    <input type="text" name="kode_transaksi" value="{{$kode_transaksi}}" class="form-control" readonly>
                    @error('kode_transaksi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    {{-- <label for="" class="form-control-label">Status</label> --}}
                    <input type="hidden" name="status" value="Check In" class="form-control">
                    {{-- @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br> --}}

                    <label for="" class="form-control-label">Nama Tamu</label>
                    <select name="id_tamu" class="form-control select2  @error('id_tamu') is-invalid @enderror">
                        <option value="">-- Pilih Tamu --</option>
                        @foreach ($tamu as $item)
                        <?php 
                            $tamu = (isset($_GET['id_tamu'])) && $_GET['id_tamu']==$item->id ? 'selected' : '';
                        ?>
                            <option value="{{$item->id}}" {{old('id_tamu') == $item->id ? 'selected' : ''}} {{$tamu}} > {{$item->nama}} </option>
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
                    <input type="text" name="tgl_checkin" value="{{old('tgl_checkin', date('Y-m-d'))}}" class="form-control datepicker @error('tgl_checkin') is-invalid @enderror" id="tgl_checkin">
                    @error('tgl_checkin')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Tanggal Check Out</label>
                    <input type="text" name="tgl_checkout" value="{{old('tgl_checkout')}}" class="form-control datepicker @error('tgl_checkout') is-invalid @enderror" id="tgl_checkout">
                    @error('tgl_checkout')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Nomor Kamar</label>
                    <select name="id_kamar[]" multiple='multiple' id="id_kamar" class="form-control select2" required data-url="{{url('transaksi/get-kamar')}}">
                        <option value="">--Pilih Kamar--</option>
                    </select>
                    @error('id_kamar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <label for="">Pemesanan Via</label>
                    <select name="tipe_pemesanan" id="tipe_pemesanan" class="form-control @error('tipe_pemesanan') is-invalid @enderror">
                        <option value="">--Pemesanan Via--</option>
                        <option value="Offline" {{old('tipe_pemesanan') == 'Offline' ?  'selected' : ''}}>Offline</option>
                        <option value="Traveloka" {{old('tipe_pemesanan') == 'Traveloka' ? 'selected' : ''}}>Traveloka</option>
                        <option value="Booking" {{old('tipe_pemesanan') == 'Booking' ? 'selected' : ''}}>Booking.com</option>
                        <option value="Travel Agent" {{old('tipe_pemesanan') == 'Travel Agent' ? 'selected' : ''}}>Travel Agent</option>
                    </select>
                    @error('tipe_pemesanan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <label for="" class="form-control-label">Keterangan</label>
                    <input type="text" name="keterangan" value="{{old('keterangan')}}" class="form-control @error('keterangan') is-invalid @enderror">
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