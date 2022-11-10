<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GranosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grains')->insert([
            [
                'id'   => 1,
                'name' => 'LINO',            
            ],
            [
                'id'   => 2,
                'name' => 'GIRASOL',
            ],
            [
                'id'   => 3,
                'name' => 'MANI EN CAJA',            
            ],
            [
                'id'   => 4,
                'name' => 'MANI PARA INDUSTRIA DE SELECCION',            
            ],
            [
                'id'   => 6,
                'name' => 'MANI PARA INDUSTRIA ACEITERA',            
            ],
            [
                'id'   => 7,
                'name' => 'MANI TIPO CONFITERIA',            
            ],
            [
                'id'   => 8,
                'name' => 'COLZA',            
            ],
            [
                'id'   => 9,
                'name' => 'COLZA 00 / CANOLA',            
            ],
            [
                'id'   => 10,
                'name' => 'TRIGO FORRAJERO',            
            ],
            [
                'id'   => 11,
                'name' => 'CEBADA FORRAJERA',            
            ],
            [
                'id'   => 12,
                'name' => 'CEBADA APTA PARA MALTERIA',            
            ],
            [
                'id'   => 14,
                'name' => 'TRIGO CANDEAL',            
            ],
            [
                'id'   => 15,
                'name' => 'TRIGO PAN',            
            ],
            [
                'id'   => 16,
                'name' => 'AVENA',            
            ],
            [
                'id'   => 17,
                'name' => 'CEBADA CERVECERA',            
            ],
            [
                'id'   => 18,
                'name' => 'CENTENO',            
            ],
            [
                'id'   => 19,
                'name' => 'MAIZ',            
            ],
            [
                'id'   => 20,
                'name' => 'MIJO',            
            ],
            [
                'id'   => 21,
                'name' => 'ARROZ CASCARA',            
            ],
            [
                'id'   => 22,
                'name' => 'SORGO GRANIFERO',            
            ],
            [
                'id'   => 23,
                'name' => 'SOJA',            
            ],
            [
                'id'   => 24,
                'name' => 'TRIGO BLANDO',            
            ],
            [
                'id'   => 25,
                'name' => 'TRIGO PLATA',            
            ],
            [
                'id'   => 26,
                'name' => 'MAIZ FLYNT O PLATA',            
            ],
            [
                'id'   => 27,
                'name' => 'MAIZ PISINGALLO',            
            ],
            [
                'id'   => 28,
                'name' => 'TRITICALE',            
            ],
            
            [
                'id'   => 30,
                'name' => 'ALPISTE'
            ],
            [
                'id'   => 31,
                'name' => 'ALGODÓN'
            ],
            [
                'id'   => 32,
                'name' => 'CÁRTAMO'
            ],
            [
                'id'   => 33,
                'name' => 'POROTO BLANCO NATURA OVAL Y ALUBIA'
            ],
            [
                'id'   => 34,
                'name' => 'POROTO DISTINTO DEL BLANCO OVAL Y ALUBIA'
            ],
            [
                'id'   => 35,
                'name' => 'ARROZ'
            ],
            [
                'id'   => 46,
                'name' => 'LENTEJA'
            ],
            [
                'id'   => 47,
                'name' => 'ARVEJA'
            ],
            [
                'id'   => 48,
                'name' => 'POROTO BLANCO SELECCIONADO OVAL Y ALUBIA'
            ],
            [
                'id'   => 49,
                'name' => 'OTRAS LEGUMBRES'
            ],
            [
                'id'   => 50,
                'name' => 'OTROS GRANOS'
            ],
            [
                'id'   => 59,
                'name' => 'GARBANZO',
            ],
            [
                'id'   => 60,
                'name' => 'AMARANTO',
            ],
            [
                'id'   => 61,
                'name' => 'AMAPOLA',
            ],
            [
                'id'   => 62,
                'name' => 'CHIA',
            ],
            [
                'id'   => 63,
                'name' => 'CORIANDRO',
            ],
            [
                'id'   => 64,
                'name' => 'HABAS',
            ],
            [
                'id'   => 65,
                'name' => 'LUPINES (LUPINUS MUTABILIS)'
            ],
            [
                'id'   => 66,
                'name' => 'LUPINO (LUPINUS ALBUS)'
            ],
            [
                'id'   => 68,
                'name' => 'MOSTAZA MARRÓN (BRASSICA JUNCEA)'
            ],
            [
                'id'   => 69,
                'name' => 'MOSTAZA NEGRA (BRASSICA NIGRA)'
            ],
            [
                'id'   => 70,
                'name' => 'MOSTAZA BLANCA (SINAPSIS ALBA)'
            ],
            [
                'id'   => 73,
                'name' => 'POROTO MANTECA (PHASEOLUS LUNATUS)'
            ],
            [
                'id'   => 74,
                'name' => 'POROTO MUNG (VIGNA RADIATA)'
            ],
            [
                'id'   => 76,
                'name' => 'POROTO PALLAR (PHASEOLUS COCCINEUS L)'
            ],
            
            [
                'id'   => 77,
                'name' => 'QUINOA (CHENOPODIUM QUINOA WILLD)'
            ],        
            [
                'id'   => 78,
                'name' => 'SESAMO (SESAMUS INDICUM)'
            ],            
            [
                'id'   => 80,
                'name' => 'TRIGO SARRACENO (FAGOPYRUM ESCULENTUM)'
            ],
            
            [
                'id'   => 81,
                'name' => 'AVENA AMARILLA (AVENA BYZANTINA)'
            ],
            
            [
                'id'   => 100,
                'name' => 'CEBADA'
            ],
            [
                'id'   => 101,
                'name' => 'MANI'
            ],
            [
                'id'   => 102,
                'name' => 'POROTO'
            ],
            [
                'id'   => 103,
                'name' => 'TRIGO'
            ],
            [
                'id'   => 104,
                'name' => 'SORGO'
            ],
            [
                'id'   => 105,
                'name' => 'OTROS GRANOS Y LEGUMBRES PROVENIENTES DE CULTIVOS DE INVIERNO (COSECHA FINA)'
            ],
            [
                'id'   => 106,
                'name' => 'OTROS GRANOS Y LEGUMBRES PROVENIENTES DE CULTIVOS DE VERANO (COSECHA GRUESA)'
            ],
            [
                'id'   => 107,
                'name' => 'COLZA/CANOLA'
            ],
        ]);


    }
}
