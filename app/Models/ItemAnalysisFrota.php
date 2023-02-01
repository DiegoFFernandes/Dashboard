<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAnalysisFrota extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_analise',
        'id_posicao',
        'id_motivo_pneu',
        'fogo',
        'id_modelo',
        'ds_modelo',
        'id_medida',
        'ds_medida',
        'pressao',
        'sulco',
        'created_at',
        'updated_at'
    ];
    public function listAll()
    {
        return ItemAnalysisFrota::select(
            'item_analysis_frotas.id', 
            'item_analysis_frotas.id_analise', 
            'item_analysis_frotas.id_posicao', 
            'pp.ds_posicao',
            'item_analysis_frotas.id_motivo_pneu', 
            'mp.ds_motivo',
            'item_analysis_frotas.fogo',
            'item_analysis_frotas.id_modelo',
            'item_analysis_frotas.ds_modelo',
            'item_analysis_frotas.id_medida',
            'item_analysis_frotas.ds_medida',
            'item_analysis_frotas.pressao',
            'af.ps_min', 
            'af.ps_max',
            'af.sulco as sulco_ideal',
            'item_analysis_frotas.sulco')
            ->join('analise_frotas as af', 'af.id', 'item_analysis_frotas.id_analise')
            ->join('posicao_pneus as pp', 'item_analysis_frotas.id_posicao', 'pp.id')
            ->join('motivo_pneus as mp', 'mp.id', 'item_analysis_frotas.id_motivo_pneu')
            ->get();
            
            
            
            
    }
    public function storeData($input)
    {
        try {
            $item = new ItemAnalysisFrota;
            $item->id_analise = $input['id'];
            $item->id_posicao = $input['posicao'];
            $item->id_motivo_pneu = $input['motivo'];
            $item->fogo = $input['fogo'];
            $item->id_modelo = $input['modelo'];
            $item->ds_modelo = $input['ds_modelo'];
            $item->id_medida = $input['id_medida'];
            $item->ds_medida = $input['ds_medida'];
            $item->pressao = $input['pressao'];
            $item->sulco = $input['sulco'];
            $item->created_at = now();
            $item->updated_at = now();
            $item->save();

            return $item;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public function destroyData($input){
        try {
            return ItemAnalysisFrota::find($input)->delete();
        } catch (\Throwable $th) {
            return 0;
        }
        
    }
}
