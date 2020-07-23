<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = new \App\User;
        $administrator->nama = 'Administrator';
        $administrator->gender = 'Laki-laki';
        $administrator->no_hp = '-';
        $administrator->alamat = '-';
        $administrator->email = 'administrator@baratha.id';
        $administrator->username = 'administrator';
        $administrator->password = Hash::make('administrator');
        $administrator->level = 'Owner';
        $administrator->save();
    }
}
