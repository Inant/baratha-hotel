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
              <input type="hidden" id="kode" name='status_bayar' value="{{$transaksi->status_bayar}}">
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
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Check In</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('tgl_checkin') is-invalid @enderror" name='tgl_checkin' value="{{$transaksi->tgl_checkin}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Check Out</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('tgl_checkout') is-invalid @enderror" name='tgl_checkout' value="{{$transaksi->tgl_checkout}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="" class="form-control-label">Durasi</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <?php 
                            $diff = strtotime($transaksi->tgl_checkout) - strtotime($transaksi->tgl_checkin);
                            $durasi = abs(round($diff / 86400));
                          ?>

                          <input type="text" class="form-control form-line" name='' value="{{$durasi}}" readonly>
                        </div>
                    </div>

                    {{-- <div class="col-md-3 mb-2">
                        <label for="" class="form-control-label">No Kamar</label>
                        <div class="form-line-check">
                          <span class='fa fa-check-circle'></span>
                          <input type="text" class="form-control form-line @error('id_kamar') is-invalid @enderror" name='id_kamar' value="{{$transaksi->kamar->no_kamar}} Hari" readonly>
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
                        <th>Tarif</th>
                        <th>Durasi</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody class="list">
                      <?php                       
                        $kamar = \DB::table('detail_transaksi as dt')->select('k.no_kamar','kk.kategori_kamar','kk.harga')->join('kamar as k','dt.id_kamar','k.id')->join('kategori_kamar as kk','k.id_kategori_kamar','kk.id')->where('dt.kode_transaksi',$transaksi->kode_transaksi)->get();
                        $total = 0;
                        $charge = 0;
                        $diskon = 0;
                        $jenis_pembayaran = 'Tunai';
                        foreach($kamar as $data){
                          $subtotal = $data->harga * $durasi;
                          $total+=$subtotal;
                          ?>
                            <tr>
                              <td>{{$data->kategori_kamar}}</td>
                              <td>{{$data->no_kamar}}</td>
                              <td>{{number_format($data->harga,0,',','.')}}</td>
                              <td>{{$durasi}} Hari</td>
                              <td>{{number_format($subtotal,0,',','.')}}</td>
                            </tr>
                          <?php
                        }
                        $tax = 10 * $total / 100;
                        $grandtotal = $tax + $total;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr <?php echo $transaksi->status_bayar!='DP50%' ? 'class="bg-dark text-white"' : '' ?> >
                          <td colspan='4' class='text-center font-weight-bold'>Total</td>
                          <td>{{number_format($total,0,',','.')}}</td>
                        </tr>
                        <?php
                        if($transaksi->status_bayar=='DP50%'){
                          ?>
                              <tr>
                                <td colspan='4' class='text-center font-weight-bold'>Total + PPN 10%</td>
                                <td>{{number_format($grandtotal,0,',','.')}}</td>
                              </tr>                          
                              <tr>
                                <td colspan='4' class='text-center font-weight-bold'>DP 50%</td>
                                <td>{{number_format($grandtotal/2,0,',','.')}}</td>
                              </tr>                          
                              <tr class="bg-dark text-white">
                                <td colspan='4' class='text-center font-weight-bold'>Sisa</td>
                                <td>{{number_format($grandtotal/2,0,',','.')}}</td>
                              </tr>                          
                        <?php } ?>
                    </tfoot>
                  </table>
                  <hr>
                  <div class="row">
                  <?php 
                      if($transaksi->status_bayar=='DP50%'){
                        $bayar = $grandtotal/2;
                        $total = $grandtotal/2;
                      }
                      else{
                        $bayar = $grandtotal;
                      }
                    ?>
                    <input type="hidden" name="total" id="total" class="form-control" value="{{$total}}" readonly>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Diskon</strong></label>
                      <input type="number" name="diskon" class="form-control diskon_tambahan <?php echo $transaksi->status_bayar=='DP50%' ? 'dp' : '' ?>" value="{{old('diskon', $diskon)}}" data-tipe='rp'>
                    </div>
                    <?php 
                      if($transaksi->status_bayar!='DP50%'){
                    ?>
                    <div class="col-4 mb-2">
                      <label for=""><strong>PPN 10%</strong></label>
                      <input type="number" name="tax" class="form-control" value="{{old('tax', $tax)}}" data-tipe='rp' readonly id="tax">
                    </div>
                    <?php } ?>
                    <div class="col-4 mb-2">
                      <label for=""><strong>Metode Pembayaran</strong></label>
                      <select name="jenis_pembayaran" class="form-control select2 @error('bayar') is-invalid @enderror" id="jenis_bayar">
                        <option value="Tunai" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Tunai' ? 'selected' : ''}}>Tunai</option>
                        <option value="Debit BCA" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Debit BCA' ? 'selected' : ''}}>Debit BCA</option>
                        <option value="Debit BRI" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Debit BRI' ? 'selected' : ''}}>Debit BRI</option>
                        <option value="Debit Bank Lain" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Debit Bank Lain' ? 'selected' : ''}}>Debit Bank Lain</option>
                        <option value="Kredit BCA" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Kredit BCA' ? 'selected' : ''}}>Kredit BCA</option>
                        <option value="Kredit BRI" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Kredit BRI' ? 'selected' : ''}}>Kredit BRI</option>
                        <option value="Kredit Bank Lain" {{old('jenis_pembayaran', $jenis_pembayaran) == 'Kredit Bank Lain' ? 'selected' : ''}}>Kredit Bank Lain</option>
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
                        <input type="number" name="charge" id="charge" class="form-control" value="{{old('charge', $charge)}}">
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
                      <h1 class='text-dark'>Grand Total : Rp. <span class="text-orange" id='idrGrandTotal'>{{number_format($bayar,0,',','.')}}</span></h1>

                      <input type="hidden" name="subtotal" id="subtotal" value="{{$bayar}}">
                      <input type="hidden" name="grandtotal" id="grand_total" class="form-control form-line text-lg text-orange font-weight-bold" value="{{$bayar}}">
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