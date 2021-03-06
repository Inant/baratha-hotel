<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use \App\KategoriKamar;
use \App\Service;
use \App\FotoKategori;

class KategoriKamarController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-building text-info';
    }

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        if($keyword){
            $kategori = KategoriKamar::with('service')->where('kategori_kamar', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $kategori = KategoriKamar::with('service')->paginate(10);
        }

        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('kategori-kamar.create');

        return view('master-kamar.kategori-kamar.list-kategori-kamar', ['kategori_kamar' => $kategori], $this->param);
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Kategori Kamar';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-kamar.index');
        $this->param['service'] = Service::get();

        return view('master-kamar.kategori-kamar.tambah-kategori-kamar', $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori_kamar' => 'required|unique:kategori_kamar|max:30',
            'harga' => 'required|numeric',
            'service.*' => 'required',
        ]);

        $newKategoriKamar = new KategoriKamar;

        $newKategoriKamar->kategori_kamar = $request->get('kategori_kamar');
        $newKategoriKamar->harga = $request->get('harga');
        $newKategoriKamar->deskripsi = $request->get('deskripsi');

        if($request->file('foto')){
            $allFoto = "";
            $foto = $request->file('foto');
            $lg = count($foto);
            foreach ($foto as $key => $value) {
                $pathUpload = 'public/assets/img/foto-kamar';
                $namaFoto = time().".".$foto[$key]->getClientOriginalName();
                $foto[$key]->move($pathUpload,$namaFoto);
                // $menu->foto = $namaFoto;
                $allFoto .= $namaFoto;
                $key + 1 < $lg ? $allFoto.='|':'';
            }
        $newKategoriKamar->foto = $allFoto;
        }
        else{
            $newKategoriKamar->foto = 'kamar-default.png';
        }
        
        $newKategoriKamar->save();
        $newKategoriKamar->service()->attach($request->get('id_service'));

        return redirect()->route('kategori-kamar.create')->withStatus('Data berhasil ditambahkan.');
    }

    function edit($id)
    {   
        $kategori = KategoriKamar::findOrFail($id);
        $this->param['pageInfo'] = 'Edit Kategori Kamar';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-kamar.index');
        $this->param['service'] = Service::get();
        $this->param['selected'] = \DB::table('kategori_kamar_service')->select('id_service')->where('id_kategori_kamar' , $id)->get()->toArray();

        return view('master-kamar.kategori-kamar.edit-kategori-kamar', ['kategori' => $kategori], $this->param);
    }

    public function update(Request $request, $id)
    {
        $KategoriKamar = KategoriKamar::findOrFail($id);
        $isUnique = $KategoriKamar->kategori_kamar == $request->get('kategori_kamar') ? "" : "|unique:kategori_kamar";
        $kamar = explode('|', $KategoriKamar->foto);
        $validatedData = $request->validate([
            'kategori_kamar' => 'required|max:30'.$isUnique,
            'harga' => 'required|numeric',
            'service.*' => 'required'
        ]);

        $KategoriKamar->kategori_kamar = $request->get('kategori_kamar');
        $KategoriKamar->harga = $request->get('harga');
        $KategoriKamar->deskripsi = $request->get('deskripsi');
        
        if($request->file('foto')){
            if ($kamar != 'kamar-default.png') {
                foreach ($kamar as $key => $value) {
                    $file_path = asset('img/foto-kamar').'/'. $value;
                    Storage::delete($file_path);
                }
            }
            $allFoto = "";
            $foto = $request->file('foto');
            $lg = count($foto);
            foreach ($foto as $key => $value) {
                $pathUpload = 'public/assets/img/foto-kamar';
                $namaFoto = time().".".$foto[$key]->getClientOriginalName();
                $foto[$key]->move($pathUpload, $namaFoto);
                // $menu->foto = $namaFoto;
                $allFoto .= $namaFoto;
                $key + 1 < $lg ? $allFoto.='|':'';
            }
        $KategoriKamar->foto = $allFoto;
        }
        // else{
        //     $KategoriKamar->foto = 'kamar-default.png';
        // }

        $KategoriKamar->save();
        $KategoriKamar->service()->sync($request->get('id_service'));

        return redirect()->route('kategori-kamar.index')->withStatus('Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $KategoriKamar = KategoriKamar::findOrFail($id);
        $KategoriKamar->delete();

        return redirect()->route('kategori-kamar.index')->withStatus('Data berhasil dihapus.');
    }

    public function addFoto()
    {
        $fields = array(
            'foto' => 'foto',
        );
        $next = $_GET['biggestNo']+1;
        return view('master-kamar.kategori-kamar.tambah-foto', ['hapus' => true, 'no' => $next, 'fields' => $fields, 'idFoto' => '0']);
    }
}
