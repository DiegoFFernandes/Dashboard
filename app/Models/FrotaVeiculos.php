<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrotaVeiculos extends Model
{
    use HasFactory;

    protected $table = 'frotaveiculos';
    protected $fillable = [
        'id',
        'descricao',
        'cd_usuario'
    ];

    public function frotaAll(){
        return FrotaVeiculos::select('id', 'descricao', 'created_at')->get();
    }
}
