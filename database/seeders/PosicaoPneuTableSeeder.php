<?php

namespace Database\Seeders;

use App\Models\PosicaoPneu;
use Illuminate\Database\Seeder;

class PosicaoPneuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $motivos = [
            ['ds_posicao' => '1 DIRECIONAL DIREITO'],
            ['ds_posicao' => '1 DIRECIONAL ESQUERDO'],
            ['ds_posicao' => '2 DIRECIONAL DIREITO'],
            ['ds_posicao' => '2 DIRECIONAL ESQUERDO'],
            ['ds_posicao' => 'TRAÇÃO DIREITA EXTERNA'],
            ['ds_posicao' => 'TRAÇÃO DIREITA INTERNA'],
            ['ds_posicao' => 'TRAÇÃO ESQUERDA EXTERNA'],
            ['ds_posicao' => 'TRAÇÃO ESQUERDA INTERNA'],
            ['ds_posicao' => 'TRUCK DIREITA EXTERNA'],
            ['ds_posicao' => 'TRUCK DIREITA INTERNA'],
            ['ds_posicao' => 'TRUCK ESQUERDA EXTERNA'],
            ['ds_posicao' => 'TRUCK ESQUERDA INTERNA'],
            ['ds_posicao' => '1 EIXO DIREITO EXTERNO'],
            ['ds_posicao' => '1 EIXO DIREITO INTERNO'],
            ['ds_posicao' => '1 EIXO ESQUERDO EXTERNO'],
            ['ds_posicao' => '1 EIXO ESQUERDO INTERNO'],
            ['ds_posicao' => '2 EIXO DIREITO EXTERNO'],
            ['ds_posicao' => '2 EIXO DIREITO INTERNO'],
            ['ds_posicao' => '2 EIXO ESQUERDO EXTERNO'],
            ['ds_posicao' => '2 EIXO ESQUERDO INTERNO'],
            ['ds_posicao' => '3 EIXO DIREITO EXTERNO'],
            ['ds_posicao' => '3 EIXO DIREITO INTERNO'],
            ['ds_posicao' => '3 EIXO ESQUERDO EXTERNO'],
            ['ds_posicao' => '3 EIXO ESQUERDO INTERNO'],
            ['ds_posicao' => '4 EIXO DIREITO EXTERNO'],
            ['ds_posicao' => '4 EIXO DIREITO INTERNO'],
            ['ds_posicao' => '4 EIXO ESQUERDO EXTERNO'],
            ['ds_posicao' => '4 EIXO ESQUERDO INTERNO'],
        ];

        foreach ($motivos as $m) {
            PosicaoPneu::insert([
                'ds_posicao' => $m['ds_posicao'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
