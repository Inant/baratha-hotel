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
                            @elseif ($_GET['tipe'] == 'khusus')
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
                    <ul class="nav nav-tabs mb-4">
                        <li <?= $_GET['tipe'] == 'general' ? 'class="active"' : '' ?>><a href="?tipe=general">Laporan
                                General</a></li>
                        @if (auth()->user()->level == 'Owner')
                        <li <?= $_GET['tipe'] == 'khusus' ? 'class="active"' : '' ?>><a href="?tipe=khusus">Laporan</a></li>
                        @endif
                        {{-- <li <?= $_GET['tipe'] == 'pembayaran' ? 'class="active"' : '' ?>><a href="?tipe=pembayaran">Laporan
                                Pembayaran</a></li> --}}
                        <li <?= $_GET['tipe'] == 'kamar-favorit' ? 'class="active"' : '' ?>><a
                                href="?tipe=kamar-favorit">Kamar Favorit</a></li>
                        {{-- <li <?= $_GET['tipe'] == 'tidak-terjual' ? 'class="active"' : '' ?>><a href="?tipe=tidak-terjual">Menu Tidak Terjual</a></li> --}}
                    </ul>

                    <form action="" method="get">
                        <input type="hidden" value="{{ $_GET['tipe'] }}" name='tipe'>
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
                            @if ($_GET['tipe'] == 'general' || $_GET['tipe'] == 'khusus' || $_GET['tipe'] == 'pembayaran')
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
                            @endif
                            <div class="col mt-4">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>
                                    Simpan</button>
                                <button type="reset" class="btn btn-secondary"><span class="fa fa-times"></span>
                                    Reset</button>
                            </div>
                        </div>
                    </form>
                    @if (isset($_GET['dari']) && isset($_GET['sampai']))
                        @if ($_GET['tipe'] == 'general')
                            @include('transaksi.laporan.laporan-general')
                        @elseif ($_GET['tipe'] == 'khusus')
                            @include('transaksi.laporan.laporan-khusus')
                        @elseif($_GET['tipe']=='pembayaran')
                            @include('transaksi.laporan.laporan-pembayaran')
                        @elseif($_GET['tipe']=='kamar-favorit')
                            @include('transaksi.laporan.kamar-favorit')
                            {{-- @elseif($_GET['tipe']=='tidak-terjual')
                    @include('penjualan.laporan.laporan-penjualan-menu-tidak-terjual') --}}
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
