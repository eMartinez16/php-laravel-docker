<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrainCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grain_categories')->insert([
            [
                'id'   => 1,
                'name' => 'Arbitrajes',
            ],
            [
                'id'   => 2,
                'name' => 'Chamico',
            ],
            [
                'id'   => 3,
                'name' => 'Granos verdes',
            ],
            [
                'id'   => 4,
                'name' => 'Granos quemados o averia',
            ],
            [
                'id'   => 5,
                'name' => 'Granos dañados',
            ],
            [
                'id'   => 6,
                'name' => 'Granos quebrados y/o partidos - máximo',
            ],
            [
                'id'   => 7,
                'name' => 'Granos negros',
            ],
            [
                'id'   => 8,
                'name' => 'Tierra',
            ],
            [
                'id'   => 9,
                'name' => 'Materias extrañas',
            ],
            [
                'id'   => 10,
                'name' => 'Humedad',
            ],
           
        ]);
    }
}
