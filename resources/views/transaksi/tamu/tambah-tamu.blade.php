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
            <form action="{{ route('tamu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <label for="" class="form-control-label">Nama Tamu</label>
                    <input type="text" name="nama" value="{{old('nama')}}" class="form-control @error('nama') is-invalid @enderror">
                    @error('nama')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    
                    <label for="" class="form-control-label">Jenis Identitas</label>
                    <br>                  
                    <div class="custom-control custom-radio custom-control-inline  ml-2 mr-5">
                        <input type="radio" value="KTP" id="customRadioInline1" name="jenis_identitas" class="custom-control-input" @error('jenis_identitas') is-invalid @enderror" {{old('jenis_identitas') == 'KTP' ? 'checked' : ''}}>
                        <label class="custom-control-label" for="customRadioInline1">KTP</label>
                    </div>
                    
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" value="SIM" id="customRadioInline2" name="jenis_identitas" class="custom-control-input" @error('jenis_identitas') is-invalid @enderror" {{old('jenis_identitas') == 'SIM' ? 'checked' : ''}}>
                        <label class="custom-control-label" for="customRadioInline2">SIM</label>
                    </div>
                    @error('jenis_identitas')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    <br>

                    <label for="" class="form-control-label">Nomor Identitas</label>
                    <input type="text" name="no_identitas" value="{{old('no_identitas')}}" class="form-control @error('no_identitas') is-invalid @enderror">
                    @error('no_identitas')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    
                    <!-- <label for="" class="form-control-label">Foto Identitas</label>
                    <input type="file" name="foto_identitas" value="{{old('foto_identitas')}}" class="form-control @error('foto_identitas') is-invalid @enderror">
                    @error('foto_identitas')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br> -->

                    <label for="" class="form-control-label">Company</label>
                    <input type="text" name="company" value="{{old('company')}}" class="form-control @error('company') is-invalid @enderror">
                    @error('company')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    
                    <label for="" class="form-control-label">Street</label>
                    <input type="text" name="street" value="{{old('street')}}" class="form-control @error('street') is-invalid @enderror">
                    @error('street')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    
                    <label for="" class="form-control-label">City</label>
                    <input type="text" name="city" value="{{old('city')}}" class="form-control @error('city') is-invalid @enderror">
                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <br>
                    
                    <label for="" class="form-control-label">Phone</label>
                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone')
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