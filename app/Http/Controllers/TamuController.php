<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Tamu;

class TamuController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-credit-card text-orange';
    }

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        if($keyword){
            $kategori = Tamu::where('nama', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $kategori = Tamu::paginate(10);
        }
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('tamu.create');

        return view('transaksi.tamu.list-tamu', ['kategori' => $kategori], $this->param);
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Tamu';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('tamu.index');

        return view('transaksi.tamu.tambah-tamu', $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:40',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'foto_identitas' => 'required|image',
        ]);

        $newTamu = new Tamu;

        $newTamu->nama = $request->get('nama');
        $newTamu->jenis_identitas = $request->get('jenis_identitas');
        $newTamu->no_identitas = $request->get('no_identitas');

        if($request->file('foto_identitas')){
            $foto = $request->file('foto_identitas');
            $pathUpload = 'public/assets/img/foto-id-tamu';
            $namaFile = time().".".$foto->getClientOriginalName();
            $foto->move($pathUpload, $namaFile);
            // $namaFoto = time().".".$foto->getClientOriginalName();
        }
        else{
            $namaFile = 'id-default.png';
        }
        $newTamu->foto_identitas = $namaFile;
        $newTamu->company = $request->get('company');
        $newTamu->street = $request->get('street');
        $newTamu->city = $request->get('city');
        $newTamu->phone = $request->get('phone');

        $newTamu->save();

        return redirect()->route('tamu.create')->withStatus('Data berhasil ditambahkan.');
    }    

    public function edit($id)
    {
        $tamu = Tamu::findOrFail($id);
        $this->param['pageInfo'] = 'Edit Tamu';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('tamu.index');

        return view('transaksi.tamu.edit-tamu', ['tamu' => $tamu], $this->param);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:40',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
        ]);

        $tamu = Tamu::findOrFail($id);

        $tamu->nama = $request->get('nama');
        $tamu->jenis_identitas = $request->get('jenis_identitas');
        $tamu->no_identitas = $request->get('no_identitas');

        if($request->file('foto_identitas')){
            $foto = $request->file('foto_identitas');
            $pathUpload = 'public/assets/img/foto-id-tamu';
            $namaFile = time().".".$foto->getClientOriginalName();
            $foto->move($pathUpload, $namaFile);
            // $namaFoto = time().".".$foto->getClientOriginalName();
            $tamu->foto_identitas = $namaFile;
        }
        $tamu->company = $request->get('company');
        $tamu->street = $request->get('street');
        $tamu->city = $request->get('city');
        $tamu->phone = $request->get('phone');

        $tamu->save();

        return redirect()->route('tamu.index')->withStatus('Data berhasil diperbarui.');
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
