<?php

namespace App\Models;

use Helper;
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

    public function listPedidoLaudoRecusado()
    {
        $query = "
                SELECT
                    FIRST 50
                    P.IDEMPRESA,
                    LTP.NR_LAUDO,
                    P.ID AS NR_PEDIDO,
                    PE.NM_PESSOA,
                    P.STPEDIDO,
                    LTP.TP_LAUDO
                FROM LAUDOTECNICOPNEURECAP LTP
                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = LTP.IDITEMPEDIDOPNEUGARANTIA)
                INNER JOIN PEDIDOPNEU P ON (P.ID = IPP.IDPEDIDOPNEU)
                INNER JOIN PESSOA PE ON (PE.CD_PESSOA = P.IDPESSOA)
                WHERE LTP.IDITEMPEDIDOPNEURECUSA IS NULL
                    AND LTP.IDITEMPEDIDOPNEUGARANTIA IS NOT NULL
                    --and P.ID in (90538,92118,92009)
                    AND LTP.TP_LAUDO in ('R','G')
                    AND P.STPEDIDO = 'N'
                    AND P.IDTIPOPEDIDO = 3";
        $data = DB::connection('firebird_rede')->select($query);
        return Helper::ConvertFormatText($data);
    }

    public function updateStatuPedidoLaudoRecusado($empresa, $pedido)
    {
        return DB::transaction(function () use ($empresa, $pedido) {

            DB::connection('firebird_rede')->select("EXECUTE PROCEDURE GERA_SESSAO");
            $query = "
                UPDATE PEDIDOPNEU P
                SET P.STPEDIDO = 'A'
                WHERE P.ID = :pedido
                    AND P.IDEMPRESA = :empresa";

            return DB::connection('firebird_rede')->statement($query, [
                'pedido' => $pedido,
                'empresa' => $empresa
            ]);
        });
    }
}
