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
        'cd_frotaveiculos',  
        'cd_usuario'     
    ];

    public function marcaAll(){
       return MarcaVeiculo::select('marcaveiculos.id', 'marcaveiculos.cd_marca', 'marcaveiculos.descricao as marca', 'cd_frotaveiculos', 'frotaveiculos.descricao as frota')
       ->join('frotaveiculos', 'frotaveiculos.id', '=', 'marcaveiculos.cd_frotaveiculos')
       ->orderBy('frotaveiculos.descricao')
       ->get();
    }
}
