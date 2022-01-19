<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKategoriKamarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kategori_kamar', function (Blueprint $table) {
            $table->integer('harga')->after('kategori_kamar');
            $table->text('deskripsi')->nullable()->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kategori_kamar', function (Blueprint $table) {
            $table->dropColumn('harga');
            $table->dropColumn('deskripsi');
        });
    }
}
