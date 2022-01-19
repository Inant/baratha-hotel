<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service';

    public function kategori()
    {
        return $this->belongsTo('App\KategoriService', 'id_kategori');
    }

    public function kategoriKamar()
    {
        return $this->belongsToMany('App\KategoriKamar', 'kategori_kamar_service', 'id_service', 'id_kategori_kamar');
    }
}
