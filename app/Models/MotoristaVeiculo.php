<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MotoristaVeiculo extends Model
{
    use HasFactory;
    protected $table = 'motorista_veiculos';
    protected $fillable = [
        'cd_empresa',
        'cd_pessoa',
        'placa',
        'cor',
        'cd_marcamodelofrota',
        'ano',
        'cd_tipoveiculo',
        'ativo',
        'cd_usuario'
    ];

    public function findMarcaModelo($id)
    {
        return MotoristaVeiculo::find($id);
    }

    public function MotoristaVeiculoAll()
    {
        return MotoristaVeiculo::select(
            'motorista_veiculos.id',
            'motorista_veiculos.cd_empresa',
            'motorista_veiculos.cd_pessoa',
            'pessoas.name',
            'motorista_veiculos.placa',
            'motorista_veiculos.cor',
            'marca_modelo_frotas.cd_frota',
            'frotaveiculos.descricao as dsfrota',
            'marca_modelo_frotas.cd_marca',
            'marcaveiculos.descricao as dsmarca',
            'marca_modelo_frotas.cd_modelo',
            'modeloveiculos.descricao as dsmodelo',
            'motorista_veiculos.ano',
            'motorista_veiculos.cd_tipoveiculo',
            'tipoveiculo.descricao as dstipo',
            DB::raw('(CASE WHEN ativo = "S" THEN "SIM" else "NÃO" END) as ativo')
        )
            ->join('pessoas', 'pessoas.id', 'motorista_veiculos.cd_pessoa')
            ->join('marca_modelo_frotas', 'marca_modelo_frotas.id', 'motorista_veiculos.cd_marcamodelofrota')
            ->join('marcaveiculos', 'marcaveiculos.id', 'marca_modelo_frotas.cd_marca')
            ->join('frotaveiculos', 'frotaveiculos.id', 'marca_modelo_frotas.cd_frota')
            ->join('modeloveiculos', 'modeloveiculos.id', 'marca_modelo_frotas.cd_modelo')
            ->join('tipoveiculo', 'tipoveiculo.id', 'motorista_veiculos.cd_tipoveiculo')

            // ->orderBy('motorista_veiculos.id')
            ->get();
    }

    public function storeData($input)
    {
        return MotoristaVeiculo::create($input);
    }

    public function findData($id)
    {
        return MotoristaVeiculo::where('id', $id)->first();
    }

    public function verifyIfExists($cd_pessoa, $placa)
    {
        return MotoristaVeiculo::where('cd_pessoa', $cd_pessoa)
            ->where('placa', $placa)->exists();
    }

    public function updateData($id, $input)
    {
        return MotoristaVeiculo::where('id', $id)->update($input);
    }

    public function destroyData($id)
    {
        return MotoristaVeiculo::find($id)->delete();
    }

    public function findPessoa($cd_pessoa){
        return MotoristaVeiculo::where('cd_pessoa', $cd_pessoa)->exists();
    }
}
