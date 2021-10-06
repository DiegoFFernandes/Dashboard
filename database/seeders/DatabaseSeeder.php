<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(LinhaMotoristaTableSeeder::class);
        $this->call(FrotaVeiculosSeeder::class);
        $this->call(EmailTableSeeder::class);
        $this->call(TipoVeiculoTableSeeder::class);
        $this->call(MarcaVeiculoTableSeeder::class);
        $this->call(ModeloVeiculoTableSeeder::class);
        $this->call(PessoaTableSeeder::class);     
        $this->call(MarcaModeloFrotaTableSeeder::class); 
        $this->call(MotoristaVeiculoTableSeeder::class);       
        
    }
}
