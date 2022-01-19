<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_transaksi', 15);
            $table->dateTime('waktu');
            $table->enum('jenis_pembayaran', ['Tunai', 'Debit BCA', 'Debit BRI', 'Kredit BCA', 'Kredit BRI','Kredit Bank Lain','Debit Bank Lain']);
            $table->integer('total');
            $table->integer('diskon');
            $table->integer('tax');
            $table->integer('charge');
            $table->integer('grandtotal');
            $table->integer('bayar');
            $table->timestamps();

            $table->foreign('kode_transaksi')
                    ->references('kode_transaksi')
                    ->on('transaksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
}
