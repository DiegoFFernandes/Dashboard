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
            'descricao' => 'Caminhão'
        ]);
        FrotaVeiculos::create([
            'descricao' => 'Carro'
        ]);
        FrotaVeiculos::create([
            'descricao' => 'Moto'
        ]);
    }
}
