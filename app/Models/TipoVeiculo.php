<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVeiculo extends Model
{
    use HasFactory;
    
    protected $table = 'tipoveiculo';
    protected $fillable = [
        'id',
        'descricao'
    ];

    public function TipoVeiculoAll(){
        return TipoVeiculo::select('id', 'descricao')->get();
    }
}
