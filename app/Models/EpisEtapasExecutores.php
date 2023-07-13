<?php

namespace App\Models;

use App\Http\Controllers\Admin\Producao\EpisController;
use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
    public function UsoEpi($etapa, $executor, $epi, $uso, $rede, $data_ini, $data_fim, $empresa)
    {
        return EpisEtapasExecutores::select([DB::raw('e.ds_local, epis_etapas_executores.id, executoretapas.nmexecutor, 
        epis.ds_epi, etapasproducaopneus.dsetapaempresa, 
        case when epis_etapas_executores.uso = "CF" then "CONFORME" ELSE "NÃO CONFORME" end uso, executoretapas.localizacao, epis_etapas_executores.created_at')])
            ->join('epis', 'epis.id', 'epis_etapas_executores.id_epi')
            ->join('etapasproducaopneus', 'etapasproducaopneus.id', 'epis_etapas_executores.id_etapa')
            ->join('executoretapas', 'executoretapas.id', 'epis_etapas_executores.id_executor')
            ->Leftjoin('empresas_grupo as e', 'e.cd_empresa', 'executoretapas.cd_empresa')
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
            ->when($empresa == 0, function ($q) {
                return;
            }, function ($q) use ($empresa) {
                return $q->where('e.cd_empresa', $empresa);
            })
            ->when($uso == '0', function ($q) {
                return;
            }, function ($q) use ($uso) {
                return $q->where('uso', $uso);
            })
            ->when($rede == '0', function ($q) {
                // return $q->where('executoretapas.localizacao', Helper::VerifyRegion(Auth::user()->conexao));
                return;
            }, function ($q) use ($rede) {
                return $q->where('executoretapas.localizacao', $rede);
            })
            ->when($data_ini == '0' , function ($q) {
                return;
            }, function ($q) use ($data_ini, $data_fim) {
                return $q->whereBetween('epis_etapas_executores.created_at', [$data_ini, $data_fim]);
            })
            ->get();
    }
    public function VerifyIfExists($id_executor, $etapa, $data){
        return EpisEtapasExecutores::join('executoretapas', 'executoretapas.id', 'epis_etapas_executores.id_executor')
        ->where('epis_etapas_executores.id_executor', $id_executor)
        ->where('epis_etapas_executores.id_etapa', $etapa)
        ->whereDate('epis_etapas_executores.created_at', $data)
        ->where('executoretapas.localizacao','SUL')
        ->exists();
    }
}
