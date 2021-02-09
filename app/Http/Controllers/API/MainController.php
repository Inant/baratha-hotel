<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pembayaran;

class MainController extends Controller
{
    public function getTotalByDate($date)
    {
        $pembayaran = Pembayaran::select(
                                    \DB::raw('SUM(pembayaran.total - pembayaran.diskon) AS total'),
                                    \DB::raw('SUM(pembayaran.tax) AS total_ppn')
                                )
                                ->whereBetween('pembayaran.waktu', [$date.' 00:00:00', $date.' 23:59:59'])                                                
                                ->join('transaksi', 'transaksi.kode_transaksi', 'pembayaran.kode_transaksi')
                                ->where('transaksi.status_bayar', 'Sudah')
                                ->first();
        
        return json_encode($pembayaran);
    }
}
