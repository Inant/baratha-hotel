<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pembayaran;

class MainController extends Controller
{
    public function getTotalByDate($date)
    {
        $status = null;
        $msg = null;
        $pembayaran = null;
        try{
            if($date == null){
                $status = 'Failed';
                $msg = 'Date is empty';
            }
            elseif($date == 'null'){
                $status = 'Failed';
                $msg = 'Date is empty';
            } 
            else{
                $pembayaran = Pembayaran::select(
                    \DB::raw('SUM(pembayaran.total - pembayaran.diskon) AS total'),
                    \DB::raw('SUM(pembayaran.tax) AS total_ppn'),
                    \DB::raw('jenis_pembayaran AS jenis_bayar'),
                )
                ->whereBetween('pembayaran.waktu', [$date.' 00:00:00', $date.' 23:59:59'])                                                
                ->join('transaksi', 'transaksi.kode_transaksi', 'pembayaran.kode_transaksi')
                ->where('transaksi.status_bayar', 'Sudah')
                ->groupBy('pembayaran.jenis_pembayaran')
                ->get();

                $status = 'Success';
                $msg = 'Successfully';
            }
        }
        catch(Exception $e){
            $status = 'Error';
            $msg = $e->getMessage();  
        }
        catch(\Illuminate\Database\QueryException $e){
            $status = 'Database Error';
            $msg = $e->getMessage();
        }
        finally{
            $response = [
                'status' => $status,
                'message' => $msg,
                'data' => $pembayaran
            ];
            return json_encode($response);
        }
    }
}
