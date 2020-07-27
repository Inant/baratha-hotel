<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKamarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_kamar', 10)->unique();
            $table->enum('status', ['Tersedia', 'Tidak Tersedia', 'Dalam Perbaikan']);
            $table->tinyInteger('id_kategori_kamar')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('id_kategori_kamar')
                    ->references('id')
                    ->on('kategori_kamar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamar');
    }
}
