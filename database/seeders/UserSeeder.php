<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      if(!DB::table('users')->find(1)) {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin',
            'apellido' => 'Syloper',
            'email' => 'admin@syloper.com',
            'dni' => '-',
            'telefono' => '-',
            'role' => 'administrador',
            'estado' => 'activo',
            'password' => Hash::make('12345678'),
        ]);
      }
    }
}
