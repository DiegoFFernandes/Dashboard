<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaVeiculo extends Model
{
    use HasFactory;
    protected $table = 'marcaveiculos';
    protected $fillable = [
        'id',         
        'descricao',         
        'cd_usuario'     
    ];

    public function marcaAll(){
       return MarcaVeiculo::all();
    }

    public function delete(){
        
    }

    
}
