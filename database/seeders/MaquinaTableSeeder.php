<?php

namespace Database\Seeders;

use App\Models\Maquina;
use Illuminate\Database\Seeder;

class MaquinaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maquinas = [
            ['ds_maquina' => 'Cavalete'],
            ['ds_maquina' => 'Robo'],
            ['ds_maquina' => 'Himapel'],
            ['ds_maquina' => 'Mesa de Corte'],
            ['ds_maquina' => 'Cabine'],
            ['ds_maquina' => 'Roletadeira'],
            ['ds_maquina' => 'Mesa de Montagem'],
            ['ds_maquina' => 'Envelopadeira'],
            ['ds_maquina' => 'Auto Clave'],
            ['ds_maquina' => 'Elevador Pneumático'],
            ['ds_maquina' => 'Maquina'],
        ];

        foreach($maquinas as $m){
            Maquina::create($m);
        }
    }
}
