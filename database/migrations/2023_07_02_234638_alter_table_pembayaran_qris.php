<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePembayaranQris extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `pembayaran` CHANGE `jenis_pembayaran` `jenis_pembayaran` ENUM('Tunai','Debit BCA','Debit BRI','Kredit BCA','Kredit BRI','Kredit Bank Lain','Debit Bank Lain','QRIS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
