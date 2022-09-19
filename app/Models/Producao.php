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

    public function TrocaServico($dt_ini, $dt_fim, $empresa)
    {
        $query = "SELECT
                        PP.idempresa,
                        CAST(coalesce(U.nm_usuario, 'CRIADA NA MRC001') as varchar(100) character SET utf8) COLETOR,
                        TD.IDORDEMPRODUCAORECAO ORDEM,
                        CAST(P.CD_PESSOA||'-'||P.NM_PESSOA as varchar(200) character SET utf8) PESSOA,
                        TD.idservantigo IDSERVANTIGO,
                        CAST(SPO.dsservico as varchar(500) character SET utf8) SERVANTIGO,
                        IPP.idservicopneu IDSERVATUAL,
                        CAST(SPN.dsservico as varchar(500) character SET utf8) SERVATUAL,
                        cast(TD.dtregistro as date) dt_registro
                    FROM ORDEMPRODUCAORECAP OPR
                    INNER JOIN (select td.idordemproducaorecao, td.idauttroca,
                                        (select first 1 sa.idservantigo from trocadesenho sa
                                        where sa.idordemproducaorecao = td.idordemproducaorecao) idservantigo,
                                            MIN(TD.dtregistro) dtregistro
                                        FROM trocadesenho TD
                                        group by td.idordemproducaorecao, td.idetapa, td.idexecutor, td.idauttroca) TD ON(OPR.ID = TD.IDORDEMPRODUCAORECAO)
                    INNER JOIN ITEMPEDIDOPNEU IPP ON(IPP.ID = OPR.IDITEMPEDIDOPNEU)
                    LEFT JOIN PEDIDOPNEU PP ON(PP.ID = IPP.IDPEDIDOPNEU)
                    LEFT JOIN PESSOA P ON(P.CD_PESSOA = PP.IDPESSOA)
                    LEFT JOIN RASPAGEMPNEU RP ON(RP.IDORDEMPRODUCAORECAP = OPR.ID)
                    LEFT JOIN servicopneu SPO ON (SPO.id = TD.idservantigo)
                    LEFT JOIN servicopneu SPN ON (SPN.id = IPP.idservicopneu) 
                    LEFT JOIN dispositivomovel DPM ON (DPM.id = PP.iddispositivomovel)
                    LEFT JOIN usuario u on (U.id = DPM.idusuario)
                    WHERE TD.DTREGISTRO between '$dt_ini' and '$dt_fim'
                    AND TD.idservantigo <> IPP.idservicopneu
                    AND OPR.stexamefinal <> 'R'
                    AND PP.idempresa = $empresa
                    GROUP BY
                            PP.idempresa,
                            U.nm_usuario,
                            TD.IDORDEMPRODUCAORECAO,
                            PESSOA,
                            IDSERVANTIGO,
                            SERVANTIGO,
                            IDSERVATUAL,
                            SERVATUAL,
                            dt_registro";

        $key = 'trocas_servico_' . $empresa . '_' . $dt_ini . '_' . $dt_fim;

        return Cache::remember($key, now()->addMinutes(15), function () use ($query) {
            return DB::connection($this->setConnet())->select($query);
        });
    }
    public function GridChandeServiceOrdem($ordem)
    {
        $key = 'ordem_' . $ordem;
        $query = "SELECT
                    TD.idordemproducaorecao ORDEM, EPP.dsetapaempresa, TD.idservantigo||'-'||SP.dsservico servtroca,
                    TD.idexecutor||'-'||ER.nmexecutor executor, TD.idauttroca||'-'||U.nm_usuario autorizador, TD.dtregistro
                FROM TROCADESENHO TD
                LEFT JOIN ETAPASPRODUCAOPNEU EPP ON (EPP.ID = TD.IDETAPA)
                LEFT JOIN SERVICOPNEU SP ON (SP.id = TD.idservantigo)
                LEFT JOIN executoretapa ER ON (ER.id = TD.idexecutor)
                LEFT JOIN USUARIO U ON (U.cd_usuario = TD.idauttroca)
                where TD.idordemproducaorecao = $ordem
                
                UNION ALL
                
                SELECT OPR.id ORDEM, null ds_etapaproducao, IPP.idservicopneu||'-'||SP.dsservico servtroca, null executor, null autorizador, null dtregistro
                FROM ORDEMPRODUCAORECAP OPR
                INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.id = OPR.iditempedidopneu)
                LEFT JOIN SERVICOPNEU SP ON (SP.id = IPP.idservicopneu)
                where OPR.id = $ordem";
        return Cache::remember($key, now()->addMinutes(15), function () use ($query) {
            return DB::connection($this->setConnet())->select($query);
        });
    }
    public function recapMounth($dt_inicial, $dt_final)
    {
        $key = 'recapmensal_' . Auth::user()->id;
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
            AND PP.IDEMPRESA IN (3,1,4,101,10,103,104,304,105)
        GROUP BY ANO, MES_NUM
        ORDER BY ANO, MES_NUM";

        return Cache::remember($key, now()->addMinutes(60), function () use ($query) {
            return DB::connection($this->setConnet())->select($query);
        });
    }
}
