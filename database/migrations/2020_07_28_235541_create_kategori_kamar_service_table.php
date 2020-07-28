<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriKamarServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_kamar_service', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('id_kategori_kamar')->unsigned()->nullable();
            $table->integer('id_service')->unsigned()->nullable();
            // $table->timestamps();

            $table->foreign('id_kategori_kamar')->references('id')->on('kategori_kamar');
            $table->foreign('id_service')->references('id')->on('service');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_kamar_service');
    }
}
