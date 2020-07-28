<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriService extends Model
{
    protected $table = 'kategori_service';

    public function service()
    {
        return $this->hasMany('App\Service');
    }
}
