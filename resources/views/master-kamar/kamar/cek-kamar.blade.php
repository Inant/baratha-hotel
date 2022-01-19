@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-6">
                  <h3 class="mb-0">{{$pageInfo}}</h3>
                </div>
                {{-- <div class="col-6 text-right">
                </div> --}}
              </div>
            </div>
            <div class="card-body">
                <form action="" method="get">
                  <div class="row align-items-center">
                    <div class="col-4">
                        <label for="" class="form-control-label">Dari</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                            </div>
                            <input type="text" class="datepicker form-control" name="dari" value="{{isset($_GET['dari']) ? $_GET['dari'] : ''}}" required>
                        </div>                
                    </div>
                    <div class="col-4">
                        <label for="" class="form-control-label">Sampai</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                            </div>
                            <input type="text" class="datepicker form-control" name="sampai" value="{{isset($_GET['sampai']) ? $_GET['sampai'] : ''}}" required>
                        </div>                
                    </div>
                    <div class="col mt-4">
                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                      <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                    </div>
                  </div>
                </form>
                @if (isset($_GET['dari']) && isset($_GET['sampai']))
                  <hr>
                  <div class="row">
                    <div class="col-lg-6">
                      <label for=""><strong>Kamar Tersedia</strong></label>
                      <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                          <thead class="thead-light">
                            <tr>
                              <th>No</th>
                              <th>Kategori Kamar</th>
                              <th>Nomor Kamar</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($kamarTersedia as $key => $value)
                              <tr>
                                  <td>{{$key + 1}}</td>
                                  <td>{{$value->kategori_kamar}}</td>
                                  <td>{{$value->no_kamar}}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                    
                    <div class="col-lg-6">
                      <label for=""><strong>Kamar Tidak Tersedia</strong></label>
                      <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                          <thead class="thead-light">
                            <tr>
                              <th>No</th>
                              <th>Kategori Kamar</th>
                              <th>Nomor Kamar</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($kamarTidakTersedia as $key => $value)
                              <tr>
                                  <td>{{$key + 1}}</td>
                                  <td>{{$value->kategori_kamar}}</td>
                                  <td>{{$value->no_kamar}}</td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection