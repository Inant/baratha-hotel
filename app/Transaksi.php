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
        return $this->belongsTo('App\Kamar', 'id_kamar');
    }

    public function pembayaran()
    {
        return $this->hasOne('App\Pembayaran', 'kode_transaksi');
    }
}
