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
   'name'     => 'Diego Ferreira',
   'email'    => 'ti.campina@ivorecap.com.br',
   'empresa'  => 1,
   'password' => bcrypt('admin'),
  ]);
  $user->assignRole('admin');
  
  User::create([
   'name'     => 'Portaria',
   'email'    => 'portaria@ivorecap.com.br',
   'empresa'  => 1,
   'password' => bcrypt('1234'),
  ]);
 }
}
