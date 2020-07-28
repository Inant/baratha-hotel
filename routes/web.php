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
        Route::resource('kategori-kamar', 'KategoriKamarController');
        Route::resource('kamar', 'KamarController');
    });

    Route::group(['prefix' => 'master-service'], function () {
        Route::resource('kategori-service', 'KategoriServiceController');
        Route::resource('service', 'ServiceController');
    });

    Route::group(['prefix' => 'transaksi'], function () {
        Route::get('check-in', 'TransaksiController@checkIn');
        Route::get('booking', 'TransaksiController@booking');
        Route::get('checkout/{kode}', 'TransaksiController@checkOut')->name('transaksi.checkout');
        Route::get('checkin-booking/{kode}', 'TransaksiController@checkInBooking')->name('transaksi.checkin-booking');
        Route::get('pembayaran/{kode}', 'TransaksiController@pembayaran')->name('transaksi.pembayaran');
        Route::resource('transaksi', 'TransaksiController');
    });

    // new route here
});
