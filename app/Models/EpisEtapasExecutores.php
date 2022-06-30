<?php

namespace App\Models;

use App\Http\Controllers\Admin\Producao\EpisController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EpisEtapasExecutores extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d/m/Y'
    ];

    public function store($executor, $etapa, $epi, $uso)
    {
        return EpisEtapasExecutores::insert([
            'id_executor' => $executor,
            'id_etapa' => $etapa,
            'id_epi' => $epi,
            'uso' => $uso,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    public function UsoEpi($etapa, $executor, $epi, $uso, $data_ini, $data_fim)
    {
        return EpisEtapasExecutores::select([DB::raw('epis_etapas_executores.id, executoretapas.nmexecutor, 
        epis.ds_epi, etapasproducaopneus.dsetapaempresa, 
        case when epis_etapas_executores.uso = "CF" then "CONFORME" ELSE "NÃO CONFORME" end uso, epis_etapas_executores.created_at')])
            ->join('epis', 'epis.id', 'epis_etapas_executores.id_epi')
            ->join('etapasproducaopneus', 'etapasproducaopneus.id', 'epis_etapas_executores.id_etapa')
            ->join('executoretapas', 'executoretapas.id', 'epis_etapas_executores.id_executor')
            ->when($etapa == 0, function ($q) {
                return;
            }, function ($q) use ($etapa) {
                return $q->where('id_etapa', $etapa);
            })
            ->when($executor == 0, function ($q) {
                return;
            }, function ($q) use ($executor) {
                return $q->where('id_executor', $executor);
            })
            ->when($epi == 0, function ($q) {
                return;
            }, function ($q) use ($epi) {
                return $q->where('id_epi', $epi);
            })
            ->when($uso == '0', function ($q) {
                return;
            }, function ($q) use ($uso) {
                return $q->where('uso', $uso);
            })
            ->when($data_ini == '0' , function ($q) {
                return;
            }, function ($q) use ($data_ini, $data_fim) {
                return $q->whereBetween('epis_etapas_executores.created_at', [$data_ini, $data_fim]);
            })
            ->get();
    }
}
