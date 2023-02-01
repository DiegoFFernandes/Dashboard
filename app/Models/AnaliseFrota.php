<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AnaliseFrota extends Model
{
    use HasFactory;

    protected $fillable = [
        'cd_pessoa',
        'nm_pessoa',
        'placa',
        'marca_modelo',
        'sulco',
        'ps_min',
        'ps_max',
        'observacao'
    ];

    public function storeData($input)
    {
        return AnaliseFrota::create([
            'cd_pessoa' => $input['pessoa'],
            'nm_pessoa' => $input['nm_pessoa'],
            'placa' => $input['placa'],
            'marca_modelo' => $input['modelo_veiculo'],
            'sulco' => $input['sulco'],
            'ps_min' => $input['ps_min'],
            'ps_max' => $input['ps_max'],
            'observacao' => $input['obs'],
        ]);
    }
    public function listData()
    {
        return AnaliseFrota::select(
            'analise_frotas.id',
            'analise_frotas.cd_pessoa',
            'analise_frotas.nm_pessoa',
            'analise_frotas.placa',
            DB::raw("CONCAT(ma.descricao,' - ', mo.descricao) as modelo"),
            'mo.descricao',
            'analise_frotas.sulco',
            'analise_frotas.ps_min',
            'analise_frotas.ps_max',
            'analise_frotas.observacao'
        )
            ->join('marca_modelo_frotas as mmf', 'mmf.id', 'analise_frotas.marca_modelo')
            ->join('marcaveiculos as ma', 'ma.id', 'mmf.cd_marca')
            ->join('modeloveiculos as mo', 'mo.id', 'mmf.cd_modelo')
            ->get();
    }
    public function DestroyData($id){
        return AnaliseFrota::find($id)->delete();
    }
}
