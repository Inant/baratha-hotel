<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusBayarTableTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `transaksi` CHANGE `status_bayar` `status_bayar` ENUM('Sudah','Belum','Piutang') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            //
        });
    }
}
