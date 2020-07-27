<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Transaksi;
use \App\Kamar;
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
        $transaksi =Transaksi::where('status', '!=', 'Check Out');

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
        $this->param['kamar'] = \DB::table('kamar')->whereNotIn('id', function($query){
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
        $this->param['kamar'] = \DB::table('kamar')->whereNotIn('id', function($query){
            $query->select('id_kamar')->from('transaksi')->whereIn('status', ['Check In', 'Booking']);
        })->orderBy('id','asc')->get();

        return \view('transaksi.transaksi.booking', $this->param);
    }

    public function getKamarTersedia($tgl_checkin, $tgl_checkout)
    {
        
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_tamu' => 'required',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'tgl_checkin' => 'required|date',
            'tgl_checkout' => 'required|date',
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

        return redirect()->back()->withStatus('Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
