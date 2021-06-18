<?php

namespace Database\Seeders;

use App\Models\MarcaVeiculo;
use Illuminate\Database\Seeder;

class MarcaVeiculoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MarcaVeiculo::create([
            'descricao' => 'VOLKSWAGEN', 
            'cd_frotaveiculos' => '1',
            'cd_marca' => '2'
        ]);

        MarcaVeiculo::create([
            'descricao' => 'VOLKSWAGEN', 
            'cd_frotaveiculos' => '2',
            'cd_marca' => '2'
        ]);
        MarcaVeiculo::create([
            'descricao' => 'FIAT', 
            'cd_frotaveiculos' => '1',
            'cd_marca' => '3'
        ]);

        MarcaVeiculo::create([
            'descricao' => 'FIAT', 
            'cd_frotaveiculos' => '2',
            'cd_marca' => '3'
        ]);
    }
}
