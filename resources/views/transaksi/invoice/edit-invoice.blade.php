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
            <form action="{{ route('transaksi.save-invoice')}}" method="post">
              @csrf
              <input type="hidden" id="kode" name='kode_transaksi' value="{{$transaksi->kode_transaksi}}" readonly>
              <div class="card-body">
              <h6 class="heading-small text-muted mb-4">Informasi Umum</h6>
                  <div class="row">
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Nama Tamu</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('nama_tamu') is-invalid @enderror" name='nama_tamu' value="{{$transaksi->tamu->nama}}" readonly>
                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Jenis Identitas</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('jenis_identitas') is-invalid @enderror" name='jenis_identitas' value="{{$transaksi->tamu->jenis_identitas}}" readonly>
                      </div>
                    </div>
                    
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">No Identitas</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('no_identitas') is-invalid @enderror" name='no_identitas' value="{{$transaksi->tamu->no_identitas}}" readonly>
                        </div>
                    </div>

                    {{-- <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">No Kamar</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('id_kamar') is-invalid @enderror" name='id_kamar' value="{{$transaksi->kamar->no_kamar}}" readonly>
                      </div>
                    </div> --}}
                  </div>
                  <h6 class="heading-small text-muted my-4">Detail Transaksi</h6>
                  <table class="table align-items-center table-flush table-striped table-hover">
                    <thead class="thead-light">
                      <tr>
                        {{-- <th>#</th> --}}
                        <th>Kategori Kamar</th>
                        <th>No Kamar</th>
                        <th>Tanggal Check In</th>
                        <th>Tanggal Check Out</th>
                        <th>Tarif</th>
                        <th>Durasi</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="list">
                      @php                       
                      $kamar = \App\Kamar::with('kategori')->where('id', $transaksi->id_kamar)->get()[0];
                      $diff = strtotime($transaksi->tgl_checkout) - strtotime($transaksi->tgl_checkin);
                      $durasi = abs(round($diff / 86400));
                      $subtotal = $durasi * $kamar->kategori->harga;
                      $tax = $subtotal * 10 /100;
                      $diskon = 0;
                      $charge = 0;
                      $grandtotal = $subtotal + $tax;
                      $jenis_pembayaran = 'Tunai';
                      $cekPembayaran = \App\Pembayaran::where('kode_transaksi', $transaksi->kode_transaksi)->count();
                      if ($cekPembayaran > 0) {
                        $pembayaran = \App\Pembayaran::select('total', 'diskon', 'tax', 'charge', 'grandtotal', 'jenis_pembayaran')->where('kode_transaksi', $transaksi->kode_transaksi)->get()[0];
                        $subtotal = $pembayaran->total;
                        $diskon = $pembayaran->diskon;
                        $tax = $pembayaran->tax;
                        $charge = $pembayaran->charge;
                        $grandtotal = $pembayaran->grandtotal;
                        $jenis_pembayaran = $pembayaran->jenis_pembayaran;
                      }
                    @endphp
                      <tr>
                        <td>{{$kamar->kategori->kategori_kamar}}</td>
                        <td>{{$transaksi->kamar->no_kamar}}</td>
                        <td>{{date('d-m-Y', strtotime($transaksi->tgl_checkin))}}</td>
                        <td>{{date('d-m-Y', strtotime($transaksi->tgl_checkout))}}</td>
                        <td>{{number_format($kamar->kategori->harga, 0, ',', '.')}}</td>
                        <td>{{$durasi}}</td>
                        <td>{{number_format($subtotal, 0, ',', '.')}}</td>
                      </tr>
                    </tbody>
                    <tfoot class='bg-dark text-white'>
                      <tr>
                        <td colspan='4' class='text-center'>TOTAL</td>
                        <td></td>
                        <td></td>
                        <td>{{number_format($subtotal,0,',','.')}}</td>
                      </tr>
                    </tfoot>
                  </table>
                  <hr>
                  <div class="row">
                    <div class="col-4 mb-2">
                      <label for=""><strong>Total</strong></label>
                      <input type="number" name="total" id="total" class="form-control" value="{{$subtotal}}"> 
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Diskon</strong></label>
                      <input type="number" name="diskon" class="form-control diskon_tambahan" value="{{old('diskon', $diskon)}}" data-tipe='rp'>
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>PPN 10%</strong></label>
                      <input type="number" name="tax" class="form-control" value="{{old('tax', $tax)}}" data-tipe='rp' readonly id="tax">
                    </div>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Metode Pembayaran</strong></label>
                      <select name="jenis_pembayaran" class="form-control select2 @error('bayar') is-invalid @enderror" id="jenis_bayar">
                        <option value="Tunai" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Tunai' ? 'selected' : ''}}>Tunai</option>
                        <option value="Debit BCA" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Debit BCA' ? 'selected' : ''}}>Debit BCA</option>
                        <option value="Debit BRI" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Debit BRI' ? 'selected' : ''}}>Debit BRI</option>
                        <option value="Kredit BCA" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Kredit BCA' ? 'selected' : ''}}>Kredit BCA</option>
                        <option value="Kredit BRI" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Kredit BRI' ? 'selected' : ''}}>Kredit BRI</option>
                      </select>
                      @error('jenis_pembayaran')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>                    
                    
                    <div class="col-4 mb-2">
                      <div id="">
                        <label for=""><strong>Charge</strong></label>
                        <input type="number" name="charge" id="charge" class="form-control" value="{{old('charge', $charge)}}" readonly>
                        {{-- @error('jenis_pembayaran')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror --}}
                      </div>
                    </div>
                    {{-- <div class="col-md-4 mt-4">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="isTravel" name="isTravel" value="True" {{old('isTravel') == 'True' ? 'checked' : ''}}>
                        <label class="custom-control-label" for="isTravel">Travel</label>
                        <br>
                        <h5 class="text-default">*Khusus Customer Travel</h5>
                      </div>
                    </div> --}}
                    {{-- <div class="col-4"></div> --}}
                    <div class="col-md-4 mt-4">
                      <h1 class='text-dark'>Grand Total : Rp. <span class="text-orange" id='idrGrandTotal'>{{number_format($grandtotal,0,',','.')}}</span></h1>
                      <input type="hidden" name="grandtotal" id="grand_total" class="form-control form-line text-lg text-orange font-weight-bold" value="{{$grandtotal}}">
                    </div>
                    <div class="col-md-3 mt-3">
                        <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                        <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span> Reset</button>
                    </div>
                  </div>
                  <div class="mt-4">
                  </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endsection