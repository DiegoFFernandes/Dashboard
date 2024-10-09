<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class View extends Model
{
    use HasFactory;

    public function coletas($id)
    {
        $dataCondition = "AND P.DTEMISSAO = CURRENT_DATE";

        if ($id == 1) {
            $dataCondition = "AND P.DTEMISSAO BETWEEN DATEADD(-EXTRACT(DAY FROM CURRENT_DATE) + 1 DAY TO CURRENT_DATE) AND 'TODAY'";
        } elseif ($id == 2) {
            $dataCondition = "AND P.DTEMISSAO BETWEEN DATEADD(-EXTRACT(DAY FROM CURRENT_DATE) + 1 DAY TO DATEADD(-1 MONTH TO CURRENT_DATE)) AND DATEADD(-EXTRACT(DAY FROM CURRENT_DATE) DAY TO CURRENT_DATE)";
        } elseif ($id == 3) {
            $dataCondition = "AND P.DTEMISSAO BETWEEN DATEADD(-EXTRACT(DAY FROM CURRENT_DATE) + 1 DAY TO DATEADD(-6 MONTH TO CURRENT_DATE)) AND DATEADD(-EXTRACT(DAY FROM CURRENT_DATE) DAY TO CURRENT_DATE)";
        } elseif ($id == 4) {
            $dataCondition = "AND P.DTEMISSAO = CURRENT_DATE-1";
        } elseif ($id == 5) {
            $dataCondition = "AND P.DTEMISSAO = CURRENT_DATE";
        }


        $query = "
                SELECT
                    E.NM_EMPRESA,
                    COUNT(1) QTDE,
                    EXTRACT(MONTH FROM P.DTEMISSAO) MES,
                    RTRIM(ME.O_DS_MES) DS_MES,
                    COALESCE(AVG(I.VLUNITARIO - COALESCE(I.VLDESCONTO, 0)), 0) VALORMEDIO
                FROM PEDIDOPNEU P
                INNER JOIN ITEMPEDIDOPNEU I ON (I.IDPEDIDOPNEU = P.ID)
                INNER JOIN ITEM IT ON (IT.CD_ITEM = I.IDSERVICOPNEU)
                LEFT JOIN MES_EXTENSO(P.DTEMISSAO) ME ON (0 = 0)
                INNER JOIN EMPRESA E ON (E.CD_EMPRESA = P.IDEMPRESA)
                WHERE P.STGERAPEDIDO = 'S'
                    AND I.STCANCELADO = 'N'
                    AND P.STPEDIDO <> 'C'
                    AND I.STGARANTIA = 'N'
                    
                    $dataCondition
                    
                    AND P.IDEMPRESA NOT IN (1)
                    AND IT.CD_SUBGRUPO <> 308
                GROUP BY DS_MES,
                        MES,
                        --P.DTEMISSAO,
                        E.NM_EMPRESA,
                        E.CD_EMPRESA
                    ORDER BY MES";

        $data = DB::connection('firebird_rede')->select($query);

        return Helper::ConvertFormatText($data);
    }
    public function pedidosNaoIniciados()
    {
        $query = "
                SELECT
                    EMPRESA.NM_EMPRESA,
                    P.IDEMPRESA,
                    COUNT(1) QTDE
                FROM PEDIDOPNEU P
                INNER JOIN ITEMPEDIDOPNEU I ON (I.IDPEDIDOPNEU = P.ID)
                INNER JOIN EMPRESA ON (EMPRESA.CD_EMPRESA = P.IDEMPRESA)
                LEFT JOIN ORDEMPRODUCAORECAP OPR ON (OPR.IDITEMPEDIDOPNEU = I.ID)
                LEFT JOIN EXAMEINICIAL E ON (E.IDORDEMPRODUCAORECAP = OPR.ID)
                INNER JOIN ITEM IT ON (IT.CD_ITEM = I.IDSERVICOPNEU)
                WHERE P.STPEDIDO <> 'C'
                    AND I.STCANCELADO = 'N'
                    AND OPR.STORDEM <> 'C'
                    AND E.ID IS NULL
                    AND IT.CD_SUBGRUPO <> 308
                    AND P.DTEMISSAO BETWEEN '01.' || EXTRACT(MONTH FROM CURRENT_DATE) || '.' || EXTRACT(YEAR FROM CURRENT_DATE) AND LASTDAYMONTH(CURRENT_DATE)
                GROUP BY P.IDEMPRESA,
                    EMPRESA.NM_EMPRESA";

        $data = DB::connection('firebird_rede')->select($query);

        return Helper::ConvertFormatText($data);
    }
}
