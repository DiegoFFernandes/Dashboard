<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisEtapasExecutores extends Model
{
    use HasFactory;

    public function store($executor, $etapa, $epi, $uso){
        return EpisEtapasExecutores::insert([
            'id_executor' => $executor,
            'id_etapa' => $etapa,
            'id_epi' => $epi,
            'uso' => $uso,          
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
