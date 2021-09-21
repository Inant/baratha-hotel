@extends('common/template')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Berhasil!</strong> {{session('status')}}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Gagal!</strong> {{session('error')}}
                </div>
                @enderror
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h3 class="mb-0">{{ $pageInfo }}</h3>
                        </div>
                        <div class="col-6 text-right">
                            <?php 
                        if(isset($_GET['tipe']) && isset($_GET['dari'])){
                    ?>
                            @if ($_GET['tipe'] == 'general')
                                <a href="<?= route('laporan-reservasi') . "?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&tipe_pembayaran=$_GET[tipe_pembayaran]&tipe_pemesanan=$_GET[tipe_pemesanan]" ?>"
                                    class="btn btn-info btn-sm" target="_blank"><span class="fa fa-print"></span> Cetak
                                    Laporan</a>
                                <a href="<?= route('laporan-reservasi') . "?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&tipe_pembayaran=$_GET[tipe_pembayaran]&tipe_pemesanan=$_GET[tipe_pemesanan]&xls=true" ?>"
                                    class="btn btn-info btn-sm" target="_blank"><span class="fa fa-file-excel"></span>
                                    Export XLS</a>
                            @elseif($_GET['tipe'] == 'pembayaran')
                                <a href="<?= route('laporan-reservasi') . "?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&tipe_pembayaran=$_GET[tipe_pembayaran]&tipe_pemesanan=$_GET[tipe_pemesanan]" ?>"
                                    class="btn btn-info btn-sm" target="_blank"><span class="fa fa-print"></span> Cetak
                                    Laporan</a>
                                <a href="<?= route('laporan-reservasi') . "?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&tipe_pembayaran=$_GET[tipe_pembayaran]&tipe_pemesanan=$_GET[tipe_pemesanan]&xls=true" ?>"
                                    class="btn btn-info btn-sm" target="_blank"><span class="fa fa-file-excel"></span>
                                    Export XLS</a>
                            @else
                                <a href="<?= route('laporan-reservasi') . "?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]" ?>"
                                    class="btn btn-info btn-sm" target="_blank"><span class="fa fa-print"></span> Cetak
                                    Laporan</a>
                                <a href="<?= route('laporan-reservasi') . "?tipe=$_GET[tipe]&dari=$_GET[dari]&sampai=$_GET[sampai]&xls=true" ?>"
                                    class="btn btn-info btn-sm" target="_blank"><span class="fa fa-file-excel"></span>
                                    Export XLS</a>
                            @endif
                            <?php } ?>
                        </div>
                        {{-- <div class="col-6 text-right">
                </div> --}}
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label for="" class="form-control-label">Dari Tanggal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    <input type="text" class="datepicker form-control" name="dari"
                                        value="{{ isset($_GET['dari']) ? $_GET['dari'] : '' }}" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <label for="" class="form-control-label">Sampai Tanggal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><span class="fa fa-calendar"></span></span>
                                    </div>
                                    <input type="text" class="datepicker form-control" name="sampai"
                                        value="{{ isset($_GET['sampai']) ? $_GET['sampai'] : '' }}" required>
                                </div>
                            </div>
                            {{--  @if ($_GET['tipe'] == 'general' || $_GET['tipe'] == 'pembayaran')
                                <div class="col-3">
                                    <label for="" class="form-control-label">Tipe Pembayaran</label>
                                    <select name="tipe_pembayaran" id="" class="select2 form-control">
                                        <option value="">Semua Tipe</option>
                                        <option value="Tunai"
                                            {{ Request::get('tipe_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai
                                        </option>
                                        <option value="BCA"
                                            {{ Request::get('tipe_pembayaran') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                        <option value="BRI"
                                            {{ Request::get('tipe_pembayaran') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                        <option value="Bank Lain"
                                            {{ Request::get('tipe_pembayaran') == 'Bank Lain' ? 'selected' : '' }}>Bank Lain
                                        </option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-control-label">Tipe Pemesanan</label>
                                    <select name="tipe_pemesanan" id="" class="select2 form-control">
                                        <option value="">Semua Tipe Pemesanan</option>
                                        <option value="Website"
                                            {{ Request::get('tipe_pemesanan') == 'Website' ? 'selected' : '' }}>Website
                                        </option>
                                        <option value="Offline"
                                            {{ Request::get('tipe_pemesanan') == 'Offline' ? 'selected' : '' }}>Offline
                                        </option>
                                        <option value="Traveloka"
                                            {{ Request::get('tipe_pemesanan') == 'Traveloka' ? 'selected' : '' }}>Traveloka
                                        </option>
                                        <option value="Booking"
                                            {{ Request::get('tipe_pemesanan') == 'Booking' ? 'selected' : '' }}>Booking.com
                                        </option>
                                        <option value="Travel Agent"
                                            {{ Request::get('tipe_pemesanan') == 'Travel Agent' ? 'selected' : '' }}>Travel
                                            Agent</option>
                                    </select>
                                </div>
                            @endif  --}}
                            <div class="col mt-4">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>
                                    Simpan</button>
                                <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span>
                                    Reset</button>
                            </div>
                        </div>
                    </form>
                    @if (isset($_GET['dari']) && isset($_GET['sampai']))
                    <div class="table-responsive mt-3">
                        <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                            <th>#</th>
                            <th>Kode Transaksi</th>
                            <th>Waktu</th>
                            <th>Nama Tamu</th>
                            <th>No Kamar</th>
                            <th>Lama Menginap</th>
                            <th>Diskon</th>
                            <th>Total</th>
                            <th>Jenis Bayar</th>
                            <th>Tipe Pemesanan</th>
                            <th>Deleted AT</th>
                            {{-- <th>PPN</th> --}}
                            <th></th>
                            </tr>
                        </thead>
                        <tbody class="list">
                        <?php 
                            $total = 0;
                            $total_diskon = 0;
                        ?>
                        @foreach ($laporan as $value)
                        <?php
                            
                            $total_diskon += $value->diskon;
                            // $total_ppn = $total_ppn + $value->total_ppn;
                            $subtotal = $value->grandtotal - $value->charge;
                            // if ($value->isTravel=='True') {
                            //     $biaya_travel = ($subtotal - $value->total_ppn - $value->room_charge) * 10/100;
                            //     $subtotal = $subtotal - $biaya_travel;
                            // }
                            $total = $total + $subtotal;
                            $diff = strtotime($value->tgl_checkout) - strtotime($value->tgl_checkin);
                            $durasi = abs(round($diff / 86400));
                      
                            // $kamar = \App\Kamar::with('transaksi')->where('id', $value->id_kamar)->get()[0];
                            $kamar = \DB::table(\DB::raw('kamar k'))->select('k.no_kamar')->join(\DB::raw('detail_transaksi d'), 'k.id', '=', 'd.id_kamar')->where('d.kode_transaksi', $value->kode_transaksi)->get();      
                        ?>
                                <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$value->kode_transaksi}}</td>
                                <td>{{date('d-m-Y H:i', strtotime($value->waktu))}}</td>
                                <td>{{$value->nama}}</td>
                                <td>
                                  @foreach ($kamar as $item)
                                      <span class="badge badge-success"> {{$item->no_kamar}} </span>
                                  @endforeach
                                </td>
                                <td>{{$durasi}}</td>
                                <td>{{$value->diskon}}</td>
                                <td>{{number_format($subtotal,0,',','.')}}</td>
                                <td>{{$value->jenis_pembayaran}}</td>
                                <td>{{$value->tipe_pemesanan}}</td>
                                <td class="text-center">
                                    @if($value->deleted_at)
                                    <span class="badge badge-danger"> {{$value->deleted_at}} </span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @php
                                                $kodeTrx = str_replace('/', '-', $value->kode_transaksi)
                                            @endphp
                                            <a class="dropdown-item" href="{{ url('transaksi/all-penjualan/delete/'.$kodeTrx) }}">
                                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">Hapus</button>
                                            </a>
                                            {{--  <form action="{{ route('soft-delete-penjualan', $kodeTrx) }}" method="get">
                                                input:hidden
                                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">Hapus</button>
                                            </form>  --}}
                                        </div>
                                    </div>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-dark text-white">
                            <tr>
                            <td colspan='7' class='text-center'><b>TOTAL</b></td>
                            <td>{{number_format($total,0,',','.')}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            </tr>
                        </tfoot>
                        </table>
                      </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
  