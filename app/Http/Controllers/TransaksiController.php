<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Transaksi;
use \App\Kamar;
use \App\Pembayaran;
use \App\Tamu;
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
        $this->param['pageInfo'] = 'Reservasi';

        // $keyword = $request->get('keyword');
        $keywordKamar = $request->get('kamar');
        $keyTamu = $request->get('keyTamu');
        $status = $request->get('status');
        
        $kamar = \DB::table(\DB::raw('kamar k'))
                    ->select('k.id', 'k.no_kamar')
                    ->join(\DB::raw('transaksi t'), 'k.id', '=', 't.id_kamar')
                    ->where('t.status', '!=', 'Check Out')
                    ->get();
        
        $tamu = \DB::table(\DB::raw('tamu t'))
        ->select('t.id', 't.nama')
        ->join(\DB::raw('transaksi tk'), 't.id', '=', 'tk.id_tamu')
        ->where('tk.status', '!=', 'Check Out')
        ->get();

        $transaksi =Transaksi::with('kamar')->with('tamu')->where('status', '!=', 'Check Out');

        if($keyTamu){
            $transaksi->where('id_tamu', $keyTamu);
        }

        if ($keywordKamar) {
            $transaksi->where('id_kamar', "$keywordKamar");
        }
        
        if ($status) {
            $transaksi->where('status', $status);
        }

        return \view('transaksi.transaksi.list-transaksi', ['transaksi' => $transaksi->paginate(10), 'kamar' => $kamar, 'tamu' => $tamu], $this->param);
    }

    public function getKode()
    {
        $current_date = date('Y-m-d');
        $tgl = explode('-', $current_date);
        $y = $tgl[0];
        $m = $tgl[1];
        $year = date('y');
        $lastKode = Transaksi::select('kode_transaksi')
        ->whereMonth('waktu', $m)
        ->whereYear('waktu', $y)
        ->orderBy('kode_transaksi','desc')
        ->skip(0)->take(1)
        ->get();

        if(count($lastKode)==0){
            // $dateCreate = date_create($_GET['waktu']);
            $date = date('my');
            $kode = 'INV/BH/'.'0001'.'/'.$m.'/'.$year;
        }
        else{
            $ex = explode('/', $lastKode[0]->kode_transaksi);
            $no = (int)$ex[2] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = 'INV/BH/'.$newNo.'/'.$m.'/'.$year;
        }

        return $kode;
    }

    public function checkIn()
    {
        $this->param['pageInfo'] = 'Check In';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.index');
        $this->param['kode_transaksi'] = $this->getKode();
        $this->param['tamu'] = Tamu::select('id', 'nama')->get();
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
        $this->param['tamu'] = Tamu::select('id', 'nama')->get();
        $this->param['kamar'] = \DB::table('kamar')->where('status', 'Tersedia')->whereNotIn('id', function($query){
            $query->select('id_kamar')->from('transaksi')->whereIn('status', ['Check In', 'Booking']);
        })->orderBy('id','asc')->get();

        return \view('transaksi.transaksi.booking', $this->param);
    }

    public function reservasi()
    {
        $this->param['pageInfo'] = 'Reservasi';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.index');
        $this->param['kode_transaksi'] = $this->getKode();
        $this->param['tamu'] = Tamu::select('id', 'nama')->get();
        $this->param['kamar'] = Kamar::where('id',$_GET['id_kamar'])->get();
        $this->param['tgl_checkin'] = $_GET['tgl_checkin'];

        return \view('transaksi.transaksi.reservasi-by-chart', $this->param);
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
        $kode = str_replace('-', '/', $kode);
        $transaksi = Transaksi::find($kode);
        $transaksi->status = 'Check Out';
        $transaksi->save();

        return redirect()->route('transaksi.index');
    }

    public function checkInBooking($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $transaksi = Transaksi::find($kode);
        $transaksi->status = 'Check In';
        $transaksi->save();

        return redirect()->back()->withStatus('Berhasil Check In.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_tamu' => 'required',
            'tgl_checkin' => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
            'id_kamar' => 'required|numeric',
            'status' => 'required',
        ]);

        $newCheckIn = new Transaksi;

        $newCheckIn->kode_transaksi = $request->get('kode_transaksi');
        $newCheckIn->waktu = date('Y-m-d H:i:s');
        $newCheckIn->id_tamu = $request->get('id_tamu');
        $newCheckIn->tgl_checkin = $request->get('tgl_checkin');
        $newCheckIn->tgl_checkout = $request->get('tgl_checkout');
        $newCheckIn->id_kamar = $request->get('id_kamar');
        $newCheckIn->status = $request->get('status');
        $newCheckIn->keterangan = $request->get('keterangan');
        $newCheckIn->status_bayar = 'Belum';

        $newCheckIn->save();

        // $kamar = Kamar::find($request->get('id_kamar'));
        // $kamar->status = 'Tidak Tersedia';
        // $kamar->save();

        return redirect()->back()->withStatus('Data berhasil ditambahkan.');
    }

    public function edit($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $this->param['pageInfo'] = 'Edit Reservasi';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.index');
        $this->param['tamu'] = Tamu::select('id', 'nama')->get();
        $this->param['kamar'] = \DB::table('kamar')->get();

        $this->param['transaksi'] = Transaksi::findOrFail($kode);

        return view('transaksi.transaksi.edit-transaksi', $this->param);
    }

    public function update(Request $request, $kode)
    {
        $validatedData = $request->validate([
            'id_tamu' => 'required',
            'tgl_checkin' => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
            'id_kamar' => 'required|numeric',
        ]);
        $kode = str_replace('-', '/', $kode);
        $transaksi = Transaksi::findOrFail($kode);
        $transaksi->id_tamu = $request->get('id_tamu');
        $transaksi->id_kamar = $request->get('id_kamar');
        $transaksi->tgl_checkin = $request->get('tgl_checkin');
        $transaksi->tgl_checkout = $request->get('tgl_checkout');
        $transaksi->keterangan = $request->get('keterangan');

        $transaksi->save();

        return redirect()->route('transaksi.index')->withStatus('Data berhasil diperbarui.');
    }

    public function destroy($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $transaksi = Transaksi::findOrFail($kode);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->withStatus('Data berhasil dihapus.');
    }

    public function pembayaran($kode)
    {
        $kode = str_replace('-', '/', $kode);
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
        $newPembayaran->tax = $request->get('tax');
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
        $laporan = \DB::table(\DB::raw('transaksi t'))->select('t.kode_transaksi','t.waktu', 'tm.nama', 't.id_kamar', 't.tgl_checkin', 't.tgl_checkout', 'p.total', 'p.charge', 'p.diskon', 'p.tax', 'p.grandtotal', 'p.jenis_pembayaran','k.no_kamar')
        ->join(\DB::raw('pembayaran p'), 'p.kode_transaksi', '=', 't.kode_transaksi')
        ->join(\DB::raw('kamar k'), 'k.id', '=', 't.id_kamar')
        ->join(\DB::raw('tamu tm'), 'tm.id', '=', 't.id_tamu')
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

    public function listInvoice(Request $request)
    {
        $this->param['pageInfo'] = 'INVOICE';

        $keyTamu = $request->get('keyTamu');
        $keywordKamar = $request->get('kamar');
        $status = $request->get('status');
        
        $kamar = \DB::table(\DB::raw('kamar k'))
                    ->select('k.id', 'k.no_kamar')
                    ->join(\DB::raw('transaksi t'), 'k.id', '=', 't.id_kamar')
                    ->where('t.status', '!=', 'Check Out')
                    ->get();

        $tamu = \DB::table(\DB::raw('tamu t'))
                    ->select('t.id', 't.nama')
                    ->join(\DB::raw('transaksi tk'), 't.id', '=', 'tk.id_tamu')
                    ->where('tk.status', '!=', 'Check Out')
                    ->get();

        $transaksi =Transaksi::with('kamar')->with('tamu')->where('status_bayar', '!=', 'Sudah');

        if($keyTamu){
            $transaksi->where('id_tamu', $keyTamu);
        }

        if ($keywordKamar) {
            $transaksi->where('id_kamar', "$keywordKamar");
        }
        
        if ($status) {
            $transaksi->where('status', $status);
        }

        return \view('transaksi.invoice.list-invoice', ['transaksi' => $transaksi->paginate(10), 'kamar' => $kamar, 'tamu' => $tamu], $this->param);
    }

    public function paid($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $transaksi = Transaksi::find($kode);
        $transaksi->status_bayar = 'Sudah';
        $transaksi->save();

        return redirect()->route('transaksi.list-invoice')->withStatus('Data berhasil disimpan.');
    }

    public function editInvoice($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $this->param['pageInfo'] = 'Edit Invoice';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.list-invoice');

        $this->param['transaksi'] = Transaksi::with('kamar')->findOrFail($kode);

        return view('transaksi.invoice.edit-invoice', $this->param);
    }

    public function saveInvoice(Request $request)
    {
        $validatedData = $request->validate([
            // 'bayar' => 'required|numeric|gte:grandtotal',
            'jenis_pembayaran' => 'required'
        ]);

        $cekInv = Pembayaran::where('kode_transaksi', $request->get('kode_transaksi'))->count();

        if ($cekInv > 0) {
            Pembayaran::where('kode_transaksi', $request->get('kode_transaksi'))->update([
                'waktu' => date('Y-m-d H:i:s'),
                'jenis_pembayaran' => $request->get('jenis_pembayaran'),
                'total' => $request->get('total'),
                'diskon' => $request->get('diskon'),
                'tax' => $request->get('tax'),
                'charge' => $request->get('charge'),
                'grandtotal' => $request->get('grandtotal'),
            ]);
        }
        else{
            $newPembayaran = new Pembayaran;
            $newPembayaran->kode_transaksi = $request->get('kode_transaksi');
            $newPembayaran->waktu = date('Y-m-d H:i:s');
            $newPembayaran->jenis_pembayaran = $request->get('jenis_pembayaran');
            $newPembayaran->total = $request->get('total');
            $newPembayaran->diskon = $request->get('diskon');
            $newPembayaran->tax = $request->get('tax');
            $newPembayaran->charge = $request->get('charge');
            $newPembayaran->grandtotal = $request->get('grandtotal');
            $newPembayaran->bayar = 0;
    
            $newPembayaran->save();
        }

        return redirect()->route('transaksi.list-invoice')->withStatus('Data berhasil disimpan.');
    }

    public function invoice($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $this->param['transaksi'] = Transaksi::with('tamu')->find($kode);
        return view('transaksi.invoice.invoice', $this->param);
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
