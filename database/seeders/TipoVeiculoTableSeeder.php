<?php

namespace Database\Seeders;

use App\Models\TipoVeiculo;
use Illuminate\Database\Seeder;

class TipoVeiculoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoVeiculo::create([
            'descricao' => 'F.CARGA'
        ]);
        TipoVeiculo::create([
            'descricao' => 'F.UTILITÁRIO'
        ]);
        TipoVeiculo::create([
            'descricao' => 'PARTICULAR'
        ]);
    }
}
