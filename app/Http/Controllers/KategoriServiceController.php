<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\KategoriService;

class KategoriServiceController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-support-16 text-purple';
    }

    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        if($keyword){
            $kategori = KategoriService::where('kategori_service', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $kategori = KategoriService::paginate(10);
        }

        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('kategori-service.create');

        return view('master-service.kategori-service.list-kategori-service', ['kategori' => $kategori], $this->param);
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Kategori Service';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-service.index');

        return view('master-service.kategori-service.tambah-kategori-service', $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori' => 'required|unique:kategori_service|max:30',
        ]);

        $newKategoriService = new KategoriService;

        $newKategoriService->kategori = $request->get('kategori');

        $newKategoriService->save();

        return redirect()->route('kategori-service.create')->withStatus('Data berhasil ditambahkan.');
    }

    function edit($id)
    {   
        $kategori = KategoriService::findOrFail($id);
        $this->param['pageInfo'] = 'Edit Kategori Service';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-service.index');

        return view('master-service.kategori-service.edit-kategori-service', ['kategori' => $kategori], $this->param);
    }

    public function update(Request $request, $id)
    {
        $KategoriService = KategoriService::findOrFail($id);
        $isUnique = $KategoriService->kategori == $request->get('kategori') ? "" : "|unique:kategori_service";

        $validatedData = $request->validate([
            'kategori' => 'required|max:30'.$isUnique,
        ]);

        $KategoriService->kategori = $request->get('kategori');

        $KategoriService->save();

        return redirect()->route('kategori-service.index')->withStatus('Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $KategoriService = KategoriService::findOrFail($id);
        $KategoriService->delete();

        return redirect()->route('kategori-service.index')->withStatus('Data berhasil dihapus.');
    }
}
