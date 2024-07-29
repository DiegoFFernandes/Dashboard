<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemCentroResultado extends Model
{
    use HasFactory;
    protected $table = 'item_centro_resultado';

    protected $fillable = [
        'id',
        'cd_empresa_desp',
        'cd_empresa_lanc',
        'cd_centroresultado',
        'ds_centroresultado',
        'cd_subgrupo',
        'orcamento',
        'alterado',
        'created_at',
        'updated_at'
    ];

    public function listCentroResultado()
    {
        return ItemCentroResultado::select(
            'item_centro_resultado.id',
            'item_centro_resultado.cd_empresa_desp',
            'item_centro_resultado.cd_centroresultado',
            'item_centro_resultado.ds_centroresultado',
            'item_centro_resultado.cd_subgrupo',  
            DB::raw("concat(s.ds_subgrupo,' - ', g.ds_grupo) as ds_subgrupo"),
            DB::raw("case when item_centro_resultado.alterado = 'S' then 'SIM' else 'NÃO' end alterado"),
            's.ds_tipo',
            'item_centro_resultado.orcamento',
            's.cd_grupo',
            'g.ds_grupo'
        )
            ->join('subgrupo_centro_resultado as s', 's.id', 'item_centro_resultado.cd_subgrupo')
            ->join('grupo_centro_resultado as g', 'g.id', 's.cd_grupo')
            // ->where('item_centro_resultado.cd_centroresultado', 104010007)
            ->get();
    }
    public function ListCentroResultadoJunsoft($input)
    {
        $query = "
                SELECT
                    CC.CD_EMPRESA,
                    CC.CD_CENTROCUSTO,
                    CC.DS_CENTROCUSTO
                FROM CENTROCUSTO CC
                where CC.CD_EMPRESA NOT IN (1)
                   " . ($input == 0 ? "AND CC.DT_REGISTRO >= CURRENT_DATE - 30" : "") . "  
                   --AND CC.CD_CENTROCUSTO = 1010299999                        
                GROUP BY CC.CD_EMPRESA,
                    CC.CD_CENTROCUSTO,
                    CC.DS_CENTROCUSTO";

        $results = DB::connection('firebird_rede')->select($query);

        // Garantir que os dados estejam em UTF-8
        $results = array_map(function ($result) {
            return array_map(function ($value) {
                return mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            }, (array) $result);
        }, $results);

        return $this->StoreCentroResultado($results);
    }

    public function StoreCentroResultado($input)
    {
        foreach ($input as $i) {
            try {
                $record = ItemCentroResultado::firstOrCreate(
                    [
                        'cd_centroresultado' => $i['CD_CENTROCUSTO']
                    ],
                    [
                        'cd_empresa_desp' => $i['CD_EMPRESA'],
                        'cd_centroresultado' => $i['CD_CENTROCUSTO'],
                        'ds_centroresultado' => $i['DS_CENTROCUSTO'],
                        'cd_subgrupo' => 0,
                        'orcamento' => 0,
                        'alterado' => 'N',
                        "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                    ]
                );

                if (!$record->wasRecentlyCreates) {
                    $record->update([
                        'alterado' => 'S',
                        'ds_centroresultado' => $i['DS_CENTROCUSTO'],
                        "updated_at"    => \Carbon\Carbon::now(),
                    ]);
                }
            } catch (Exception $e) {
                return throw $e;
            }
        }
        return $record;
    }
    public function updateItemCentroResultado($input)
    {
        try {
            return ItemCentroResultado::where('cd_centroresultado', $input['cd_centroresultado'])->update([
                'cd_empresa_desp' => $input['cd_empresa_desp'],
                'cd_centroresultado' => $input['cd_centroresultado'],
                'cd_subgrupo' => $input['cd_subgrupo'],
                'orcamento' => $input['orcamento'],
                'alterado' => 'S',
                "updated_at" => \Carbon\Carbon::now(), # new \Datetime()
            ]);
        } catch (\Throwable $th) {
            return $th;
        }        
    }
    public function destroyItemCentroResultado($input){
        return ItemCentroResultado::where('cd_centroresultado', $input['cd_centroresultado'])->delete();
    }

}
