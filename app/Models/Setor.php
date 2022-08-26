<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    public function listData()
    {
        return Setor::join('area_administrativas', 'area_administrativas.id', 'setors.id_area_adm')
        ->orderBy('setors.nm_setor')
        ->get();
    }
}
