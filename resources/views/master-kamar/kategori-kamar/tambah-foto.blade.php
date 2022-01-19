<div class="row row-detail mb-4" data-no='{{$no}}'>
  <div {{isset($n)&&$errors->has('foto.'.$n) ? ' is-invalid' : ''}}">
      <label for="" class="form-control-label">Foto Kamar</label>
      <input type="file" name="foto[]" id="foto" class="form-control">
      @if (isset($n)&&$errors->has('foto.'.$n))
          <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('foto.'.$n) }}</strong>
          </span>
      @endif
  </div>
  
  <div class="col-md-1">
      <div class="dropdown mt-4">
          <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
              <a class="dropdown-item addFoto" data-no='{{$no}}' href="">Tambah</a>
              @if($hapus)
              <a class="dropdown-item deleteFoto" data-no='{{$no}}' href="">Hapus</a>
              @endif
          </div>
      </div>
  </div>
</div>