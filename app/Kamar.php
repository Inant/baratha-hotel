<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';

    public function kategori()
    {
        return $this->belongsTo('App\KategoriKamar', 'id_kategori_kamar');
    }
}
