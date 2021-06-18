<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloVeiculo extends Model
{
    use HasFactory;
    protected $table = 'modeloveiculos';
    protected $fillable = [
        'cd_marca',
        'cd_frota',
        'descricao'
    ];
}
