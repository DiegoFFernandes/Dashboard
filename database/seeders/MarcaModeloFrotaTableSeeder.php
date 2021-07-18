<?php

namespace Database\Seeders;

use App\Models\MarcaModeloFrota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcaModeloFrotaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('motorista_veiculos')->delete();
        DB::table('marca_modelo_frotas')->delete();

        $veiculos = [
            [ 'cd_marca' => 1,'cd_modelo' => 1,'cd_frota' => 2, 'cd_usuario' => 1 ],
            [ 'cd_marca' => 1,'cd_modelo' => 2,'cd_frota' => 2, 'cd_usuario' => 1 ],
            [ 'cd_marca' => 2,'cd_modelo' => 3,'cd_frota' => 2, 'cd_usuario' => 1 ],
            [ 'cd_marca' => 3,'cd_modelo' => 4,'cd_frota' => 2, 'cd_usuario' => 1 ],
            [ 'cd_marca' => 4,'cd_modelo' => 5,'cd_frota' => 1, 'cd_usuario' => 1 ],
            [ 'cd_marca' => 5,'cd_modelo' => 6,'cd_frota' => 1, 'cd_usuario' => 1 ],
            [ 'cd_marca' => 3,'cd_modelo' => 7,'cd_frota' => 2, 'cd_usuario' => 1 ],
            [ 'cd_marca' => 3,'cd_modelo' => 8,'cd_frota' => 2, 'cd_usuario' => 1 ],
        ];

        foreach($veiculos as $v){
            MarcaModeloFrota::create($v);
        }

    }
}
