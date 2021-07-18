<?php

namespace Database\Seeders;

use App\Models\ModeloVeiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModeloVeiculoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marca_modelo_frotas')->delete();
        DB::table('modeloveiculos')->delete();

        $modelos = [
            ['descricao' => 'GOL', 'cd_usuario' => 1],
            ['descricao' => 'FUSCA', 'cd_usuario' => 1],
            ['descricao' => 'UNO', 'cd_usuario' => 1],
            ['descricao' => 'ONIX', 'cd_usuario' => 1],
            ['descricao' => 'F-350', 'cd_usuario' => 1],
            ['descricao' => 'DAILY', 'cd_usuario' => 1],
            ['descricao' => 'MONTANA', 'cd_usuario' => 1],
            ['descricao' => 'S10', 'cd_usuario' => 1]
          ];

        
        foreach($modelos as $m){
            ModeloVeiculo::create($m);
        }
        
    }
}