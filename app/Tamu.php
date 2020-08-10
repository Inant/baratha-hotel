<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $table = 'tamu';

    public function transaksi()
    {
        return $this->hasMany('App\Transaksi', 'id_kamar');
    }
}
