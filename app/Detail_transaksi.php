<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_transaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';

    public function transaksi(){
        return $this->belongsTo('App\Transaksi','kode_transaksi');
    }

    public function kamar(){
        return $this->belongsTo('App\Kamar','id');
    }
    //
}
