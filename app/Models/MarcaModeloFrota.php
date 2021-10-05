<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MarcaModeloFrota extends Model
{
    use HasFactory;

    protected $fillable = [
        'cd_marca',
        'cd_modelo',
        'cd_frota',
        'cd_usuario'
    ];

    public function MarcaModeloAll()
    {
        return MarcaModeloFrota::select(
            'marca_modelo_frotas.id',
            'marca_modelo_frotas.cd_marca',
            'marcaveiculos.descricao as dsmarca',
            'marca_modelo_frotas.cd_modelo',
            'modeloveiculos.descricao as dsmodelo',
            'marca_modelo_frotas.cd_frota',
            'frotaveiculos.descricao as dsfrota',
            'marca_modelo_frotas.cd_usuario',
            'users.name as usuario'
        )
            ->join('marcaveiculos', 'marcaveiculos.id', '=', 'marca_modelo_frotas.cd_marca')
            ->join('modeloveiculos', 'modeloveiculos.id', 'marca_modelo_frotas.cd_modelo')
            ->join('users', 'users.id', 'marca_modelo_frotas.cd_usuario')
            ->join('frotaveiculos', 'frotaveiculos.id', 'marca_modelo_frotas.cd_frota')
            ->orderBy('marca_modelo_frotas.id')
            ->get();
    }

    public function storeData($input)
    {
        return MarcaModeloFrota::create($input);
    }

    public function updateData($id, $input)
    {
        return MarcaModeloFrota::find($id)->update($input);
    }

    public function findData($id)
    {
        return MarcaModeloFrota::select(
            'marca_modelo_frotas.id',
            'marca_modelo_frotas.cd_marca',
            'marcaveiculos.descricao as dsmarca',
            'marca_modelo_frotas.cd_modelo',
            'modeloveiculos.descricao as dsmodelo',
            'marca_modelo_frotas.cd_frota',
            'frotaveiculos.descricao as dsfrota',
            'marca_modelo_frotas.cd_usuario',
            'users.name as usuario'
        )
            ->join('marcaveiculos', 'marcaveiculos.id', '=', 'marca_modelo_frotas.cd_marca')
            ->join('modeloveiculos', 'modeloveiculos.id', 'marca_modelo_frotas.cd_modelo')
            ->join('users', 'users.id', 'marca_modelo_frotas.cd_usuario')
            ->join('frotaveiculos', 'frotaveiculos.id', 'marca_modelo_frotas.cd_frota')
            ->where('marca_modelo_frotas.id', $id)
            ->first();
    }

    public function destroyData($id)
    {
        return MarcaModeloFrota::find($id)->delete();
    }

    public function verifyIfExists($cd_marca, $cd_modelo)
    {
        return MarcaModeloFrota::where('cd_marca', $cd_marca)
            ->where('cd_modelo', $cd_modelo)
            ->exists();
    }

    public function verifyIfExistsMarca($cd_marca)
    {
        return MarcaModeloFrota::where('cd_marca', $cd_marca)->exists();
    }

    public function verifyIfExistsModelo($cd_modelo)
    {
        return MarcaModeloFrota::where('cd_modelo', $cd_modelo)->exists();
    }

    public function MarcaModeloDsAll()
    {
        return MarcaModeloFrota::select(
            'marca_modelo_frotas.id',
            DB::raw("CONCAT(marcaveiculos.descricao,' - ', modeloveiculos.descricao,' - ',
        frotaveiculos.descricao) as dsmarca")
        )
            ->join('marcaveiculos', 'marcaveiculos.id', '=', 'marca_modelo_frotas.cd_marca')
            ->join('modeloveiculos', 'modeloveiculos.id', 'marca_modelo_frotas.cd_modelo')
            ->join('users', 'users.id', 'marca_modelo_frotas.cd_usuario')
            ->join('frotaveiculos', 'frotaveiculos.id', 'marca_modelo_frotas.cd_frota')
            ->orderBy('marca_modelo_frotas.id')
            ->get();
    }
}
