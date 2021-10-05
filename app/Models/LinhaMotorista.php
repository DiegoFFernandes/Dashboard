<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LinhaMotorista extends Model
{
    use HasFactory;

    protected $fillable = [
        'cd_empresa',
        'linha',
        'ativa',
    ];

    public function linhaAll()
    {
        $empresa = Auth::user()->empresa;
        return LinhaMotorista::select('id', 'linha', 'cd_empresa')
            ->where('cd_empresa', $empresa)
            ->where('ativa', 'S')
            ->get();
    }
}
