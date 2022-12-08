<?php

namespace Database\Seeders;

use App\Models\EtapaMaquina;
use Illuminate\Database\Seeder;

class EtapaMaquinaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $etapaMaquina = [
            ['cd_empresa' => 3, 'cd_etapa_producao' => 1, 'cd_maquina' => 1, 'cd_seq_maq' => 1, 'cd_barras' => 311],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 1, 'cd_maquina' => 1, 'cd_seq_maq' => 2, 'cd_barras' => 312],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 1, 'cd_maquina' => 1, 'cd_seq_maq' => 3, 'cd_barras' => 313],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 1, 'cd_maquina' => 1, 'cd_seq_maq' => 4, 'cd_barras' => 314],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 2, 'cd_maquina' => 2, 'cd_seq_maq' => 1, 'cd_barras' => 321],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 2, 'cd_maquina' => 2, 'cd_seq_maq' => 2, 'cd_barras' => 322],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 2, 'cd_maquina' => 3, 'cd_seq_maq' => 3, 'cd_barras' => 323],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 3, 'cd_maquina' => 4, 'cd_seq_maq' => 1, 'cd_barras' => 331],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 3, 'cd_maquina' => 4, 'cd_seq_maq' => 2, 'cd_barras' => 332],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 1, 'cd_barras' => 341],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 2, 'cd_barras' => 342],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 3, 'cd_barras' => 343],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 4, 'cd_barras' => 344],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 5, 'cd_barras' => 345],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 6, 'cd_barras' => 346],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 7, 'cd_barras' => 347],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 4, 'cd_maquina' => 1, 'cd_seq_maq' => 8, 'cd_barras' => 348],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 5, 'cd_maquina' => 1, 'cd_seq_maq' => 1, 'cd_barras' => 351],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 5, 'cd_maquina' => 1, 'cd_seq_maq' => 2, 'cd_barras' => 352],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 5, 'cd_maquina' => 1, 'cd_seq_maq' => 3, 'cd_barras' => 353],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 6, 'cd_maquina' => 5, 'cd_seq_maq' => 1, 'cd_barras' => 361],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 6, 'cd_maquina' => 5, 'cd_seq_maq' => 2, 'cd_barras' => 362],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 8, 'cd_maquina' => 1, 'cd_seq_maq' => 1, 'cd_barras' => 381],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 8, 'cd_maquina' => 1, 'cd_seq_maq' => 2, 'cd_barras' => 382],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 9, 'cd_maquina' => 6, 'cd_seq_maq' => 1, 'cd_barras' => 391],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 9, 'cd_maquina' => 6, 'cd_seq_maq' => 2, 'cd_barras' => 392],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 9, 'cd_maquina' => 6, 'cd_seq_maq' => 3, 'cd_barras' => 393],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 9, 'cd_maquina' => 6, 'cd_seq_maq' => 4, 'cd_barras' => 394],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 9, 'cd_maquina' => 6, 'cd_seq_maq' => 5, 'cd_barras' => 395],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 10, 'cd_maquina' => 1, 'cd_seq_maq' => 1, 'cd_barras' => 3101],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 10, 'cd_maquina' => 1, 'cd_seq_maq' => 2, 'cd_barras' => 3102],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 10, 'cd_maquina' => 7, 'cd_seq_maq' => 3, 'cd_barras' => 3103],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 10, 'cd_maquina' => 8, 'cd_seq_maq' => 4, 'cd_barras' => 3104],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 10, 'cd_maquina' => 9, 'cd_seq_maq' => 5, 'cd_barras' => 3105],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 10, 'cd_maquina' => 10, 'cd_seq_maq' => 6, 'cd_barras' => 3106],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 1, 'cd_barras' => 3111],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 2, 'cd_barras' => 3112],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 3, 'cd_barras' => 3113],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 4, 'cd_barras' => 3114],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 5, 'cd_barras' => 3115],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 6, 'cd_barras' => 3116],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 7, 'cd_barras' => 3117],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 11, 'cd_maquina' => 9, 'cd_seq_maq' => 8, 'cd_barras' => 3118],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 12, 'cd_maquina' => 1, 'cd_seq_maq' => 1, 'cd_barras' => 3121],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 12, 'cd_maquina' => 1, 'cd_seq_maq' => 2, 'cd_barras' => 3122],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 12, 'cd_maquina' => 1, 'cd_seq_maq' => 3, 'cd_barras' => 3123],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 12, 'cd_maquina' => 1, 'cd_seq_maq' => 4, 'cd_barras' => 3124],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 13, 'cd_maquina' => 11, 'cd_seq_maq' => 1, 'cd_barras' => 313111],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 13, 'cd_maquina' => 11, 'cd_seq_maq' => 2, 'cd_barras' => 313112],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 14, 'cd_maquina' => 7, 'cd_seq_maq' => 1, 'cd_barras' => 3141],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 14, 'cd_maquina' => 7, 'cd_seq_maq' => 2, 'cd_barras' => 3142],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 14, 'cd_maquina' => 7, 'cd_seq_maq' => 3, 'cd_barras' => 3143],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 14, 'cd_maquina' => 7, 'cd_seq_maq' => 4, 'cd_barras' => 3144],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 15, 'cd_maquina' => 8, 'cd_seq_maq' => 1, 'cd_barras' => 3151],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 15, 'cd_maquina' => 8, 'cd_seq_maq' => 2, 'cd_barras' => 3152],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 16, 'cd_maquina' => 8, 'cd_seq_maq' => 1, 'cd_barras' => 3161],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 16, 'cd_maquina' => 8, 'cd_seq_maq' => 2, 'cd_barras' => 3162],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 20, 'cd_maquina' => 11, 'cd_seq_maq' => 1, 'cd_barras' => 3201],
            ['cd_empresa' => 3, 'cd_etapa_producao' => 20, 'cd_maquina' => 11, 'cd_seq_maq' => 2, 'cd_barras' => 3202]
        ];

        foreach ($etapaMaquina as $key => $m) {
            EtapaMaquina::insert(
                [
                    'cd_empresa' => $m['cd_empresa'],
                    'cd_etapa_producao' => $m['cd_etapa_producao'],
                    'cd_maquina' => $m['cd_maquina'],
                    'cd_seq_maq' => $m['cd_seq_maq'],
                    'cd_barras' => $m['cd_barras'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
