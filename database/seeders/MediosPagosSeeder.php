<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediosPagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      if(!DB::table('medios_pagos')->find(1)) {
        DB::table('medios_pagos')->insert([
            'id' => 1,
            'name' => 'Transferencia',            
        ]);
      }
      if(!DB::table('medios_pagos')->find(2)) {
        DB::table('medios_pagos')->insert([
            'id' => 2,
            'name' => 'Cheque',            
        ]);
      }
      if(!DB::table('medios_pagos')->find(3)) {
        DB::table('medios_pagos')->insert([
            'id' => 3,
            'name' => 'Efectivo',            
        ]);
      }            
    }
}
