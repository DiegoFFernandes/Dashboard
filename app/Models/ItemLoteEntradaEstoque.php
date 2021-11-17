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
    public function list($id)
    {
        return ItemLoteEntradaEstoque::select('item_lote_entrada_estoques.id', 'item_lote_entrada_estoques.cd_produto', 'itens.ds_item', 
        'item_lote_entrada_estoques.peso', 'item_lote_entrada_estoques.created_at')
        ->join('itens', 'itens.cd_item', 'item_lote_entrada_estoques.cd_produto')
        ->where('cd_lote', $id)->get();
    }
    public function listGroup($id){
        return ItemLoteEntradaEstoque::select('item_lote_entrada_estoques.cd_produto', 'itens.ds_item',
        DB::raw('sum(item_lote_entrada_estoques.peso) peso'))
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
    public function destroyData($id){
        ItemLoteEntradaEstoque::find($id)->delete();
        return response()->json(['success' => 'Item excluido com sucesso!']);
    }
}
