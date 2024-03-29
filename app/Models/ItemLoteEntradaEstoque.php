<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ItemLoteEntradaEstoque extends Model
{
    use HasFactory;
    protected $fillable = [
        'cd_lote',
        'cd_produto',
        'peso',
        'cd_usuario'
    ];
    // protected $casts = [
    //     'created_at' => 'datetime:d/m/Y H:m:s'

    // ];
    protected function serializeDate($date)
    {
        return $date->format('d/m/Y H:i:s');
    }

    public function list($id)
    {
        return ItemLoteEntradaEstoque::select(
            'item_lote_entrada_estoques.id',
            'item_lote_entrada_estoques.cd_produto',
            'itens.ds_item',
            'item_lote_entrada_estoques.peso',
            'itens.ps_liquido',
            'users.name',
            'item_lote_entrada_estoques.created_at'
        )
            ->join('itens', 'itens.cd_item', 'item_lote_entrada_estoques.cd_produto')
            ->join('users', 'users.id', 'item_lote_entrada_estoques.cd_usuario')
            ->where('cd_lote', $id)->get();
    }
    public function listGroup($id)
    {
        return ItemLoteEntradaEstoque::select(
            'item_lote_entrada_estoques.cd_produto',
            'itens.ds_item',
            DB::raw('count(*) qtditem, ROUND(sum(item_lote_entrada_estoques.peso),2) peso')
        )
            ->join('itens', 'itens.cd_item', 'item_lote_entrada_estoques.cd_produto')
            ->where('cd_lote', $id)
            ->groupBy('item_lote_entrada_estoques.cd_produto', 'itens.ds_item')
            ->get();
    }
    public function store($input)
    {
        try {
            ItemLoteEntradaEstoque::create([
                'cd_lote' => $input['cd_lote'],
                'cd_produto' => $input['cd_produto'],
                'peso' => $input['peso'],
                'cd_usuario' => $input['cd_usuario']
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return 0;
        }
        return 1;
    }
    public function destroyData($id)
    {
        ItemLoteEntradaEstoque::find($id)->delete();
        return response()->json(['success' => 'Item excluido com sucesso!']);
    }
    public function countData($cd_lote)
    {
        return ItemLoteEntradaEstoque::select(DB::raw('sum(peso) as peso, count(*) as qtd'))
        ->where('cd_lote', $cd_lote)->get();
    }
}
