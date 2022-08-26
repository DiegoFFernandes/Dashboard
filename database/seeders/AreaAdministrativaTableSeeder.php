<?php

namespace Database\Seeders;

use App\Models\AreaAdministrativa;
use Illuminate\Database\Seeder;

class AreaAdministrativaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $area = [
            ['nm_area' => 'Produção'],
            ['nm_area' => 'Administrativo'],
            ['nm_area' => 'Financeiro'],
            ['nm_area' => 'Recursos Humanos'],
            ['nm_area' => 'Sistema de Gestão'],
            ['nm_area' => 'Comercial'],
            ['nm_area' => 'Controladoria']
        ];

        foreach ($area as $a) {
            AreaAdministrativa::insert([
                'nm_area' => $a['nm_area'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
