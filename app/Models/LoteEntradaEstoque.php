<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoteEntradaEstoque extends Model
{
    use HasFactory;
    protected $fillable = [
        'cd_empresa',
        'descricao',
        'cd_usuario',
        'status',
        'tp_lote',
        'id_subgrupo',
        'id_marca'
    ];

    public $timestamps = true;
    public $table = 'lote_entrada_estoques';
    protected $connection;

    public function lotesAll()
    {
        return LoteEntradaEstoque::select(
            'lote_entrada_estoques.id',
            'lote_entrada_estoques.cd_empresa',
            'lote_entrada_estoques.descricao',
            DB::raw('count(i.id) as qtd_itens'),
            DB::raw('coalesce(sum(i.peso),0) as ps_liquido_total'),
            'lote_entrada_estoques.status',
            'lote_entrada_estoques.tp_lote',
            'lote_entrada_estoques.id_subgrupo',
            'lote_entrada_estoques.id_marca',
            'lote_entrada_estoques.cd_usuario',
            'lote_entrada_estoques.created_at',
            'lote_entrada_estoques.updated_at'
        )

            ->leftJoin('item_lote_entrada_estoques as i', 'i.cd_lote', 'lote_entrada_estoques.id')
            ->where('cd_empresa', Auth::user()->empresa)
            ->groupBy(
                'lote_entrada_estoques.id',
                'lote_entrada_estoques.cd_empresa',
                'lote_entrada_estoques.descricao',
                'lote_entrada_estoques.status',
                'lote_entrada_estoques.tp_lote',
                'lote_entrada_estoques.id_subgrupo',
                'lote_entrada_estoques.id_marca',
                'lote_entrada_estoques.cd_usuario',
                'lote_entrada_estoques.created_at',
                'lote_entrada_estoques.updated_at'
            )
            ->get();
    }
    public function storeData($input)
    {
        LoteEntradaEstoque::create([
            'cd_empresa' => Auth::user()->empresa,
            'descricao' => $input['ds_lote'],
            'cd_usuario' => $input['cd_usuario'],
            'status' => $input['status'],
            'tp_lote' => $input['tp_lote'],
            'id_subgrupo' => $input['cd_subgrupo'],
            'id_marca' => $input['cd_marca']
        ]);
    }
    public function findLote($id)
    {
        return LoteEntradaEstoque::findOrFail($id);
    }
    public function updateData($data, $qtd_item)
    {
        LoteEntradaEstoque::where('id', $data->id)
            ->update(['status' => 'F', 'ps_liquido_total' => $qtd_item[0]['peso'], 'qtd_itens' => $qtd_item[0]['qtd']]);

        return response()->json(['success' => 'Lote finalizado com sucesso!']);
    }
    public function deleteData($id)
    {
        try {
            LoteEntradaEstoque::find($id)->delete();
        } catch (\Throwable $th) {
            return response()->json(['error' => "Esse lote não pode ser excluido a item nele!"]);
        }
        return response()->json(['success' => 'Lote excluido com sucesso!']);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i:s');
    }
}
