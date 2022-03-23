<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Producao extends Model
{
    use HasFactory;

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function TrocaServico($data)
    {
        $query = "SELECT
                    R.O_IDORDEMPRODUCAORECAP ORDEM, R.CD_PESSOA||' - '|| R.NM_PESSOA PESSOA, R.DSETAPA, R.IDEXECUTOR||' - '||R.NMEXECUTOR OPERADOR,
                    R.DTALTERACAO, R.IDANTIGA, R.DSANTIGA, R.IDNOVA, R.DSNOVA
                    FROM RETORNA_TROCADESENHO(3,'$data')R
                    WHERE R.IDANTIGA <> R.IDNOVA";
        return DB::connection($this->setConnet())->select($query);
    }
    public function recapMounth($dt_inicial, $dt_final)
    {
        $key = 'recapmensal_';
        $query = "SELECT
        CASE EXTRACT(MONTH FROM OPR.DTFECHAMENTO)
            WHEN 1 THEN 'Jan'
            WHEN 2 THEN 'Fev'
            WHEN 3 THEN 'Mar'
            WHEN 4 THEN 'Abr'
            WHEN 5 THEN 'Mai'
            WHEN 6 THEN 'Jun'
            WHEN 7 THEN 'Jul'
            WHEN 8 THEN 'Ago'
            WHEN 9 THEN 'Set'
            WHEN 10 THEN 'Out'
            WHEN 11 THEN 'Nov'
            WHEN 12 THEN 'Dez'
        END MES_NOME,
        EXTRACT(MONTH FROM OPR.DTFECHAMENTO) MES_NUM,
        EXTRACT(YEAR FROM OPR.DTFECHAMENTO) ANO, COUNT(OPR.ID) QTDE
        FROM ORDEMPRODUCAORECAP OPR
        INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
        INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
        WHERE OPR.STEXAMEFINAL = 'A'
          AND OPR.STORDEM = 'F'
          AND IPP.STCANCELADO = 'N'
          AND IPP.STGARANTIA = 'N'
          AND OPR.DTFECHAMENTO between '$dt_inicial' and '$dt_final'
          --AND CAST(OPR.DTFECHAMENTO AS TIMESTAMP) BETWEEN :MONTH_BEGIN(-4) AND :MONTH_END(0)
            AND PP.IDEMPRESA IN (3,1,4)
        GROUP BY ANO, MES_NUM
        ORDER BY ANO, MES_NUM";

        return Cache::remember($key, now()->addMinutes(60), function () use ($query) {
            return DB::connection($this->setConnet())->select($query);
        });
    }
}
