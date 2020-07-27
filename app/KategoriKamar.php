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
}
