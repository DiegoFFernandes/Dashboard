<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SolicitacaoCompra extends Model
{
    use HasFactory;

    public function SolicitacoesCompraBloqueadas($status)
    {
        $query = "
                SELECT
                    SC.CD_EMPRESA,
                    SC.NR_SOLICITACAO,
                    SC.CD_SOLICITANTE || ' - ' || U.NM_USUARIO AS USUARIO,
                    SC.DT_SOLICITACAO,
                    SC.DT_DESEJADA,
                    CASE
                        WHEN SC.TP_SOLICITACAO = 'U' THEN 'URGENTE'
                        ELSE 'NORMAL'
                    END TP_SOLICITACAO,
                    SC.TP_SOLICITACAO,
                    SC.CD_AUTORIZADOR,
                    SC.DS_OBSERVACAO,
                    COALESCE(SC.ST_VISTO, 'N') ST_VISTO
                FROM SOLICITACAO SC
                INNER JOIN USUARIO U ON (U.CD_USUARIO = SC.CD_SOLICITANTE)
                WHERE SC.ST_SOLICITACAO = 'B'
                    AND COALESCE(SC.ST_VISTO, 'N') = '$status'
                ORDER BY SC.DT_SOLICITACAO DESC
            ";

        $results = DB::connection('firebird_rede')->select($query);
        return $results =  Helper::ConvertFormatText($results);
    }

    public function SolicitacoesCompraBloqueadasItem($nr_solicitacao, $cd_empresa)
    {
        $query = "
                    SELECT
                        ISC.CD_EMPRESA,
                        ISC.NR_SOLICITACAO,
                        ISC.CD_ITEM||' - '||I.DS_ITEM DS_ITEM,
                        ISC.PS_SOLICITADO,
                        ISC.QT_SOLICITADA,
                        ISC.VL_UNITARIO,
                        ISC.DS_OBSERVACAO
                    FROM
                        ITEMSOLICITACAO ISC
                    INNER JOIN ITEM I ON (I.CD_ITEM = ISC.CD_ITEM)
                    WHERE ISC.NR_SOLICITACAO = $nr_solicitacao
                        and isc.cd_empresa = $cd_empresa
            ";

        $results = DB::connection('firebird_rede')->select($query);
        return $results =  Helper::ConvertFormatText($results);
    }

    public function updateStatusSolicitacoesCompraBloqueadas($cd_empresa, $nr_solicitacao, $status, $ds_observacao)
    {

        return DB::transaction(function () use ($cd_empresa, $nr_solicitacao, $status, $ds_observacao) {

            DB::connection('firebird_rede')->select("EXECUTE PROCEDURE GERA_SESSAO");

           $query = "
                UPDATE SOLICITACAO S
                SET S.ST_SOLICITACAO = '$status',
                    S.ST_VISTO = 'S',
                    S.DS_OBSERVACAO = '$ds_observacao'
                WHERE S.nr_solicitacao = $nr_solicitacao
                    AND S.CD_EMPRESA = $cd_empresa
                                ";

            return DB::connection('firebird_rede')->select($query);
        });
    }
}
