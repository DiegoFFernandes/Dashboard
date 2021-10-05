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
}
