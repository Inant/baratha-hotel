<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\PemasukanChart;
use \App\Kamar;

class Pages extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-tv-2 text-primary';
    }
    public function dashboard()
    {
        $bulan = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $tahun = date('Y');
        $pemasukanPerBulan = [];

        foreach ($bulan as $key => $val) {
            $cekPemasukan = \App\Pembayaran::whereYear('waktu', $tahun)->whereMonth('waktu', $val)->count();
            if ($cekPemasukan > 0) {
                $pemasukan = \DB::table('pembayaran')->select(\DB::raw('SUM(grandtotal) AS pemasukan'))->whereYear('waktu', $tahun)->whereMonth('waktu', $val)->get();
                array_push($pemasukanPerBulan, $pemasukan[0]->pemasukan);
            }
            else{
                array_push($pemasukanPerBulan, 0);
            }
            
            // echo "<pre>";
            // print_r ($pemasukan);
            // echo "</pre>";
            
        }

        $pemasukanChart = new PemasukanChart;
        $pemasukanChart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des']);
        $pemasukanChart->dataset('Pemasukan Per Bulan', 'line', $pemasukanPerBulan)->backgroundcolor('transparent')->color('#ffffff');

        // reservation chart
        $dari = date('Y-m-01');
        $this->param['dari'] = $dari;
        $this->param['kamar'] = Kamar::get();
        return view('pages.dashboard', [ 'pemasukanChart' => $pemasukanChart ], $this->param);
        // return view('pages.dashboard', $this->param);
    }
    // public function form()
    // {
    //     $this->param['pageInfo'] = 'Form input';
    //     $this->param['btnRight']['text'] = 'Lihat Data';
    //     $this->param['btnRight']['link'] = url('pages/list-data');
    //     return view('pages.form', $this->param);
    // }
    // public function list()
    // {
    //     $this->param['pageInfo'] = 'List Data';
    //     $this->param['btnRight']['text'] = 'Tambah Data';
    //     $this->param['btnRight']['link'] = url('pages/form');
    //     return view('pages.list-data', $this->param);
    // }
}
