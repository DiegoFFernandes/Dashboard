<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

\Carbon\Carbon::setToStringFormat('d-m-Y');

class LoteEntradaEstoque extends Model
{
    use HasFactory;
    protected $fillable = [
        'descricao',
        'cd_usuario',
        'status'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        //'updated_at' => 'datetime:Y-m-d',
        //'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];
    public $timestamps = true;
    public $table = 'lote_entrada_estoques';
    protected $connection;

    public function lotesAll()
    {
        return LoteEntradaEstoque::all();
    }

    public function storeData($input)
    {
        LoteEntradaEstoque::create([
            'descricao' => $input['ds_lote'],
            'cd_usuario' => $input['cd_usuario'],
            'status' => $input['status']
        ]);
    }
    public function findLote($id)
    {
        return LoteEntradaEstoque::findOrFail($id);
    }

    public function updateData($data, $qtd_item)
    {        
        LoteEntradaEstoque::where('id', $data->id)->update(['status' => 'F', 'ps_liquido_total' => $data->peso, 'qtd_itens' => $qtd_item]);      
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
}
