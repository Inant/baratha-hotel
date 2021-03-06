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
            <form action="{{ route('kategori-fasilitas.store') }}" method="POST">
                @csrf
                <div class="card-body">
                  <label for="" class="form-control-label">Kategori Fasilitas</label>
                  <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{old('kategori')}}" placeholder="ex : Hiburan">
                  @error('kategori')
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