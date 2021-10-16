<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'     => ' ',
            'email'    => 'usuario@ivorecap.com.br',
            'empresa'  => 1,
            'password' => bcrypt('@@ivo@2021//'),
            'conexao'  => 'firebird_campina'
        ]);
        $user->assignRole('usuario');


        $user = User::create([
            'name'     => 'Diego Ferreira',
            'email'    => 'ti.campina@ivorecap.com.br',
            'empresa'  => 1,
            'password' => bcrypt('admin'),
            'conexao'  => 'firebird_campina'
        ]);
        $user->assignRole('admin');

        $users = [
            [
                'name' => 'Evandro', 'email' => 'ti.paranavai@ivorecap.com.br',
                'empresa' => 1, 'password' => bcrypt('admin'), 'conexao' => 'firebird_paranavai'
            ],
            [
                'name' => 'Portaria', 'email' => 'portaria@ivorecap.com.br',
                'empresa'  => 1, 'password' => bcrypt('1234'), 'conexao' => 'firebird_campina'
            ],
        ];

        foreach ($users as $u) {
            User::create($u);
        }
    }
}
