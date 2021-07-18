<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloVeiculo extends Model
{
    use HasFactory;
    protected $table = 'modeloveiculos';
    protected $fillable = [
        'id',        
        'descricao',
        'cd_usuario'
    ];

    public function modeloAll()
    {
       return ModeloVeiculo::all();
    }
}
