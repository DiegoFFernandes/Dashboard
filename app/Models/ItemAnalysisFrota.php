<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemAnalysisFrota extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_analise',
        'id_posicao',
        'id_motivo_pneu',
        'fogo',
        'dot',
        'id_modelo',
        'ds_modelo',
        'id_medida',
        'ds_medida',
        'pressao',
        'sulco',
        'id_user',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'created_at'  => 'date:d-m-Y',
        
    ];
    public function listAll($id)
    {
        return ItemAnalysisFrota::select(
            'af.nm_pessoa',
            'af.placa',
            'item_analysis_frotas.id',
            'item_analysis_frotas.id_analise',
            'item_analysis_frotas.id_posicao',
            'pp.ds_posicao',
            'item_analysis_frotas.id_motivo_pneu',
            'mp.ds_motivo',
            'item_analysis_frotas.fogo',
            'item_analysis_frotas.dot',
            'item_analysis_frotas.id_modelo',
            'item_analysis_frotas.ds_modelo',
            'item_analysis_frotas.id_medida',
            'item_analysis_frotas.ds_medida',
            'item_analysis_frotas.pressao',
            'af.ps_min',
            'af.ps_max',
            'af.sulco as sulco_ideal',
            'item_analysis_frotas.sulco'
        )
            ->join('analise_frotas as af', 'af.id', 'item_analysis_frotas.id_analise')
            ->join('posicao_pneus as pp', 'item_analysis_frotas.id_posicao', 'pp.id')
            ->join('motivo_pneus as mp', 'mp.id', 'item_analysis_frotas.id_motivo_pneu')            
            ->where('id_analise', $id)
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
            $item->dot = $input['dot'];
            $item->id_modelo = $input['modelo'];
            $item->ds_modelo = $input['ds_modelo'];
            $item->id_medida = $input['id_medida'];
            $item->ds_medida = $input['ds_medida'];
            $item->pressao = $input['pressao'];
            $item->sulco = $input['sulco'];
            $item->id_user = Auth::user()->id;
            $item->created_at = now();
            $item->updated_at = now();
            $item->save();

            return $item;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function destroyData($input)
    {
        try {
            return ItemAnalysisFrota::find($input)->delete();
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function updateData($input)
    {
        ItemAnalysisFrota::find($input['id_item'])->update([
            'id_posicao' => $input['posicao'],
            'id_motivo_pneu' => $input['motivo'],
            'fogo' => $input['fogo'],
            'dot' => $input['dot'],
            'id_modelo' => $input['modelo'],
            'ds_modelo' => $input['ds_modelo'],
            'id_medida' => $input['id_medida'],
            'ds_medida' => $input['ds_medida'],
            'pressao' => $input['pressao'],
            'sulco' => $input['sulco'],
            'id_user' => Auth::user()->id,
            'updated_at' => now(),
        ]);
    }
    public function listAllPrint($id)
    {
        return ItemAnalysisFrota::select(
            'af.nm_pessoa',
            'af.placa',
            'item_analysis_frotas.id',
            'item_analysis_frotas.id_analise',
            'item_analysis_frotas.id_posicao',
            'pp.ds_posicao',
            'item_analysis_frotas.id_motivo_pneu',
            'mp.ds_motivo',
            'mp.ds_causa',
            'mp.ds_recomendacoes',
            'item_analysis_frotas.fogo',
            'item_analysis_frotas.dot',
            'item_analysis_frotas.id_modelo',
            'item_analysis_frotas.ds_modelo',
            'item_analysis_frotas.id_medida',
            'item_analysis_frotas.ds_medida',
            'item_analysis_frotas.pressao',
            'af.ps_min',
            'af.ps_max',
            'af.sulco as sulco_ideal',
            'item_analysis_frotas.sulco',
            'pi.path',
            'users.name',
            'item_analysis_frotas.created_at',
        )
            ->join('analise_frotas as af', 'af.id', 'item_analysis_frotas.id_analise')
            ->join('posicao_pneus as pp', 'item_analysis_frotas.id_posicao', 'pp.id')
            ->join('motivo_pneus as mp', 'mp.id', 'item_analysis_frotas.id_motivo_pneu')  
            ->leftJoin('picture_analysis_frotas as pi', 'pi.id_item_analysis', 'item_analysis_frotas.id')  
            ->join('users', 'users.id', 'item_analysis_frotas.id_user')        
            ->where('id_analise', $id)
            ->get();
    }
}
