<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'kode_transaksi';
    public $incrementing = false;

    public function kamar()
    {
        return $this->hasOne('App\Kamar', 'id');
    }
}
