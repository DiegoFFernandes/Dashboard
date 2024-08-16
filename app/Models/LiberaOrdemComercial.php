<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LiberaOrdemComercial extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet($localizacao)
    {
        if ($localizacao == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';

        // return $this->connection = Auth::user()->conexao;
    }
    public function listOrdensBloqueadas($cd_regiao, $pedidos)
    {
        $query = "
                SELECT
                PP.IDEMPRESA EMP,
                PP.DTEMISSAO,
                PP.ID PEDIDO,
                PP.STPEDIDO,
                PP.TP_BLOQUEIO,
                PP.IDPEDIDOMOVEL,
                CAST(P.NM_PESSOA AS VARCHAR(1000) CHARACTER SET UTF8) PESSOA,
                CAST(PP.DSBLOQUEIO AS VARCHAR(8100) CHARACTER SET UTF8) DSBLOQUEIO,
                PP.DSLIBERACAO,
                CAST(PV.NM_PESSOA AS VARCHAR(1000) CHARACTER SET UTF8) VENDEDOR,
                EP.CD_REGIAOCOMERCIAL
            FROM PEDIDOPNEU PP
            INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.IDPEDIDOPNEU = PP.ID)
            INNER JOIN ITEM I ON (IPP.IDSERVICOPNEU = I.CD_ITEM)
            LEFT JOIN ITEMTABPRECO ITP ON (ITP.CD_TABPRECO = IPP.IDTABPRECO
                AND ITP.CD_ITEM = IPP.IDSERVICOPNEU)
            INNER JOIN PESSOA P ON (P.CD_PESSOA = PP.IDPESSOA)
            INNER JOIN PESSOA PV ON (PV.CD_PESSOA = PP.IDVENDEDOR)
            INNER JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA
                AND EP.CD_ENDERECO = 1)
            WHERE PP.STPEDIDO IN ('B')
                AND PP.TP_BLOQUEIO <> 'F'
                " . (($cd_regiao != "") ? "and ep.cd_regiaocomercial in ($cd_regiao)" : "") . "
                " . (($pedidos != "") ? "and pp.id in ($pedidos)" : "and pp.id = 0") . "
                --and ipb.iditempedidopneu = 466381
                AND PP.idempresa <> 1
            GROUP BY PP.STPEDIDO,
                PP.TP_BLOQUEIO,
                PP.IDEMPRESA,
                PP.DTEMISSAO,
                PESSOA,
                PP.DSBLOQUEIO,
                PP.DSLIBERACAO,
                VENDEDOR,
                EP.CD_REGIAOCOMERCIAL,
                PP.ID,
                PP.IDPEDIDOMOVEL  ";
        return DB::connection('firebird_rede')->select($query);
    }
    public function listPneusOrdensBloqueadas($id)
    {
        $query = "
                SELECT
                PP.STPEDIDO,
                PP.TP_BLOQUEIO,
                PP.ID PEDIDO,
                PP.IDEMPRESA EMP,
                PP.DTEMISSAO,
                CAST(P.NM_PESSOA AS VARCHAR(1000) CHARACTER SET UTF8) PESSOA,
                I.CD_SUBGRUPO,
                CAST(PV.NM_PESSOA AS VARCHAR(1000) CHARACTER SET UTF8) VENDEDOR,
                IPP.NRSEQCRIACAO SEQ,
                PP.IDPEDIDOMOVEL,
                I.DS_ITEM,
                IPP.VLUNITARIO VL_VENDA,
                CAST(ITP.VL_PRECO AS NUMERIC(15,2)) VL_PRECO,
                CAST(100 * (1 - (IPP.VLUNITARIO /
                CASE
                WHEN ITP.VL_PRECO = 0 THEN 1
                ELSE ITP.VL_PRECO
                END)) AS NUMERIC(15,2)) PC_DESCONTO,
                ITP.CD_TABPRECO
            FROM PEDIDOPNEU PP
            INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.IDPEDIDOPNEU = PP.ID)
            --inner join itempedidopneuborracheiro ipb on (ipb.iditempedidopneu = ipp.id)
            INNER JOIN ITEM I ON (IPP.IDSERVICOPNEU = I.CD_ITEM)
            LEFT JOIN ITEMTABPRECO ITP ON (ITP.CD_TABPRECO = IPP.IDTABPRECO
                AND ITP.CD_ITEM = IPP.IDSERVICOPNEU)
            INNER JOIN PESSOA P ON (P.CD_PESSOA = PP.IDPESSOA)
            INNER JOIN PESSOA PV ON (PV.CD_PESSOA = PP.IDVENDEDOR)
            WHERE PP.IDEMPRESA <> 1
                AND PP.STPEDIDO IN ('B')
                AND PP.TP_BLOQUEIO <> 'F'
                " . (($id <> 0) ? " and pp.id = '" . $id . "'" : "") . "";

        return DB::connection('firebird_rede')->select($query);
    }
}
