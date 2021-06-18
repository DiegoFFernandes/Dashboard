<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasseVeiculo extends Model
{
    use HasFactory;
    protected $table = 'classeveiculos';
    protected $fillable = [
        'descricao'
    ];
}
