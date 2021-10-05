<?php

namespace Database\Seeders;

use App\Models\LinhaMotorista;
use Illuminate\Database\Seeder;

class LinhaMotoristaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $linhas = [
            ['cd_empresa' => 1, 'linha' => 'Local', 'ativa' => 'S'],
            ['cd_empresa' => 1, 'linha' => 'Ponta Grossa', 'ativa' => 'S'],
            ['cd_empresa' => 1, 'linha' => 'Ceasa', 'ativa' => 'S'],
            ['cd_empresa' => 1, 'linha' => 'Rio de Janeiro', 'ativa' => 'S'],
            ['cd_empresa' => 1, 'linha' => 'São Paulo', 'ativa' => 'S'],
            ['cd_empresa' => 1, 'linha' => 'Palhoça', 'ativa' => 'S'],
        ];


        foreach($linhas as $l){
            LinhaMotorista::create($l);
        }
    }
        
                    
    
}
