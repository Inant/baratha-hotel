<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Transaksi;
use \App\Kamar;
use \App\Pembayaran;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{   
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-credit-card text-orange';
    }

    public function index(Request $request)
    {
        $this->param['pageInfo'] = 'Transaksi';

        $keyword = $request->get('keyword');
        $keywordKamar = $request->get('kamar');
        $status = $request->get('status');
        
        $kamar = \DB::table(\DB::raw('kamar k'))
                    ->select('k.id', 'k.no_kamar')
                    ->join(\DB::raw('transaksi t'), 'k.id', '=', 't.id_kamar')
                    ->where('t.status', '!=', 'Check Out')
                    ->get();
        $transaksi =Transaksi::with('kamar')->where('status', '!=', 'Check Out');

        if($keyword){
            $transaksi->where('nama_tamu', 'LIKE',"%$keyword%");
        }

        if ($keywordKamar) {
            $transaksi->where('id_kamar', "$keywordKamar");
        }
        
        if ($status) {
            $transaksi->where('status', $status);
        }

        return \view('transaksi.transaksi.list-transaksi', ['transaksi' => $transaksi->paginate(10), 'kamar' => $kamar], $this->param);
    }

    public function getKode()
    {
        $current_date = date('Y-m-d');
        $tgl = explode('-', $current_date);
        $y = $tgl[0];
        $m = $tgl[1];
        $lastKode = Transaksi::select('kode_transaksi')
        ->whereMonth('waktu', $m)
        ->whereYear('waktu', $y)
        ->orderBy('kode_transaksi','desc')
        ->skip(0)->take(1)
        ->get();

        if(count($lastKode)==0){
            // $dateCreate = date_create($_GET['waktu']);
            $date = date('my');
            $kode = "INV".$date."-0001";
        }
        else{
            $ex = explode('-', $lastKode[0]->kode_transaksi);
            $no = (int)$ex[1] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = $ex[0].'-'.$newNo;
        }

        return $kode;
    }

    public function checkIn()
    {
        $this->param['pageInfo'] = 'Check In';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.index');
        $this->param['kode_transaksi'] = $this->getKode();
        $this->param['kamar'] = \DB::table('kamar')->where('status', 'Tersedia')->whereNotIn('id', function($query){
            $query->select('id_kamar')->from('transaksi')->whereIn('status', ['Check In', 'Booking']);
        })->orderBy('id','asc')->get();

        return \view('transaksi.transaksi.check-in', $this->param);
    }

    public function booking()
    {
        $this->param['pageInfo'] = 'Booking';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.index');
        $this->param['kode_transaksi'] = $this->getKode();
        $this->param['kamar'] = \DB::table('kamar')->where('status', 'Tersedia')->whereNotIn('id', function($query){
            $query->select('id_kamar')->from('transaksi')->whereIn('status', ['Check In', 'Booking']);
        })->orderBy('id','asc')->get();

        return \view('transaksi.transaksi.booking', $this->param);
    }

    public function getKamarTersedia()
    {
        $tgl_checkin = $_GET['tgl_checkin'];
        $tgl_checkout = $_GET['tgl_checkout'];
        $kamar = \DB::table('kamar AS k')
                        ->select('k.id', 'k.no_kamar')
                        ->whereNotIn('id', function($query) use ($tgl_checkin, $tgl_checkout){
                            $query->select('id_kamar')->from('transaksi')
                            ->whereBetween('tgl_checkin', [$tgl_checkin, $tgl_checkout])
                            ->orWhereBetween('tgl_checkout', [$tgl_checkin, $tgl_checkout])
                            ->where('status', '!=', 'Check Out');
                        })
                        ->orderBy('id', 'asc')
                        ->get();
        return json_encode($kamar);
    }

    public function checkOut($kode)
    {
        $transaksi = Transaksi::find($kode);
        $transaksi->status = 'Check Out';
        $transaksi->save();

        return redirect()->route('transaksi.pembayaran', ['kode' => $kode]);
    }

    public function checkInBooking($kode)
    {
        $transaksi = Transaksi::find($kode);
        $transaksi->status = 'Check In';
        $transaksi->save();

        return redirect()->back()->withStatus('Berhasil Check In.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_tamu' => 'required',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'tgl_checkin' => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
            'id_kamar' => 'required|numeric',
        ]);

        $newCheckIn = new Transaksi;

        $newCheckIn->kode_transaksi = $request->get('kode_transaksi');
        $newCheckIn->waktu = date('Y-m-d H:i:s');
        $newCheckIn->nama_tamu = $request->get('nama_tamu');
        $newCheckIn->jenis_identitas = $request->get('jenis_identitas');
        $newCheckIn->no_identitas = $request->get('no_identitas');
        $newCheckIn->tgl_checkin = $request->get('tgl_checkin');
        $newCheckIn->tgl_checkout = $request->get('tgl_checkout');
        $newCheckIn->id_kamar = $request->get('id_kamar');
        $newCheckIn->status = $request->get('status');
        $newCheckIn->status_bayar = 'Belum';

        $newCheckIn->save();

        $kamar = Kamar::find($request->get('id_kamar'));
        $kamar->status = 'Tidak Tersedia';
        $kamar->save();

        return redirect()->back()->withStatus('Data berhasil ditambahkan.');
    }

    public function edit($kode)
    {
        $this->param['pageInfo'] = 'Edit Transaksi';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.index');
        $this->param['kamar'] = \DB::table('kamar')->get();

        $this->param['transaksi'] = Transaksi::findOrFail($kode);

        return view('transaksi.transaksi.edit-transaksi', $this->param);
    }

    public function update(Request $request, $kode)
    {
        $validatedData = $request->validate([
            'nama_tamu' => 'required',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'tgl_checkin' => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
            'id_kamar' => 'required|numeric',
        ]);

        $transaksi = Transaksi::findOrFail($kode);
        $transaksi->nama_tamu = $request->get('nama_tamu');
        $transaksi->jenis_identitas = $request->get('jenis_identitas');
        $transaksi->no_identitas = $request->get('no_identitas');
        $transaksi->id_kamar = $request->get('id_kamar');
        $transaksi->tgl_checkin = $request->get('tgl_checkin');
        $transaksi->tgl_checkout = $request->get('tgl_checkout');

        $transaksi->save();

        return redirect()->route('transaksi.index')->withStatus('Data berhasil diperbarui.');
    }

    public function destroy($kode)
    {
        $transaksi = Transaksi::findOrFail($kode);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->withStatus('Data berhasil dihapus.');
    }

    public function pembayaran($kode)
    {
        $this->param['pageInfo'] = 'Pembayaran';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.index');

        $this->param['transaksi'] = Transaksi::with('kamar')->findOrFail($kode);

        return view('transaksi.pembayaran.pembayaran', $this->param);
    }

    public function savePembayaran(Request $request)
    {
        $validatedData = $request->validate([
            'bayar' => 'required|numeric|gte:grandtotal',
            'jenis_pembayaran' => 'required'
        ]);

        $newPembayaran = new Pembayaran;
        $newPembayaran->kode_transaksi = $request->get('kode_transaksi');
        $newPembayaran->waktu = date('Y-m-d H:i:s');
        $newPembayaran->jenis_pembayaran = $request->get('jenis_pembayaran');
        $newPembayaran->total = $request->get('total');
        $newPembayaran->diskon = $request->get('diskon');
        $newPembayaran->tax = 0;
        $newPembayaran->charge = $request->get('charge');
        $newPembayaran->grandtotal = $request->get('grandtotal');
        $newPembayaran->bayar = $request->get('bayar');

        $newPembayaran->save();

        $transaksi = Transaksi::find($request->get('kode_transaksi'));
        $transaksi->status_bayar = 'Sudah';
        $transaksi->save();

        return redirect()->route('transaksi.index')->withStatus('Data berhasil disimpan.');
    }

    public function getLaporanGeneral($dari, $sampai, $tipe='')
    {
        $laporan = \DB::table(\DB::raw('transaksi t'))->select('t.kode_transaksi','t.waktu', 't.nama_tamu', 't.id_kamar', 't.tgl_checkin', 't.tgl_checkout', 'p.total', 'p.charge', 'p.diskon', 'p.tax', 'p.grandtotal', 'p.jenis_pembayaran','k.no_kamar')
        ->join(\DB::raw('pembayaran p'), 'p.kode_transaksi', '=', 't.kode_transaksi')
        ->join(\DB::raw('kamar k'), 'k.id', '=', 't.id_kamar')
        ->whereBetween('t.waktu', ["$dari 00:00:00", "$sampai 23:59:59"])
        ->where('t.status_bayar', 'Sudah');
        if ($tipe) {
            $laporan->where('p.jenis_pembayaran', 'LIKE', "%$tipe");
        }
        return $laporan->get();
    }

    public function getKamarFavorit($dari, $sampai)
    {
        if($dari && $sampai){
            $laporan = \DB::table(\DB::raw('transaksi t'))
                        ->select('k.no_kamar', \DB::raw('COUNT(t.id_kamar) as jml'))
                        ->join(\DB::raw('kamar k'), 'k.id', '=', 't.id_kamar')
                        ->whereBetween('waktu', ["$dari 00:00:00", "$sampai 23:59:59"])
                        ->groupBy('t.id_kamar')
                        ->orderBy(\DB::raw('jml'))
                        ->get();
        }
        return $laporan;
    }

    public function laporan()
    {
        $this->param['pageInfo'] = 'Laporan';
        // $this->param['btnRight']['text'] = 'Tambah Penjualan';
        // $this->param['btnRight']['link'] = route('penjualan.create');
        if(isset($_GET['dari']) && isset($_GET['sampai'])){
            if($_GET['tipe']=='general'){
                $this->param['laporan'] = $this->getLaporanGeneral($_GET['dari'], $_GET['sampai'], $_GET['tipe_pembayaran']);
            }
            else if($_GET['tipe']=='kamar-favorit'){
                $this->param['laporan'] = $this->getKamarFavorit($_GET['dari'], $_GET['sampai']);
            }
        }
        return view('transaksi.laporan.laporan', $this->param);
    }
}
