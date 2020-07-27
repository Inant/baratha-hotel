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
            <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <label for="" class="form-control-label">Nomor Kamar</label>
                    <input type="text" name="no_kamar" value="{{old('no_kamar')}}" class="form-control @error('no_kamar') is-invalid @enderror">
                    @error('no_kamar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>

                    <label for="" class="form-control-label">Kategori Kamar</label>
                    <select name="id_kategori_kamar" class="form-control select2 @error('id_kategori_kamar') is-invalid @enderror" id="">
                        <option value="">---Pilih Kategori---</option>
                        @foreach ($kategoris as $item)
                    <option value="{{$item->id}}" {{old('id_kategori_kamar') == $item->id ? 'selected' : ''}}> {{$item->kategori_kamar}} </option>
                        @endforeach
                    </select>
                    @error('id_kategori_kamar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <label for="" class="form-control-label">Status Kamar</label>
                    <br>                  
                    <div class="custom-control custom-radio custom-control-inline  ml-2 mr-5">
                      <input type="radio" value="Tersedia" id="tersedia" name="status" class="custom-control-input" @error('status') is-invalid @enderror" {{old('status') == 'Tersedia' ? 'checked' : ''}}>
                      <label class="custom-control-label" for="tersedia">Tersedia</label>
                    </div>
                    
                    <div class="custom-control custom-radio custom-control-inline mr-5">
                      <input type="radio" value="Tidak Tersedia" id="tidak-tersedia" name="status" class="custom-control-input" @error('status') is-invalid @enderror" {{old('status') == 'Tidak Tersedia' ? 'checked' : ''}}>
                      <label class="custom-control-label" for="tidak-tersedia">Tidak Tersedia</label>
                    </div>
                    
                    <div class="custom-control custom-radio custom-control-inline mr-5">
                      <input type="radio" value="Dalam Perbaikan" id="dalam-perbaikan" name="status" class="custom-control-input" @error('status') is-invalid @enderror" {{old('status') == 'Dalam Perbaikan' ? 'checked' : ''}}>
                      <label class="custom-control-label" for="dalam-perbaikan">Dalam Perbaikan</label>
                    </div>
                    
                    @error('status')
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