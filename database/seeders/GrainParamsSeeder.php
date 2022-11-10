<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrainParamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        
        DB::table('grain_params')->insert([
            [
                'id'          => 1,
                'grain_id'    => 23,
                'description' => 'MERMA POR CHAMICO',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'          => 2,
                'grain_id'    => 23,
                'description' => 'GRANOS DAÑADOS',    
                'created_at'  => now(),
                'updated_at'  => now(),        
            ],
            [
                'id'          => 3,
                'grain_id'    => 23,
                'description' => 'GRANOS QUEMADOS O DE AVERIA',   
                'created_at'  => now(),
                'updated_at'  => now(),        
            ],
            [
                'id'          => 4,
                'grain_id'    => 23,
                'description' => 'TOTAL GRANOS DAÑADOS',       
                'created_at'  => now(),
                'updated_at'  => now(),    
            ],
            [
                'id'          => 5,
                'grain_id'    => 2,
                'description' => 'TOTAL MATERIAS EXTRAÑAS',     
                'created_at'  => now(),
                'updated_at'  => now(),       
            ],
            [
                'id'          => 6,
                'grain_id'    => 2,
                'description' => 'CHAMICO(semillas cada 100 gr.)',    
                'created_at'  => now(),
                'updated_at'  => now(),        
            ],
            [
                'id'          => 7,
                'grain_id'    => 2,
                'description' => 'MATERIA GRASA (s.s.s.)',   
                'created_at'  => now(),
                'updated_at'  => now(),         
            ],
            [
                'id'          => 8,
                'grain_id'    => 2,
                'description' => 'LINO',     
                'created_at'  => now(),
                'updated_at'  => now(),       
            ],
            [
                'id'          => 9,
                'grain_id'    => 2,
                'description' => 'HUMEDAD',   
                'created_at'  => now(),
                'updated_at'  => now(),         
            ],
        ]);
    }
}
