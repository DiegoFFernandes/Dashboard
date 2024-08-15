<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoPneu extends Model
{
    use HasFactory;
    protected $table = 'PEDIDOPNEU';

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function verifyIfExists($pedido)
    {
        $query = "select first 1 pp.id from pedidopneu pp where pp.id = $pedido";
        $data = DB::connection('firebird_rede')->select($query);
        return empty($data) ? 0 : 1;
    }
    public function updateData($data, $stpedido, $tpbloqueio)
    {       
        // return $query = "update pedidopneu pp
        //     set pp.dsliberacao = '$data->DSLIBERACAO'
        //     " . (($stpedido == "N") ? ", pp.stpedido = 'N' " : "") . " 
        //     " . (($tpbloqueio == "F") ? ", pp.tpbloqueio = 'F' " : "") . " 
        //     where pp.id = $data->PEDIDO";

        return DB::transaction(function () use ($data, $stpedido, $tpbloqueio) {

            DB::connection('firebird_rede')->select("EXECUTE PROCEDURE GERA_SESSAO");

            $query = "update pedidopneu pp
            set pp.dsliberacao = '$data->DSLIBERACAO'
            " . (($stpedido == "N") ? ", pp.stpedido = 'N' " : "") . " 
            " . (($tpbloqueio == "F") ? ", pp.tp_bloqueio = 'F' " : "") . " 
            where pp.id = $data->PEDIDO";

            return DB::connection('firebird_rede')->statement($query);
        });
    }
}
