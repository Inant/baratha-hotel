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
            <form action="{{ route('fasilitas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <label for="" class="form-control-label">Service</label>
                    <input type="text" name="service" value="{{old('service')}}" class="form-control @error('service') is-invalid @enderror" placeholder="WiFi">
                    @error('service')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Icon</label>
                    <input type="text" name="icon" value="{{old('icon')}}" class="form-control @error('icon') is-invalid @enderror" placeholder="fa fa-wifi">
                    @error('icon')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Kategori Service</label>
                    <select name="id_kategori" class="form-control select2 @error('id_kategori') is-invalid @enderror" id="">
                        <option value="">---Pilih Kategori---</option>
                        @foreach ($kategoris as $item)
                    <option value="{{$item->id}}" {{old('id_kategori') == $item->id ? 'selected' : ''}}> {{$item->kategori}} </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                    <button class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection