<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nama', 40);
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('no_hp', 13);
            $table->text('alamat');
            $table->string('email', 40)->unique();
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->enum('level', ['Owner', 'Resepsionis']);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
