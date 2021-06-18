<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotoristaVeiculo extends Model
{
    use HasFactory;
    protected $table = 'motorista_veiculos';
    protected $fillable = [
        'cd_empresa',
        'cd_pessoa',
        'placa',
        'cor',
        'cd_marca',
        'cd_modelo',        
        'ano',
        'cd_tipoveiculo',        
        'ativo',
        'cd_usuario'
    ];
}
