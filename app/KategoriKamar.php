<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriKamar extends Model
{
    protected $table='kategori_kamar';

    public function kamar()
    {
        return $this->hasMany('App\Kamar');
    }

    public function service()
    {
        return $this->belongsToMany('App\Service', 'kategori_kamar_service', 'id_kategori_kamar', 'id_service');
    }
}
