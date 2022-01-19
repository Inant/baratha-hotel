<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    public function transaksi()
    {
        return $this->belongsTo('App\Transaksi', 'kode_transaksi');
    }
}
