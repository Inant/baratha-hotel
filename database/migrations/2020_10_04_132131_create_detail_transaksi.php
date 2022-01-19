<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->string('kode_transaksi',20);
            $table->integer('id_kamar')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('kode_transaksi')
                    ->references('kode_transaksi')
                    ->on('transaksi');

            $table->foreign('id_kamar')
                    ->references('id')
                    ->on('kamar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_transaksi');
    }
}
