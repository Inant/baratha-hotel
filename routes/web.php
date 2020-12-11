<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/','Pages@dashboard');
    // user
    Route::get('user/ganti-password/', 'UserController@gantiPassword')->name('user.ganti-password');

    Route::put('user/update-password/{id}', 'UserController@updatePassword')->name('user.update-password');

    Route::resource('user', 'UserController');
    
    Route::group(['prefix' => 'master-kamar'], function () {
        Route::get('kategori-kamar/foto/addFoto', 'KategoriKamarController@addFoto');
        Route::get('reservation-chart', 'KamarController@reservationChart');
        Route::get('cek-kamar', 'KamarController@getKamarTersedia');
        Route::resource('kategori-kamar', 'KategoriKamarController');
        Route::resource('kamar', 'KamarController');
    });

    Route::group(['prefix' => 'master-fasilitas'], function () {
        Route::resource('kategori-fasilitas', 'KategoriServiceController');
        Route::resource('fasilitas', 'ServiceController');
    });

    Route::group(['prefix' => 'transaksi'], function () {
        Route::get('check-in', 'TransaksiController@checkIn');
        Route::get('booking', 'TransaksiController@booking');
        Route::get('checkout/{kode}', 'TransaksiController@checkOut')->name('transaksi.checkout');
        Route::get('checkin-booking/{kode}', 'TransaksiController@checkInBooking')->name('transaksi.checkin-booking');
        Route::get('pembayaran/{kode}', 'TransaksiController@pembayaran')->name('transaksi.pembayaran');
        Route::get('invoice', 'TransaksiController@listInvoice')->name('transaksi.list-invoice');
        Route::get('edit-invoice/{kode}', 'TransaksiController@editInvoice')->name('transaksi.edit-invoice');
        Route::post('save-invoice', 'TransaksiController@saveInvoice')->name('transaksi.save-invoice');
        Route::get('paid/{kode}', 'TransaksiController@paid')->name('transaksi.paid');
        Route::get('invoice/{kode}', 'TransaksiController@invoice')->name('transaksi.invoice');
        Route::get('laporan', 'TransaksiController@laporan');
        Route::get('laporan-reservasi', 'TransaksiController@printLaporan')->name('laporan-reservasi');
        Route::post('save-pembayaran', 'TransaksiController@savePembayaran')->name('transaksi.save-pembayaran');
        Route::get('get-kamar', 'TransaksiController@getKamarTersedia');
        Route::get('reservasi', 'TransaksiController@reservasi');
        Route::get('online', 'TransaksiController@online');
        Route::get('pembayaran-online', 'TransaksiController@listPembayaranOnline')->name('transaksi.list-pembayaran-online');
        Route::get('pembayaran-online/detail/{kode}', 'TransaksiController@detailPembayaran');
        Route::get('online/verifikasi', 'TransaksiController@updatePembayaranOnline');
        Route::resource('tamu', 'TamuController');
        Route::resource('transaksi', 'TransaksiController');
    });

    // new route here
});
