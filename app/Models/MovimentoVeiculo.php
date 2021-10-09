<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class MovimentoVeiculo extends Model
{
    use HasFactory;
    protected $fillable = [
        "cd_empresa",
        "cd_motorista_veiculos",
        "cd_pessoa",
        "cd_linha",
        "entrada",
        "saida",
        "observacao"
    ];

    public function storeDataEntrada($input)
    {
        if (MovimentoVeiculo::where("cd_motorista_veiculos", $input->cd_motorista_veiculos)->whereNull("saida")->exists()) {
            return 0;
        }

        return MovimentoVeiculo::create([
            "cd_empresa" => $input->cd_empresa,
            "cd_motorista_veiculos" => $input->cd_motorista_veiculos,
            "cd_pessoa" => $input->cd_pessoa,
            "cd_linha" => $input->cd_linha,
            "observacao" => $input->observacao,
            "entrada" => $input->entrada
        ]);
    }

    public function storeDataSaida($input)
    {
        $id =  MovimentoVeiculo::orderBy('id', 'desc')
            ->where('cd_motorista_veiculos', $input->cd_motorista_veiculos)
            ->whereNull('saida')
            ->value('id');

        if (isset($id)) {
            return MovimentoVeiculo::find($id)->update(['saida' => $input->saida]);
        } else {
            return 0;
        }
    }

    public function qtdMovimento($movimento, $dtInicio){        
        return MovimentoVeiculo::whereBetween($movimento, [$dtInicio, date('Y-m-d H:m:s')])->count();
    }

    public function movimentoAll(){
        return MovimentoVeiculo::select('pessoas.name as motorista', 'motorista_veiculos.placa', 'linha_motoristas.linha', 'user_entrada.name as resp_entrada', 
        'movimento_veiculos.entrada', 'user_saida.name as resp_saida', 'movimento_veiculos.saida')
        ->join('motorista_veiculos', 'motorista_veiculos.id', 'movimento_veiculos.cd_motorista_veiculos')
        ->join('pessoas', 'pessoas.id', 'motorista_veiculos.cd_pessoa')
        ->join('users as user_entrada', 'user_entrada.id', 'movimento_veiculos.cd_resp_entrada')
        ->leftJoin('users as user_saida', 'user_saida.id', 'movimento_veiculos.cd_resp_saida')
        ->join('linha_motoristas', 'linha_motoristas.id', 'movimento_veiculos.cd_linha')
        ->get();
    }
}
