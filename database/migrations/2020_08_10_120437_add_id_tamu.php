<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdTamu extends Migration
{

    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->integer('id_tamu')->unsigned()->nullable()->after('kode_transaksi');

            $table->foreign('id_tamu')
                ->references('id')
              ->on('tamu');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign('transaksi_id_tamu_foreign');
            $table->dropColumn('id_tamu');
        });
    }
}
