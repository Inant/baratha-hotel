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

    // new route here
});
