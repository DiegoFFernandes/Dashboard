<?php

namespace Database\Seeders;

use App\Models\ModeloVeiculo;
use Illuminate\Database\Seeder;

class ModeloVeiculoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModeloVeiculo::create([
            'cd_marca'         => 2,
            'cd_frotaveiculos' => 2,
            'descricao'        => 'GOL',
        ]);

        ModeloVeiculo::create([
            'cd_marca'         => 3,
            'cd_frotaveiculos' => 1,
            'descricao'        => '190',
        ]);

        ModeloVeiculo::create([
            'cd_marca'         => 3,
            'cd_frotaveiculos' => 2,
            'descricao'        => '147',
        ]);
    }
}
