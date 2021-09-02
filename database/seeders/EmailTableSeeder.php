<?php

namespace Database\Seeders;

use App\Models\Email;
use Illuminate\Database\Seeder;

class EmailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = [
            ['email' => 'ti.campina@ivorecap.com.br', 'cd_usuario' => 1 ],
            ['email' => 'silvio.lima@ivorecap.com.br', 'cd_usuario' => 1],
            ['email' => 'rafael.henrique@ivorecap.com.br', 'cd_usuario' => 1]
        ];

        foreach ($email as $m) {
            Email::create($m);
           };

    }
}
