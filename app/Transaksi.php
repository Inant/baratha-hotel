<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'kode_transaksi';
    public $incrementing = false;
    
    public function tamu()
    {
        return $this->belongsTo('App\Tamu', 'id_tamu');
    }

    public function pembayaran()
    {
        return $this->hasOne('App\Pembayaran', 'kode_transaksi');
    }

    public function detail_transaksi(){
        return $this->hasMany('App\Detail_transaksi','kode_transaksi');
    }
}
