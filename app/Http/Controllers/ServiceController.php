<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Service;
use \App\KategoriService;

class ServiceController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-support-16 text-purple';
    }
    
    public function index(Request $request)
    {
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('service.create');

        $keyword = $request->get('keyword');
        $keywordKategori = $request->get('kategori-service');
        
        $kategori = KategoriService::get();
        $service = Service::with('kategori');

        if($keywordKategori){
            $service->where('id_kategori', $keywordKategori);
        }

        if ($keyword) {
            $service->where('service', 'LIKE', "%$keyword%");
        }
        return \view('master-service.service.list-service', ['service' => $service->paginate(10), 'kategori' => $kategori], $this->param);
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Service';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('service.index');

        $kategoris = KategoriService::get();
        return \view('master-service.service.tambah-service', ['kategoris' => $kategoris], $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'service' => 'required|unique:service',
            'id_kategori' => 'required|numeric',
            'icon' => 'required'
        ]);

        $newService = new Service;

        $newService->service = $request->get('service');
        $newService->icon = $request->get('icon');
        $newService->id_kategori = $request->get('id_kategori');

        $newService->save();

        return redirect()->route('service.create')->withStatus('Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->param['pageInfo'] = 'Edit Service';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('service.index');

        $kategoris = KategoriService::get();
        $service = Service::findOrFail($id);

        return view('master-service.service.edit-service', ['service' => $service, 'kategoris' => $kategoris], $this->param);
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
        $service = Service::findOrFail($kode);
        $isUnique = $service->service == $request->get('service') ? "" : "|unique:service";

        $validatedData = $request->validate([
            'service' => 'required'.$isUnique,
            'id_kategori' => 'required|numeric',
            'icon' => 'required'
        ]);        

        $service->service = $request->get('service');
        $service->icon = $request->get('icon');
        $service->id_kategori = $request->get('id_kategori');        

        $service->save();

        return redirect()->route('service.index')->withStatus('Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('service.index')->withStatus('Data berhasil dihapus.');
    }
}
