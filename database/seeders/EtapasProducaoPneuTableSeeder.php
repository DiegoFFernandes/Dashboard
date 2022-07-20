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
            ['dsetapaempresa' => 'RECEBIMENTO', 'nm_tabela' => 'LIMPEZAPNEU' , 'cd_etapa' => 5],
            ['dsetapaempresa' => 'LIMPEZA', 'nm_tabela' => '' , 'cd_etapa' => 0],
            ['dsetapaempresa' => 'EXAME INICIAL', 'nm_tabela' => 'EXAMEINICIAL' , 'cd_etapa' =>  1],
            ['dsetapaempresa' => 'RASPAGEM', 'nm_tabela' => 'RASPAGEMPNEU' , 'cd_etapa' => 2  ],
            ['dsetapaempresa' => 'ESCAREAÇÃO', 'nm_tabela' => 'ESCAREACAOPNEU' , 'cd_etapa' => 4  ],
            ['dsetapaempresa' => ' SEGUNDO EXAME', 'nm_tabela' => 'LIMPEZAMANCHAO' , 'cd_etapa' => 0 ],
            ['dsetapaempresa' => 'APLICAÇÃO DE COLA', 'nm_tabela' => 'APLICACAOCOLAPNEU' , 'cd_etapa' => 6 ],
            ['dsetapaempresa' => 'PREPARAÇÃO DE BANDA', 'nm_tabela' => 'PREPARACAOBANDAPNEU' , 'cd_etapa' => 3  ],
            ['dsetapaempresa' => 'ENCHIMENTO', 'nm_tabela' => 'EXTRUSORAPNEU' , 'cd_etapa' => 8 ],
            ['dsetapaempresa' => 'SETOR AZ', 'nm_tabela' => 'EXTRUSORAAUTOPNEU' , 'cd_etapa' => 20 ],
            ['dsetapaempresa' => 'APLICAÇÃO DE BANDA', 'nm_tabela' => 'EMBORRACHAMENTO' , 'cd_etapa' => 9 ],
            ['dsetapaempresa' => 'MONTAGEM', 'nm_tabela' => 'MONTAGEMRECAP' , 'cd_etapa' => 14 ],
            ['dsetapaempresa' => 'VULCANIZAÇÃO', 'nm_tabela' => 'VULCANIZACAO' , 'cd_etapa' => 11 ],
            ['dsetapaempresa' => 'DESMONTAGEM', 'nm_tabela' => '' , 'cd_etapa' => 12 ],
            ['dsetapaempresa' => 'EXAME FINAL', 'nm_tabela' => 'EXAMEFINALPNEU' , 'cd_etapa' => 12 ],
            ['dsetapaempresa' => 'DESENVELOPAMENTO', 'nm_tabela' => 'DESENVELOPAMENTO' , 'cd_etapa' => 16  ],
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
