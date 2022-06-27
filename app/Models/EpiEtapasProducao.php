<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpiEtapasProducao extends Model
{
    use HasFactory;
    protected $table = 'epis_etapaproducaopneus';

    public function SearchEpisEtapas($id_etapa){
        return EpiEtapasProducao::
        join('etapasproducaopneus', 'etapasproducaopneus.id', 'epis_etapaproducaopneus.id_etapaproducao')
        ->join('epis', 'epis.id', 'epis_etapaproducaopneus.id_epi' )
        ->where('id_etapaproducao', $id_etapa)->get();
    }
}
