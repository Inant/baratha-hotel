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
        $keywordKategori = $request->get('kategori-kamar');
        
        $kategori = KategoriKamar::get();
        $kamar = Kamar::with('kategori');

        if($keywordKategori){
            $kamar->where('id_kategori_kamar', $keywordKategori);
        }

        if ($keyword) {
            $kamar->where('no_kamar', 'LIKE', "%$keyword%");
        }
        return \view('master-kamar.kamar.list-kamar', ['kamar' => $kamar->paginate(10), 'kategori' => $kategori], $this->param);
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
}
