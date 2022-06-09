<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa = [
            ['cd_empresa' => 1, 'ds_local' => 'RENOVAT', 'cd_loja' => 1, 'cd_grupo' => 1, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 2, 'ds_local' => 'CAMPINA', 'cd_loja' => 9, 'cd_grupo' => 3, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 3, 'ds_local' => 'CAMPINA', 'cd_loja' => 1, 'cd_grupo' => 3, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 4, 'ds_local' => 'GUARAPAUAVA', 'cd_loja' => 1, 'cd_grupo' => 4, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 12, 'ds_local' => 'RENOVAT', 'cd_loja' => 9, 'cd_grupo' => 1, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 21, 'ds_local' => 'CAMPO LARGO', 'cd_loja' => 1, 'cd_grupo' => 21, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 22, 'ds_local' => 'CAMPO LARGO', 'cd_loja' => 1, 'cd_grupo' => 21, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 42, 'ds_local' => 'GUARAPAUAVA', 'cd_loja' => 9, 'cd_grupo' => 4, 'regiao' => 'SUL', 'conexao' => 'firebird_campina'],
            ['cd_empresa' => 101, 'ds_local' => 'PARANAVAI', 'cd_loja' => 1, 'cd_grupo' => 101, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 102, 'ds_local' => 'DOURADOS', 'cd_loja' => 1, 'cd_grupo' => 102, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 103, 'ds_local' => 'APUCARANA', 'cd_loja' => 1, 'cd_grupo' => 103, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 104, 'ds_local' => 'ASSIS', 'cd_loja' => 1, 'cd_grupo' => 104, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 105, 'ds_local' => 'DOURADOS II', 'cd_loja' => 1, 'cd_grupo' => 105, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 201, 'ds_local' => 'PARANAVAI', 'cd_loja' => 9, 'cd_grupo' => 101, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 202, 'ds_local' => 'DOURADOS', 'cd_loja' => 9, 'cd_grupo' => 102, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 203, 'ds_local' => 'APUCARANA', 'cd_loja' => 9, 'cd_grupo' => 103, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 204, 'ds_local' => 'ASSIS', 'cd_loja' => 9, 'cd_grupo' => 104, 'regiao' => 'NORTE', 'conexao' => 'firebird_paranavai'],
            ['cd_empresa' => 1001, 'ds_local' => 'TRUCK CEASA', 'cd_loja' => 1, 'cd_grupo' => 1001, 'regiao' => 'TRUCK', 'firebird_campina'],
            ['cd_empresa' => 1002, 'ds_local' => 'TRUCK AREIA BRANCA', 'cd_loja' => 1, 'cd_grupo' => 1002, 'regiao' => 'TRUCK', 'firebird_campina'],
            ['cd_empresa' => 1003, 'ds_local' => 'TRUCK P. GROSSA', 'cd_loja' => 1, 'cd_grupo' => 1003, 'regiao' => 'TRUCK', 'firebird_campina'],
            ['cd_empresa' => 1005, 'ds_local' => 'TRUCK GUARAPUAVA', 'cd_loja' => 1, 'cd_grupo' => 1005, 'regiao' => 'TRUCK', 'firebird_campina'],
        ];

        foreach ($empresa as $e) {
            Empresa::insert([
            'cd_empresa' => $e['cd_empresa'],
            'ds_local' => $e['ds_local'],
            'cd_loja' => $e['cd_loja'],
            'cd_grupo' => $e['cd_grupo'],
            'regiao' => $e['regiao'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        }
    }
}
