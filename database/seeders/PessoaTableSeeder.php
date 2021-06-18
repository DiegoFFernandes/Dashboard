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
        Pessoa::create([
            'name' => 'Diego Ferreira Fernandes'
        ]);
        Pessoa::create([
            'name' => 'Rafael Henrique'
        ]);
        Pessoa::create([
            'name' => 'Silvio Lima'
        ]);
    }    
}
