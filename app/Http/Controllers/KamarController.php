<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Kamar;
use \App\KategoriKamar;

class KamarController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-building text-info';
    }
    
    public function index(Request $request)
    {
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('kamar.create');

        $keyword = $request->get('keyword');
//        $keywordKategori = $request->get('kategori-kamar');
        
        $kategori = KategoriKamar::orderBy('kategori_kamar','asc')->get();
        $kamar = Kamar::with('kategori');
        
        if(isset($_GET['id_kategori'])){
            $kamar->where('id_kategori_kamar', $_GET['id_kategori']);
        }

        if ($keyword) {
            $kamar->where('no_kamar', 'LIKE', "%$keyword%");
        }
        
        return \view('master-kamar.kamar.list-kamar', ['kamar' => $kamar->get(), 'kategori' => $kategori], $this->param);
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Kamar';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kamar.index');

        $kategoris = KategoriKamar::get();
        return \view('master-kamar.kamar.tambah-kamar', ['kategoris' => $kategoris], $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_kamar' => 'required|unique:kamar',
            'status' => 'required',
            'id_kategori_kamar' => 'required|numeric',
        ]);

        $newKamar = new Kamar;

        $newKamar->no_kamar = $request->get('no_kamar');
        $newKamar->status = $request->get('status');
        $newKamar->id_kategori_kamar = $request->get('id_kategori_kamar');

        $newKamar->save();

        return redirect()->route('kamar.create')->withStatus('Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->param['pageInfo'] = 'Edit Kamar';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kamar.index');

        $kategoris = KategoriKamar::get();
        $kamar = Kamar::findOrFail($id);

        return view('master-kamar.kamar.edit-kamar', ['kamar' => $kamar, 'kategoris' => $kategoris], $this->param);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        $kamar = Kamar::findOrFail($kode);
        $isUnique = $kamar->no_kamar == $request->get('no_kamar') ? "" : "|unique:kamar";

        $validatedData = $request->validate([
            'no_kamar' => 'required'.$isUnique,
            'id_kategori_kamar' => 'required|numeric',
            'status' => 'required',
        ]);        

        $kamar->no_kamar = $request->get('no_kamar');
        $kamar->status = $request->get('status');
        $kamar->id_kategori_kamar = $request->get('id_kategori_kamar');        

        $kamar->save();

        return redirect()->route('kamar.index')->withStatus('Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kamar = Kamar::findOrFail($id);
        $kamar->delete();

        return redirect()->route('kamar.index')->withStatus('Data berhasil dihapus.');
    }

    public function reservationChart()
    {
        $dari = date('Y-m-d');
        if (isset($_GET['dari'])) {
            $dari = $_GET['dari'];
        }

        $this->param['pageInfo'] = 'Reservation Chart';
        $this->param['dari'] = $dari;
        $this->param['kamar'] = Kamar::get();
        // $this->param['btnRight']['text'] = 'Lihat Data';
        // $this->param['btnRight']['link'] = route('kamar.index');
        return view('master-kamar.kamar.reservation-chart', $this->param);
    }

    public function getKamarTersedia()
    {
        $this->param['pageInfo'] = 'Cek Kamar';
        if (isset($_GET['dari']) && isset($_GET['sampai'])) {
            # code...
            $tgl_checkin = $_GET['dari'];
            $tgl_checkout = $_GET['sampai'];
            $this->param['kamarTersedia'] = \DB::table('kamar AS k')
                            ->select('k.id', 'k.no_kamar', 'kt.kategori_kamar')
                            ->join('kategori_kamar AS kt', 'kt.id', '=', 'k.id_kategori_kamar')
                            ->where('status','Tersedia')
                            ->whereNotIn('k.id', function($query) use ($tgl_checkin, $tgl_checkout){
                                $query->select('d.id_kamar')->from('detail_transaksi as d')
                                ->join('transaksi as t','t.kode_transaksi','d.kode_transaksi')
                                ->whereBetween('t.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                                ->orWhereBetween('t.tgl_checkout', [$tgl_checkin, $tgl_checkout])
                                ->where('t.status', '!=', 'Check Out');
                            })
                            ->orderBy('kategori_kamar', 'asc')
                            ->get();

            $this->param['kamarTidakTersedia'] = \DB::table('kamar AS k')
                            ->select('k.id', 'k.no_kamar', 'kt.kategori_kamar')
                            ->join('kategori_kamar AS kt', 'kt.id', '=', 'k.id_kategori_kamar')
                            ->where('status','Tersedia')
                            ->whereIn('k.id', function($query) use ($tgl_checkin, $tgl_checkout){
                                $query->select('d.id_kamar')->from('detail_transaksi as d')
                                ->join('transaksi as t','t.kode_transaksi','d.kode_transaksi')
                                ->whereBetween('t.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                                ->orWhereBetween('t.tgl_checkout', [$tgl_checkin, $tgl_checkout])
                                ->where('t.status', '!=', 'Check Out');
                            })
                            ->orderBy('kategori_kamar', 'asc')
                            ->get();
        }

        return view('master-kamar.kamar.cek-kamar', $this->param);
    }
}
