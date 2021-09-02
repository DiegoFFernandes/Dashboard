<?php

namespace Database\Seeders;

use App\Models\Pessoa;
use Illuminate\Database\Seeder;

class PessoaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pessoas = [
            ['cd_empresa' => 1, 'name' => 'Diego Ferreira Fernandes', 'cpf' => '07895271978', 'cd_email' => '1', 'endereco' => 'rua 1', 'numero' => '75', 'phone' => '041985227055', 'cd_usuario' => 1],
            ['cd_empresa' => 2, 'name' => 'Rafael Henrique', 'cpf' => '07895271971', 'cd_email' => '3', 'endereco' => 'rua 1', 'numero' => '75', 'phone' => '041985227055', 'cd_usuario' => 1],
            ['cd_empresa' => 1, 'name' => 'Silvio Lima', 'cpf' => '07895271975', 'cd_email' => '2', 'endereco' => 'rua 1', 'numero' => '75', 'phone' => '041985227055', 'cd_usuario' => 1]
        ];

        foreach($pessoas as $p){
            Pessoa::create($p);
        }
    }
}
