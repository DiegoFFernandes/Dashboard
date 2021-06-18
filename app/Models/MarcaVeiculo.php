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
        'cd_marca',
        'descricao',
        'cd_tipoveiculo'        
    ];
}
