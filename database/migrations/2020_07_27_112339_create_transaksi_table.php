<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('kode_transaksi', 15);
            $table->dateTime('waktu');
            $table->string('nama_tamu', 30);
            $table->enum('jenis_identitas', ['KTP', 'SIM']);
            $table->string('no_identitas', 20);
            $table->date('tgl_checkin');
            $table->date('tgl_checkout');
            $table->integer('id_kamar')->unsigned()->nullable();
            $table->enum('status', ['Check In', 'Check Out', 'Booking']);
            $table->enum('status_bayar', ['Sudah', 'Belum']);
            $table->enum('tipe_pemesanan', ['Website', 'Offline', 'Traveloka', 'Booking', 'Travel Agent']);
            $table->timestamps();

            $table->primary('kode_transaksi');
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
        Schema::dropIfExists('transaksi');
    }
}
