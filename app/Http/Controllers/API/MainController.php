<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaksi;
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
    public function penjualan($kodePenjualan)
    {
        $penjualan = Transaksi::select(
            'transaksi.kode_transaksi as kode_penjualan',
            // 'tamu.nama as nama_customer',
            'transaksi.waktu',
            'transaksi.tgl_checkout',
            'transaksi.tgl_checkin',
        )
        // ->join('tamu','transaksi.id_tamu','tamu.id')
        ->where('transaksi.status_bayar', 'Piutang')
        ->where('transaksi.kode_transaksi', $kodePenjualan)
        ->get();

        foreach ($penjualan as $key => $value) {
            $diff = strtotime($value->tgl_checkout) - strtotime($value->tgl_checkin);
            $durasi = abs(round($diff / 86400));

            $kamar = \DB::table('detail_transaksi as dt')
            ->select(\DB::raw('SUM(kk.harga) AS totalBiayaKamar'),'dt.kode_transaksi')
            ->join('kamar as k','dt.id_kamar','k.id')->join('kategori_kamar as kk','k.id_kategori_kamar','kk.id')
            ->where('dt.kode_transaksi',$value->kode_penjualan)
            ->groupBy('dt.kode_transaksi')->first();
            $value->total = $kamar->totalBiayaKamar * $durasi;
            $value->total_ppn = 10/100 * $value->total;
        }
        
        return $penjualan;
    }
    public function getPiutang($kodePenjualan)
    {
        $kodePenjualan = str_replace('-', '/', $kodePenjualan);

        $status = null;
        $msg = null;
        $penjualan = null;

        try{
            // $where['transaksi.status_bayar'] = 'Piutang';
            // if(isset($_GET['kode_penjualan'])){
            //     $where['transaksi.kode_transaksi'] = $kodePenjualan;
            // }
            $penjualan = $this->penjualan($kodePenjualan);  
            $status = count($penjualan)==0 ? 'Kosong' : 'Success';
            $msg = 'Successfully';
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
                'data' => $penjualan
            ];
            return $response;
        }
    }
    public function getListPiutang()
    {
        $status = null;
        $msg = null;
        $penjualan = null;

        try{
            // $where['transaksi.status_bayar'] = 'Piutang';
            // if(isset($_GET['kode_penjualan'])){
            //     $where['transaksi.kode_transaksi'] = $kodePenjualan;
            // }
            $penjualan = Transaksi::select(
                'transaksi.kode_transaksi as kode_penjualan',
                'tamu.nama as nama_customer',
                'transaksi.waktu',
                'transaksi.tgl_checkout',
                'transaksi.tgl_checkin',
            )
            ->join('tamu','transaksi.id_tamu','tamu.id')
            ->where('transaksi.status_bayar', 'Piutang')
            ->get();

            foreach ($penjualan as $key => $value) {
                $diff = strtotime($value->tgl_checkout) - strtotime($value->tgl_checkin);
                $durasi = abs(round($diff / 86400));

                $kamar = \DB::table('detail_transaksi as dt')
                ->select(\DB::raw('SUM(kk.harga) AS totalBiayaKamar'),'dt.kode_transaksi')
                ->join('kamar as k','dt.id_kamar','k.id')->join('kategori_kamar as kk','k.id_kategori_kamar','kk.id')
                ->where('dt.kode_transaksi',$value->kode_penjualan)
                ->groupBy('dt.kode_transaksi')->first();
                $value->total = $kamar->totalBiayaKamar * $durasi;
                $value->total_ppn = 10/100 * $value->total;
            }
                
            $status = count($penjualan)==0 ? 'Kosong' : 'Success';
            $msg = 'Successfully';
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
                'data' => $penjualan
            ];
            return $response;
        }
    }
    public function bayarPiutang(\Illuminate\Http\Request $request)
    {
        $status = null;
        $msg = null;
        try{
            if(isset($_POST['kode_transaksi'])){
                $penjualan = $this->penjualan($_POST['kode_transaksi']);  
                $grand = $penjualan[0]->total = $penjualan[0]->total_ppn;
                $newPembayaran = new Pembayaran;
                $newPembayaran->kode_transaksi = $request->get('kode_transaksi');
                $newPembayaran->waktu = date('Y-m-d H:i:s');
                $newPembayaran->jenis_pembayaran = 'Tunai';
                $newPembayaran->no_kartu = null;
                $newPembayaran->total = $penjualan[0]->total;
                $newPembayaran->diskon = 0;
                $newPembayaran->tax = $penjualan[0]->total_ppn;
                $newPembayaran->charge = 0;
                $newPembayaran->grandtotal = $grand;
                $newPembayaran->bayar = $grand;
        
                $newPembayaran->save();
        
                $transaksi = Transaksi::find($request->get('kode_transaksi'));
                $transaksi->status_bayar = 'Piutang Terbayar';
                $transaksi->status = 'Check Out';
                $transaksi->save();

                $status = 'Success';
                $msg = 'Successfully';
            }
            else{
                $status = 'Failed';   
                $msg = 'Kode transaksi is empty';
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
                'message' => $msg
            ];
            return json_encode($response);
        }
    }
}
