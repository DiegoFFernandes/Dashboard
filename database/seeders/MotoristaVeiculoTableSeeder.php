<?php

namespace Database\Seeders;

use App\Models\MotoristaVeiculo;
use Illuminate\Database\Seeder;

class MotoristaVeiculoTableSeeder extends Seeder
{
 /**
  * Run the database seeds.
  *
  * @return void
  */
 public function run()
 {
  MotoristaVeiculo::create([
   'cd_empresa'        => 1,
   'cd_pessoa'      => 1,
   'placa'          => 'ATB8320',
   'cor'            => 'BRANCO',
   'cd_marca'       => '2',
   'cd_modelo'      => '1',
   'ano'            => 2011,
   'cd_tipoveiculo' => 3,
   'ativo'          => 'S',
   'cd_usuario'     => 1,
  ]);
 }
}
