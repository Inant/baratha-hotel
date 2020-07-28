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
            <form action="{{ route('kategori-kamar.store') }}" method="POST">
                @csrf
                <div class="card-body">
                  <label for="" class="form-control-label">Kategori Kamar</label>
                  <input type="text" name="kategori_kamar" class="form-control @error('kategori_kamar') is-invalid @enderror" value="{{old('kategori_kamar')}}" placeholder="ex : Deluxe">
                  @error('kategori_kamar')
                      <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Tarif</label>
                  <input type="number" name="harga" class="form-control @error('kategori_kamar') is-invalid @enderror" value="{{old('harga')}}" placeholder="ex : 500000">
                  @error('harga')
                      <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                  <br>

                  <label for="" class="form-control-label">Deskripsi</label>
                  <textarea name="deskripsi" id="deskripsi" class="form-control @error('kategori_kamar') is-invalid @enderror">{{old('deskripsi', '-')}}</textarea>
                  @error('harga')
                      <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                  <br>

                <label for="" class="form-control-label">Service</label>
                <select name="id_service[]" id="id_service" class="form-control select2 @error('id_service') ? 'is-invalid' : '' @enderror" multiple>
                    @foreach ($service as $item)
                        <option value="{{$item->id}}" {{old('id_service') && in_array($item->id, old('id_service')) ? 'selected' : ''}}>{{$item->service}}</option>
                    @endforeach
                </select>
                @error('id_service')
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