<?php

namespace Database\Seeders;

use App\Models\Epi;
use Illuminate\Database\Seeder;

class EpiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $epis = [
            ['ds_epi' => 'ÓCULOS DE PROTEÇÃO'],
            ['ds_epi' => 'PROTETOR AURICULAR TIPO CONCHA'],
            ['ds_epi' => 'AVENTAL DE RASPA DE COURO'],
            ['ds_epi' => 'AVENTAL DE RASPA'],
            ['ds_epi' => 'LUVAS DE VAQUETA E POLIVINIL'],
            ['ds_epi' => 'LUVAS DE VAQUETA'],
            ['ds_epi' => 'LUVAS POLIVINIL'],
            ['ds_epi' => 'MANGOTE DE RASPA DE COURO'],
            ['ds_epi' => 'MANGOTE DE RASPA'],
            ['ds_epi' => 'ÓCULOS DE PROTEÇÃO'],
            ['ds_epi' => 'PROTETOR AURICULAR'],
            ['ds_epi' => 'PROTETOR AURICULAR PLUG OU CONCHA'],
            ['ds_epi' => 'RESPIRADOR SEMI-FACIAL COM FILTRO PARA ORGÂNICOS'],
            ['ds_epi' => 'RESPIRADOR SEMI-FACIAL PFF3'],
            ['ds_epi' => 'RESPIRADOR SEMI-FACIAL PFF3 - MASCARA'],
            ['ds_epi' => 'SAPATOS DE PROTEÇÃO']
        ];
        foreach ($epis as $key => $e) {
            Epi::insert([
                'ds_epi' => $e['ds_epi'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
