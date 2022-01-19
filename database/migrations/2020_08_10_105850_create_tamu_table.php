<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTamuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tamu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 40);
            $table->string('email', 50);
            $table->enum('jenis_identitas', ['KTP', 'SIM'])->nullable();
            $table->string('no_identitas', 20)->nullable();
            $table->string('foto_identitas')->nullable();
            $table->string('company', 60)->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tamu');
    }
}
