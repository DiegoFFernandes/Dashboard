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
    public function findLote($id){
        return LoteEntradaEstoque::findOrFail($id);
    }

    public function updateData($id){
        LoteEntradaEstoque::where('id', $id)->update(['status' => 'F']);
        return response()->json(['success' => 'Lote finalizado com sucesso!']);
    }
}   
