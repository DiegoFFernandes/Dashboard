<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubgrupoCentroResultado extends Model
{
    use HasFactory;
    protected $table = 'subgrupo_centro_resultado';

    protected $fillable = [
        'ds_subgrupo',
        'cd_grupo',
        'ds_tipo',
        'cd_dre'
    ];

    public function listSubgrupoCentroResultado()
    {
        return SubgrupoCentroResultado::select(
            'subgrupo_centro_resultado.id',
            'subgrupo_centro_resultado.ds_subgrupo as subgrupo',
            DB::raw("concat(subgrupo_centro_resultado.ds_subgrupo,' - ',g.ds_grupo) as ds_subgrupo"),
            'subgrupo_centro_resultado.cd_grupo',
            'g.ds_grupo',
            'subgrupo_centro_resultado.ds_tipo',
            'subgrupo_centro_resultado.cd_dre'
        )
            ->join('grupo_centro_resultado as g', 'g.id', 'subgrupo_centro_resultado.cd_grupo')->get();
    }
    public function listDsTipo()
    {
        return SubgrupoCentroResultado::select('ds_tipo')->groupBy('ds_tipo')->get();
    }

    public function StoreSubGrupoCentroResultado($input)
    {

        $subgrupo = new SubgrupoCentroResultado;

        return $subgrupo::firstOrCreate(
            [
                'ds_subgrupo' => $input['ds_subgrupo'],
                'cd_grupo' => $input['cd_grupo'],
                'ds_tipo' => $input['ds_tipo']
            ],
            [
                'ds_subgrupo' => $input['ds_subgrupo'],
                'cd_grupo' => $input['cd_grupo'],
                'ds_tipo' => $input['ds_tipo']
            ]
        );
    }
    public function UpdateSubGrupoCentroResultado($input){
        return SubgrupoCentroResultado::find($input['id'])->update(
            [
                'ds_subgrupo' => $input['ds_subgrupo'],
                'cd_grupo' => $input['cd_grupo'],
                'ds_tipo' => $input['ds_tipo']
            ]
        );
    }
    
}
