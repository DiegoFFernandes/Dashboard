<?php

namespace Database\Seeders;

use App\Models\FrotaVeiculos;
use Illuminate\Database\Seeder;

class FrotaVeiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FrotaVeiculos::create([
            'descricao' => 'Caminhão',
            'cd_usuario' => 1
        ]);
        FrotaVeiculos::create([
            'descricao' => 'Carro',
            'cd_usuario' => 1
        ]);
        FrotaVeiculos::create([
            'descricao' => 'Moto',
            'cd_usuario' => 1
        ]);
    }
}
