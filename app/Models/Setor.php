<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;

    public function listData()
    {
        return Setor::select('setors.id', 'setors.nm_setor', 'area_administrativas.nm_area')
        ->join('area_administrativas', 'area_administrativas.id', 'setors.id_area_adm')
        ->orderBy('setors.nm_setor')
        ->get();
    }
}
