<?php

namespace Database\Seeders;

use App\Models\Setor;
use Illuminate\Database\Seeder;

class SetorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setores = [
            ['nm_setor' => 'Faturamento'],
            ['nm_setor' => 'Financeiro'],
            ['nm_setor' => 'Cobrança'],
            ['nm_setor' => 'Tecnologia da Informação'],
            ['nm_setor' => 'Recursos Humanos'],
            ['nm_setor' => 'Controladoria'],
            ['nm_setor' => 'Compras']
        ];

        foreach ($setores as $s) {
            Setor::insert([
                'nm_setor' => $s['nm_setor'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
