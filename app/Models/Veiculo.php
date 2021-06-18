<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;
    protected $table = 'veiculos';
    protected $fillable = [
        'cd_pessoa',
        'placa',
        'cor',
        'cd_marca',
        'cd_modelo',
        'ano',
        'cd_frota',
        'cd_classeveiculo',
        'ativo',
        'cd_usuario'
    ];

    
}
