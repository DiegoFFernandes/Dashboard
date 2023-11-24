<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Sgi extends Model
{
    use HasFactory;
    protected $fillable = [
        'cd_empresa',
        'title',
        'description',
        'path',
        'dt_validade',
        'status',
        'id_user_create'
    ];
    public function getDtValidadeAttribute($value)
    {
        // Usando o Carbon para formatar a data
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function storeData($input, $file)
    {
        $sgi = new Sgi();
        return $sgi::create([
            'cd_empresa' => $input['unidade'],
            'title' => $input['title'],
            'description' => $input['description'],
            'path' => $file['file']->store('sgi'),
            'dt_validade' => $input['dt_validade'],
            'status' => 'A',
            'id_user_create' => Auth::user()->id
        ]);
    }
    public function listData($status, $unidade)
    {
        return Sgi::select(
            'sgis.id',
            'sgis.cd_empresa',
            DB::raw("CONCAT(empresas_grupo.ds_local, ' - ', empresas_grupo.regiao) as ds_local"),
            // 'empresas_grupo.ds_local',
            'sgis.title',
            'sgis.description',
            'sgis.path',
            'sgis.dt_validade',
            'sgis.id_user_create',
            'users.name',
            DB::raw("CASE sgis.status 
                                WHEN 'A' THEN 'Aguardando Publicar'
                                WHEN 'P' THEN 'Publico' 
                            END status"),
            'sgis.created_at as criado',
            'sgis.status as public'
        )
            ->join('empresas_grupo', 'empresas_grupo.cd_empresa_new', 'sgis.cd_empresa')
            ->join('users', 'users.id', 'sgis.id_user_create')
            ->when($status == 'pub', function ($q) use ($unidade) {
                if ($unidade == 'all') {
                    return $q->where('sgis.status', 'P');
                } else {
                    return $q->where('sgis.status', 'P')
                        ->where('sgis.cd_empresa', $unidade);
                }
            }, function ($q) use ($status) {
                // return $q->whereIn('sgis.status', 'A');
            })
            ->get();
    }

    public function countSgis()
    {
        return Sgi::select('sgis.cd_empresa', 'e.ds_local', DB::raw('count(*) as qtd'))
            ->join('empresas_grupo as e', 'e.cd_empresa_new', 'sgis.cd_empresa')
            // ->join('procedimento_public as p', 'p.id_procedimento', 'procedimentos.id')
            ->where('sgis.status', 'P')
            ->groupBy('sgis.cd_empresa', 'e.ds_local')
            ->orderBy('e.ds_local')
            ->get();
    }
}
