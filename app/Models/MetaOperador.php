<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MetaOperador extends Model
{
    use HasFactory;

    public function setConnet()
    {
        if (Auth::user() == null) {
            return $this->connection = 'firebird_campina';
        };
        return $this->connection = Auth::user()->conexao;
    }

    public function MetaOperadorSetor($cd_executor, $etapa)
    {
        $query = "SELECT X.ID,       
            CAST(X.NMEXECUTOR AS VARCHAR(100) CHARACTER SET UTF8) NMEXECUTOR,
            SUM(X.DIAATUAL) DIAATUAL, SUM(X.ONTEM) ONTEM, SUM(X.ANTEONTEM) ANTEONTEM, meta.qtmetadiaria meta
        FROM (
           SELECT E.id,
           CAST(E.NMEXECUTOR AS VARCHAR(100) CHARACTER SET UTF8) NMEXECUTOR, COUNT(I.ID) DIAATUAL, 0 ONTEM, 0 ANTEONTEM
           FROM $etapa->nm_tabela I
           INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
           INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
           INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
           INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
           WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE
            AND I.ST_ETAPA = 'F'
            AND PP.IDEMPRESA IN (108)
           GROUP BY E.id, E.NMEXECUTOR
           HAVING COUNT(I.ID) > 5
           
           UNION ALL
           
           SELECT E.id, 
           CAST(E.NMEXECUTOR AS VARCHAR(100) CHARACTER SET UTF8) NMEXECUTOR,
            0 DIAATUAL, COUNT(I.ID) ONTEM, 0 ANTEONTEM
           FROM $etapa->nm_tabela I
           INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
           INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
           INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
           INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
          WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE-1
            AND I.ST_ETAPA = 'F'
            AND PP.IDEMPRESA IN (108)
           GROUP BY  E.id, E.NMEXECUTOR
           HAVING COUNT(I.ID) > 5
           
           UNION ALL
           
           SELECT E.id, 
           CAST(E.NMEXECUTOR AS VARCHAR(100) CHARACTER SET UTF8) NMEXECUTOR,
           0 DIAATUAL, 0 ONTEM, COUNT(I.ID) ANTEONTEM
           FROM $etapa->nm_tabela I
           INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
           INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
           INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
           INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
           WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE-2
            AND I.ST_ETAPA = 'F'
            AND PP.IDEMPRESA IN (108)
           GROUP BY  E.id, E.NMEXECUTOR
           HAVING COUNT(I.ID) > 5
        ) X
        LEFT JOIN executoretapa E ON (E.ID = X.ID)
        LEFT JOIN etapasproducaoexecutorrecap meta on (meta.idexecutor = x.id)
        WHERE meta.idetapa = $etapa->cd_etapa
            and X.ID = $cd_executor
        GROUP BY X.ID, X.NMEXECUTOR, meta
        ORDER BY DIAATUAL DESC";

        return DB::connection('firebird_rede')->select($query);
    }
}
