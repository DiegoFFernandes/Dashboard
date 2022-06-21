<?php

namespace Database\Seeders;

use App\Models\EtapasProducaoPneu;
use Illuminate\Database\Seeder;

class EtapasProducaoPneuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $etapas = [
            ['dsetapaempresa' => 'RECEBIMENTO'],
            ['dsetapaempresa' => 'LIMPEZA'],
            ['dsetapaempresa' => 'EXAME INICIAL'],
            ['dsetapaempresa' => 'RASPAGEM'],
            ['dsetapaempresa' => 'ESCAREAÇÃO'],
            ['dsetapaempresa' => ' SEGUNDO EXAME'],
            ['dsetapaempresa' => 'APLICAÇÃO DE COLA'],
            ['dsetapaempresa' => 'PREPARAÇÃO DE BANDA'],
            ['dsetapaempresa' => 'ENCHIMENTO'],
            ['dsetapaempresa' => 'SETOR AZ'],
            ['dsetapaempresa' => 'APLICAÇÃO DE BANDA'],
            ['dsetapaempresa' => 'MONTAGEM'],
            ['dsetapaempresa' => 'VULCANIZAÇÃO'],
            ['dsetapaempresa' => 'DESMONTAGEM'],
            ['dsetapaempresa' => 'EXAME FINAL'],
            ['dsetapaempresa' => 'DESENVELOPAMENTO'],
        ];

        foreach($etapas as $e){
            EtapasProducaoPneu::insert(
                ['dsetapaempresa' => $e['dsetapaempresa'], 
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
