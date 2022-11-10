<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CondicionFiscalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!DB::table('condicion_fiscal')->find(1)) {
          DB::table('condicion_fiscal')->insert([
              'id' => 1,
              'codigo' => 20,
              'nombre' => 'MONOTRIBUTO',            
          ]);
        }

        if(!DB::table('condicion_fiscal')->find(2)) {
          DB::table('condicion_fiscal')->insert([
              'id' => 2,
              'codigo' => 30,
              'nombre' => 'IVA RESPONSABLE INSCRIPTO',            
          ]);
        }

        if(!DB::table('condicion_fiscal')->find(3)) {
          DB::table('condicion_fiscal')->insert([
            'id' => 3,
            'codigo' => 32,
            'nombre' => 'IVA EXENTO',            
          ]);              
        }

        if(!DB::table('condicion_fiscal')->find(4)) {
          DB::table('condicion_fiscal')->insert([
            'id' => 4,
            'codigo' => 33,
            'nombre' => 'IVA RESPONSABLE NO INSCRIPTO',            
          ]);                      
        }
    }
}
