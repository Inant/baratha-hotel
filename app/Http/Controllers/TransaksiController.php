<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Transaksi;
use \App\Detail_transaksi;
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
        
/*         $kamar = \DB::table(\DB::raw('kamar k'))
                    ->select('k.id', 'k.no_kamar')
                    ->join(\DB::raw('transaksi t'), 'k.id', '=', 't.id_kamar')
                    ->where('t.status', '!=', 'Check Out')
                    ->get();
 */        
        $tamu = \DB::table(\DB::raw('tamu t'))
        ->select('t.id', 't.nama')
        ->join(\DB::raw('transaksi tk'), 't.id', '=', 'tk.id_tamu')
        ->where('tk.status', '!=', 'Check Out')
        ->get();

        $transaksi = Transaksi::select('kode_transaksi','tgl_checkin','tgl_checkout','status','keterangan','nama','tipe_pemesanan')->join('tamu','transaksi.id_tamu','tamu.id')->where('status','!=','Check Out');

        if($keyTamu){
            $transaksi->where('id_tamu', $keyTamu);
        }

/*         if ($keywordKamar) {
            $transaksi->where('id_kamar', "$keywordKamar");
        }
 */        
        if ($status) {
            $transaksi->where('status', $status);
        }

        return \view('transaksi.transaksi.list-transaksi', ['transaksi' => $transaksi->paginate(10), 'kamar' => /*$kamar */[], 'tamu' => $tamu], $this->param);
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
            $query->select('d.id_kamar')->from('detail_transaksi as d')->join('transaksi as t','t.kode_transaksi','d.kode_transaksi')->whereIn('t.status', ['Check In', 'Booking']);
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
            $query->select('d.id_kamar')->from('detail_transaksi as d')->join('transaksi as t','t.kode_transaksi','d.kode_transaksi')->whereIn('t.status', ['Check In', 'Booking']);
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
                        ->where('status','Tersedia')
                        ->whereNotIn('id', function($query) use ($tgl_checkin, $tgl_checkout){
                            $query->select('d.id_kamar')->from('detail_transaksi as d')
                            ->join('transaksi as t','t.kode_transaksi','d.kode_transaksi')
                            ->where('t.tgl_checkin', '>=', $tgl_checkin)
                            ->where('t.tgl_checkin', '<=', $tgl_checkout)
                            // ->whereBetween('t.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                            // ->orWhereBetween('t.tgl_checkout', [$tgl_checkin, $tgl_checkout])
                            ->where('t.tgl_checkout', '>=', $tgl_checkin)
                            ->where('t.tgl_checkout', '<=', $tgl_checkout)
                            ->where('t.status', '!=', 'Check Out');
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

        return redirect()->route('transaksi.list-invoice')->withStatus('Berhasil Check Out.');
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
            'status' => 'required',
            'tipe_pemesanan' => 'required',
        ]);

        $newCheckIn = new Transaksi;

        $newCheckIn->kode_transaksi = $request->get('kode_transaksi');
        $newCheckIn->waktu = date('Y-m-d H:i:s');
        $newCheckIn->id_tamu = $request->get('id_tamu');
        $newCheckIn->tgl_checkin = $request->get('tgl_checkin');
        $newCheckIn->tgl_checkout = $request->get('tgl_checkout');
        $newCheckIn->status = $request->get('status');
        $newCheckIn->keterangan = $request->get('keterangan');
        $newCheckIn->status_bayar = 'Belum';
        $newCheckIn->tipe_pemesanan = $request->get('tipe_pemesanan');
//        $newCheckIn->id_kamar = $request->get('id_kamar');

        $newCheckIn->save();

        foreach ($_POST['id_kamar'] as $value) {
            $detail = new Detail_transaksi;
            $detail->kode_transaksi = $request->get('kode_transaksi');
            $detail->id_kamar = $value;

            $detail->save();
        }



        // $kamar = Kamar::find($request->get('id_kamar'));
        // $kamar->status = 'Tidak Tersedia';
        // $kamar->save();

        return redirect()->route('transaksi.list-invoice')->withStatus('Data berhasil ditambahkan.');
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
        $this->param['selectedRoom'] = \App\Detail_transaksi::select('id_kamar')->where('kode_transaksi', $kode)->get()->toArray();

        return view('transaksi.transaksi.edit-transaksi', $this->param);
    }

    public function update(Request $request, $kode)
    {
        $validatedData = $request->validate([
            'id_tamu' => 'required',
            'tipe_pemesanan' => 'required',
            'tgl_checkin' => 'required|date',
            'tgl_checkout' => 'required|date|after:tgl_checkin',
            'id_kamar' => 'required',
        ]);
        $kode = str_replace('-', '/', $kode);

        DB::table('detail_transaksi')->where('kode_transaksi', $kode)->delete();

        foreach ($_POST['id_kamar'] as $value) {
            $detail = new Detail_transaksi;
            $detail->kode_transaksi = $kode;
            $detail->id_kamar = $value;

            $detail->save();
        }

        $transaksi = Transaksi::findOrFail($kode);

        $transaksi->id_tamu = $request->get('id_tamu');
        // $transaksi->id_kamar = $request->get('id_kamar');
        $transaksi->tgl_checkin = $request->get('tgl_checkin');
        $transaksi->tgl_checkout = $request->get('tgl_checkout');
        $transaksi->tipe_pemesanan = $request->get('tipe_pemesanan');
        $transaksi->keterangan = $request->get('keterangan');
//        $newCheckIn->id_kamar = $request->get('id_kamar');

        $transaksi->save();

        

        $transaksi->save();

        return redirect()->route('transaksi.index')->withStatus('Data berhasil diperbarui.');
    }

    public function destroy($kode)
    {
        $kode = str_replace('-', '/', $kode);
        Pembayaran::where('kode_transaksi', $kode)->delete();
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
        $newPembayaran->no_kartu = $request->get('no_kartu');
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

    public function getLaporanGeneral($dari, $sampai, $tipe, $tipe_pembayaran='')
    {
        $laporan = \DB::table(\DB::raw('transaksi t'))->select('t.kode_transaksi','p.waktu', 'tm.nama', 't.tgl_checkin', 't.tgl_checkout', 'p.total', 'p.charge', 'p.diskon', 'p.tax', 'p.grandtotal', 'p.jenis_pembayaran', 'p.no_kartu', 't.tipe_pemesanan')
        ->join(\DB::raw('pembayaran p'), 'p.kode_transaksi', '=', 't.kode_transaksi')
        ->join(\DB::raw('tamu tm'), 'tm.id', '=', 't.id_tamu')
        ->whereBetween('p.waktu', ["$dari 00:00:00", "$sampai 23:59:59"])
        ->whereNull('deleted_at')
        ->where('t.status_bayar', 'Sudah')
        ->orWhere('t.status_bayar', 'Piutang Terbayar');
        if ($tipe_pembayaran) {
            $laporan->where('p.jenis_pembayaran', 'LIKE', "%$tipe_pembayaran");
        }
        if ($_GET['tipe_pemesanan'] != '') {
            $laporan->where('t.tipe_pemesanan', '=', $_GET['tipe_pemesanan']);
        }

        return $laporan->get();
    }
    
    public function getLaporanKhusus($dari, $sampai, $tipe='')
    {
        if(auth()->user()->level == 'Owner') {
            $laporan = \DB::table(\DB::raw('transaksi t'))->select('t.kode_transaksi','p.waktu', 'tm.nama', 't.tgl_checkin', 't.tgl_checkout', 'p.total', 'p.charge', 'p.diskon', 'p.tax', 'p.grandtotal', 'p.jenis_pembayaran', 'p.no_kartu','t.tipe_pemesanan')
            ->join(\DB::raw('pembayaran p'), 'p.kode_transaksi', '=', 't.kode_transaksi')
            ->join(\DB::raw('tamu tm'), 'tm.id', '=', 't.id_tamu')
            ->whereBetween('p.waktu', ["$dari 00:00:00", "$sampai 23:59:59"])
            ->where('t.status_bayar', 'Sudah')
            ->orWhere('t.status_bayar', 'Piutang Terbayar');
            if ($_GET['tipe_pembayaran']) {
                $laporan->where('p.jenis_pembayaran', 'LIKE', "%$_GET[tipe_pembayaran]");
            }
            if ($_GET['tipe_pemesanan'] != '') {
                $laporan->where('t.tipe_pemesanan', '=', $_GET['tipe_pemesanan']);
            }
            
            return $laporan->get();
        }
        else {
            return back()->withError('Maaf hanya owner yang dapat mengakses fitur ini.');
        }
    }

    public function getLaporanPembayaran($dari, $sampai, $tipe='')
    {
        $laporan = \DB::table(\DB::raw('transaksi t'))->select('t.kode_transaksi','p.waktu', 'tm.nama', 't.tgl_checkin', 't.tgl_checkout', 'p.total', 'p.charge', 'p.diskon', 'p.tax', 'p.grandtotal', 'p.jenis_pembayaran', 'p.no_kartu','t.tipe_pemesanan')
        ->join(\DB::raw('pembayaran p'), 'p.kode_transaksi', '=', 't.kode_transaksi')
        ->join(\DB::raw('tamu tm'), 'tm.id', '=', 't.id_tamu')
        ->whereBetween('p.waktu', ["$dari 00:00:00", "$sampai 23:59:59"])
        ->where('t.status_bayar', 'Sudah')
        ->orWhere('t.status_bayar', 'Piutang Terbayar');
        if ($_GET['tipe_pembayaran']) {
            $laporan->where('p.jenis_pembayaran', 'LIKE', "%$_GET[tipe_pembayaran]");
        }
        if ($_GET['tipe_pemesanan'] != '') {
            $laporan->where('t.tipe_pemesanan', '=', $_GET['tipe_pemesanan']);
        }
        $laporan->whereNull('deleted_at');
        
        return $laporan->get();
    }

    public function getKamarFavorit($dari, $sampai)
    {
        if($dari && $sampai){
            $laporan = \DB::table(\DB::raw('transaksi t'))
                        ->select('k.no_kamar', \DB::raw('COUNT(d.id_kamar) as jml'))
                        ->join(\DB::raw('detail_transaksi d'), 'd.kode_transaksi', '=', 't.kode_transaksi')
                        ->join(\DB::raw('kamar k'), 'k.id', '=', 'd.id_kamar')
                        ->whereBetween('waktu', ["$dari 00:00:00", "$sampai 23:59:59"])
                        ->groupBy('d.id_kamar')
                        ->orderBy(\DB::raw('jml'), 'desc');
            if(auth()->user()->level == 'Resepsionis') 
                $laporan->whereNull('t.deleted_at');
        }
        return $laporan->get();
    }

    public function listInvoice(Request $request)
    {
        $this->param['pageInfo'] = 'INVOICE';

        $keyTamu = $request->get('keyTamu');
        $keywordKamar = $request->get('kamar');
        $status = $request->get('status');
        
        $kamar = \DB::table(\DB::raw('kamar k'))
                    ->select('k.id', 'k.no_kamar')
                    ->join(\DB::raw('detail_transaksi dt'), 'k.id', '=', 'dt.id_kamar')
                    ->join(\DB::raw('transaksi t'), 'dt.kode_transaksi', '=', 't.kode_transaksi')
                    ->where('t.status_bayar', '=', 'Belum')
                    ->distinct()
                    ->get();

        $tamu = \DB::table(\DB::raw('tamu t'))
                    ->select('t.id', 't.nama')
                    ->join(\DB::raw('transaksi tk'), 't.id', '=', 'tk.id_tamu')
                    ->where('tk.status_bayar', '=', 'Belum')
                    ->distinct()
                    ->get();

        $transaksi =Transaksi::with('tamu');

        if($keyTamu){
            $transaksi->where('id_tamu', $keyTamu);
        }
        if ($status) {
            $transaksi->where('status', $status);
        }

        $transaksi->where('status_bayar', '=', 'Belum');
        if ($keywordKamar) {
            $transaksi->where('dt.id_kamar', "$keywordKamar")
            ->join('detail_transaksi as dt','transaksi.kode_transaksi','dt.kode_transaksi')
            ->groupBy('transaksi.kode_transaksi');
        }
        

        return \view('transaksi.invoice.list-invoice', ['transaksi' => $transaksi->paginate(10), 'kamar' => $kamar, 'tamu' => $tamu], $this->param);
    }

    public function paid($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $transaksi = Transaksi::find($kode);
        $transaksi->status_bayar = 'Sudah';
        $transaksi->save();
        
        $status = Transaksi::select('status_bayar')->where('kode_transaksi',$kode)->get();
        if($status[0]->status_bayar=='DP50%'){
            $getGrandTotal = Pembayaran::select('grandtotal')->where('kode_transaksi',$kode)->get();
            Pembayaran::where('kode_transaksi',$kode)->update(['bayar' => $getGrandTotal[0]->grandtotal]);
        }

        return redirect()->route('transaksi.list-invoice')->withStatus('Data berhasil disimpan.');
    }
    public function listPiutang()
    {
        $this->param['pageInfo'] = 'List Piutang';

        $transaksi =Transaksi::with('tamu')->where('status_bayar', '=', 'Piutang');
        return \view('transaksi.invoice.list-piutang', ['transaksi' => $transaksi->paginate(10)], $this->param);

    }
    public function addPiutang($kode)
    {
        $kode = str_replace('-', '/', $kode);

        Transaksi::where('kode_transaksi',$kode)->update(['status_bayar' => 'Piutang']);
        return redirect()->route('transaksi.list-invoice')->withStatus('Piutang Berhasil Ditambahkan.');
    }

    public function editInvoice($kode)
    {
        $kode = str_replace('-', '/', $kode);
        $this->param['pageInfo'] = 'Edit Invoice';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('transaksi.list-invoice');

        $this->param['transaksi'] = Transaksi::findOrFail($kode);
        $pembayaran = Pembayaran::where('kode_transaksi',$kode)->get();
        $this->param['pembayaran'] = count($pembayaran) > 0 ? $pembayaran[0] : '';

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
            // if(empty($request->get('tax'))){
            //     //DP50%
            //     $transaksi = Pembayaran::select('total')->where('kode_transaksi',$request->get('kode_transaksi'))->get()[0];
            //     $grandtotal = $transaksi->grandtotal - $request->get('diskon') + $request->get('charge');
            //     $arr = array(
            //         'waktu' => date('Y-m-d H:i:s'),
            //         'jenis_pembayaran' => $request->get('jenis_pembayaran'),
            //         'diskon' => $request->get('diskon'),
            //         'charge' => $request->get('charge'),
            //         'grandtotal' => $grandtotal,
            //         'bayar' => $grandtotal,
            //     );
            // }
            // else{
            //     $arr = array(
            //         'waktu' => date('Y-m-d H:i:s'),
            //         'jenis_pembayaran' => $request->get('jenis_pembayaran'),
            //         'total' => $request->get('total'),
            //         'diskon' => $request->get('diskon'),
            //         'tax' => $request->get('tax'),
            //         'charge' => $request->get('charge'),
            //         'grandtotal' => $request->get('grandtotal'),
            //     );
            // }
            $arr = array(
                'waktu' => date('Y-m-d H:i:s'),
                'jenis_pembayaran' => $request->get('jenis_pembayaran'),
                'no_kartu' => $request->get('no_kartu'),
                'total' => $request->get('total'),
                'diskon' => $request->get('diskon'),
                'tax' => $request->get('tax'),
                'charge' => $request->get('charge'),
                'grandtotal' => $request->get('grandtotal'),
            );
            Pembayaran::where('kode_transaksi', $request->get('kode_transaksi'))->update($arr);
        }
        else{
            $newPembayaran = new Pembayaran;
            $newPembayaran->kode_transaksi = $request->get('kode_transaksi');
            $newPembayaran->waktu = date('Y-m-d H:i:s');
            $newPembayaran->jenis_pembayaran = $request->get('jenis_pembayaran');
            $newPembayaran->no_kartu = $request->get('no_kartu');
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
        // return $this->param;
    }

    public function online()
    {
        $data = Transaksi::select('kode_transaksi','tgl_checkin','tgl_checkout','status','keterangan','nama')->join('tamu','transaksi.id_tamu','tamu.id')->where('status_bayar','Belum')->where('tipe_pemesanan','Online')->paginate(10);
        return view('transaksi.transaksi.list-pemesanan-online')->with('data', $data)->with('pageInfo', 'Pemesanan Online')->with('icon','ni-world-2 text-pink');
    }
    public function listPembayaranOnline()
    {
        $data = \DB::table('pembayaran as p')->select('p.kode_transaksi','p.jenis_pembayaran','p.bukti')->join('transaksi as t','p.kode_transaksi','t.kode_transaksi')->where('t.status_bayar','Menunggu Verifikasi')->paginate(10);
        return view('transaksi.pembayaran-online.index')->with('data', $data)->with('pageInfo', 'Pembayaran Online')->with('icon','ni-credit-card text-yellow');
    }

    public function searchPembayaranOnline(Request $req)
    {
        $data = Pembayaran::where('kode_transaksi','like','%'.$req->get('keyword').'%')
                        ->whereNotNull('bukti')->get();
        return view('transaksi.pembayaran-online.index')->with('data', $data)->with('pageInfo', 'Pembayaran Online')->with('icon','ni-credit-card text-orange');
    }

    public function detailPembayaran($kode)
    {
        $kode = str_replace('-','/',$kode);
        $data = Pembayaran::where('kode_transaksi', $kode)->get();
        return view('transaksi.pembayaran.detail-pembayaran')->with('data', $data)->with('pageInfo', 'Detail Pembayaran Online')->with('icon','ni-credit-card text-orange');
    }

    public function updatePembayaranOnline()
    {
        $kode = $_GET['kode'];
        $kode = str_replace('-', '/', $kode);
        $email = \DB::table('transaksi as t')->select('ta.email')->join('tamu as ta','t.id_tamu','ta.id')->where('t.kode_transaksi',$kode)->get()[0];
        if($_GET['act']=='acc'){
            DB::table('transaksi')->where('kode_transaksi', $kode)->update([
                'status' => 'Check In',
                'status_bayar' => 'DP50%',
                'updated_at' => date('y-m-d H:i:s')
            ]);
            $tipe = 'Diterima';  
        }
        else{
            $tipe = 'Ditolak';
            DB::table('transaksi')->where('kode_transaksi', $kode)->update([
                'status_bayar' => 'Belum',
                'updated_at' => date('y-m-d H:i:s')
            ]);
            DB::table('pembayaran')->where('kode_transaksi', $kode)->delete();
        }
        \Mail::to($email->email)->send(new \App\Mail\VerifikasiMail($kode,$tipe));
        return redirect()->route('transaksi.list-pembayaran-online')->withStatus('Verifikasi Berhasil');
    }

    public function laporan()
    {
        $this->param['pageInfo'] = 'Laporan';
        // $this->param['btnRight']['text'] = 'Tambah Penjualan';
        // $this->param['btnRight']['link'] = route('penjualan.create');
        $tipe = $_GET['tipe'];
        if(isset($_GET['dari']) && isset($_GET['sampai'])){
            if($tipe=='general'){
                $this->param['laporan'] = $this->getLaporanGeneral($_GET['dari'], $_GET['sampai'], $_GET['tipe_pembayaran']);
            }
            else if($tipe=='khusus'){
                if(auth()->user()->level == 'Owner') {   
                    $this->param['laporan'] = $this->getLaporanKhusus($_GET['dari'], $_GET['sampai'],$_GET['tipe_pembayaran']);
                }
                else {
                    return back()->withError('Maaf hanya owner yang dapat mengakses fitur ini.');
                }
            }
            else if($tipe=='pembayaran'){
                $this->param['laporan'] = $this->getLaporanPembayaran($_GET['dari'], $_GET['sampai']);
            }
            else if($tipe=='kamar-favorit'){
                $this->param['laporan'] = $this->getKamarFavorit($_GET['dari'], $_GET['sampai']);
            }
        }
        else {
            if($tipe=='khusus'){
                if(auth()->user()->level != 'Owner') {   
                    return back()->withError('Maaf hanya owner yang dapat mengakses fitur ini.');
                }
            }
        }
        return view('transaksi.laporan.laporan', $this->param);
    }

    public function printLaporan(){
        if(isset($_GET['dari']) && isset($_GET['sampai'])){
            $type = $_GET['tipe'];
            if($type=='general'){
                $this->param['laporan'] = $this->getLaporanGeneral($_GET['dari'], $_GET['sampai'], $_GET['tipe_pembayaran']);
            }
            else if($type=='khusus'){
                $this->param['laporan'] = $this->getLaporanKhusus($_GET['dari'], $_GET['sampai'], $_GET['tipe_pembayaran']);
            }
            else if($_GET['tipe']=='pembayaran'){
                $this->param['laporan'] = $this->getLaporanPembayaran($_GET['dari'], $_GET['sampai']);
            }
            else if($_GET['tipe']=='kamar-favorit'){
                $this->param['laporan'] = $this->getKamarFavorit($_GET['dari'], $_GET['sampai']);
            }
        }
        return view('transaksi.laporan.print-laporan-'.$_GET['tipe'], $this->param);
    }

    public function allPenjualan(Request $request)
    {
        $this->param['pageInfo'] = 'List Penjualan';

        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        
        $this->param['laporan'] = \DB::table(\DB::raw('transaksi t'))->select('t.kode_transaksi','p.waktu', 'tm.nama', 't.tgl_checkin', 't.tgl_checkout', 'p.total', 'p.charge', 'p.diskon', 'p.tax', 'p.grandtotal', 'p.jenis_pembayaran', 't.tipe_pemesanan', 't.deleted_at','t.status_bayar')
        ->join(\DB::raw('pembayaran p'), 'p.kode_transaksi', '=', 't.kode_transaksi')
        ->join(\DB::raw('tamu tm'), 'tm.id', '=', 't.id_tamu')
        ->whereBetween('p.waktu', ["$dari 00:00:00", "$sampai 23:59:59"])
        // ->where('t.status_bayar', 'Sudah')
        ->get();        

        return view('transaksi.transaksi.list-penjualan', $this->param);
    }

    public function softDelete($kode)
    {
        try{
            if(auth()->user()->level == 'Owner') {
                $kode = str_replace('-', '/', $kode);
                \DB::table('transaksi')->where('kode_transaksi', $kode)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

                return back()->withStatus('Data berhasil dihapus.');
            }
            else {
                return back()->withError('Maaf hanya owner yang dapat mengakses fitur ini.');
            }
        }
        catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function restoreData($kode)
    {
        try{
            if(auth()->user()->level == 'Owner') {
                $kode = str_replace('-', '/', $kode);
                \DB::table('transaksi')->where('kode_transaksi', $kode)->update([
                    'deleted_at' => NULL
                ]);

                return back()->withStatus('Data berhasil dikembalikan.');
            }
            else {
                return back()->withError('Maaf hanya owner yang dapat mengakses fitur ini.');
            }
        }
        catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError($e->getMessage());
        }
    }
}
