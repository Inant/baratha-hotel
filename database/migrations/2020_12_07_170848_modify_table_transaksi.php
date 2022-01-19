<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTableTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE transaksi MODIFY COLUMN tipe_pemesanan ENUM('Website', 'Offline', 'Traveloka', 'Booking', 'Travel Agent')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE transaksi MODIFY COLUMN tipe_pemesanan ENUM('Online', 'Offline')");
    }
}
