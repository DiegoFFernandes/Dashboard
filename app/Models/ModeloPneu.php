<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloPneu extends Model
{
    use HasFactory;
   

    public function list(){
       return ModeloPneu::all();
    }
}
