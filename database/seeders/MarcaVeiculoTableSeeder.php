<?php

namespace Database\Seeders;

use App\Models\MarcaVeiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcaVeiculoTableSeeder extends Seeder
{
 /**
  * Run the database seeds.
  *
  * @return void
  */
 public function run()
 {
  DB::table('marca_modelo_frotas')->delete();
  DB::table('marcaveiculos')->delete();

  $marcas = [
   ['descricao' => 'VOLKSWAGEN', 'cd_usuario' => '1'],
   ['descricao' => 'FIAT', 'cd_usuario' => '1'],
   ['descricao' => 'CHEVROLET', 'cd_usuario' => '1'],
   ['descricao' => 'FORD', 'cd_usuario' => '1'],
   ['descricao' => 'IVECO', 'cd_usuario' => '1'],
  ];

  foreach ($marcas as $m) {
   MarcaVeiculo::create($m);
  };

 }
}
