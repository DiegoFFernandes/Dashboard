<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ComissaoVendedor extends Model
{
    use HasFactory;

    public function listComissaoLiquidacao($data, $dt_inicio, $dt_fim)
    {
        $cd_empresa = $data['cd_empresa'];
        // $dt_inicio = $data['data_ini'];
        // $dt_fim = $data['data_fim'];
        $cd_vendedor = $data['cd_vendedor'];

        $query = "
                SELECT DISTINCT
                    C.CD_EMPRESA,
                    PVI.NM_PESSOA NM_VENDEDOR,
                    INFV.CD_VENDEDOR,
                    C.CD_PESSOA,
                    P.NM_PESSOA,
                    N.DT_EMISSAO,
                    N.NR_NOTAFISCAL || ' - ' || C.NR_PARCELA || '/' || RMAX.O_NR_MAIORPARCELA NR_NOTAFISCAL,
                    C.DT_LANCAMENTO,
                    PR.DT_PROCESSO DT_LIQUIDACAO,
                    C.DT_VENCIMENTO,
                    C.VL_DOCUMENTO,
                    INF.CD_MOVIMENTACAO,
                    CAST(
                    --VERIFICA SE EXISTE VALOR LIQUIDADO
                    COALESCE(((SUM(MP.VL_DOCUMENTO) / C.VL_DOCUMENTO) * (INF.VL_LIQUIDO * INFV.PC_COMISSAO) / 100) / RMAX.O_NR_MAIORPARCELA,

                    --CASO VALOR FOR NULO FAZ O CACULO PELO TOTAL DOS ITEM DIVINDO PELA PARCELA RESTANTE
                    (INF.VL_UNITARIO * INF.QT_ITEMNOTA) / RMAX.O_NR_MAIORPARCELA * INFV.PC_COMISSAO / 100,

                    --SE LIQUIDADO CALCULA PELO VALOR PAGO CLIENTE
                    (ROUND((SUM(MP.VL_DOCUMENTO) / C.VL_DOCUMENTO), 2) * (INF.VL_LIQUIDO * INFV.PC_COMISSAO) / 100) / RMAX.O_NR_MAIORPARCELA)

                    AS DECIMAL(18,2)) VL_COMISSAO,

                    INF.CD_ITEM,
                    ITEM.DS_ITEM,
                    INF.QT_ITEMNOTA,
                    CAST((INF.VL_UNITARIO * INF.QT_ITEMNOTA) / RMAX.O_NR_MAIORPARCELA AS DECIMAL(18,2)) VL_UNITARIO,
                    CAST(ROUND((SUM(MP.VL_DOCUMENTO) / C.VL_DOCUMENTO) * (INF.VL_LIQUIDO / RMAX.O_NR_MAIORPARCELA), 2) AS DECIMAL(18,2)) AS VL_LIQUIDADO,
                    INFV.PC_COMISSAO,
                    
                    INFV.CD_TIPO,
                    INF.VL_UNITARIO VL_VENDA,
                    I.VL_PRECO VL_TABPRECO
                FROM CONTAS C
                INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                INNER JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA
                    AND EP.CD_ENDERECO = 1)
                JOIN NOTA N ON (C.NR_DOCUMENTO = N.NR_NOTAFISCAL
                    AND C.CD_EMPRESA = N.CD_EMPRESA
                    AND C.CD_PESSOA = N.CD_PESSOA
                    AND C.DT_LANCAMENTO = N.DT_EMISSAO)
                JOIN ITEMNOTA INF ON (N.NR_LANCAMENTO = INF.NR_LANCAMENTO
                    AND N.CD_EMPRESA = INF.CD_EMPRESA
                    AND N.CD_SERIE = INF.CD_SERIE
                    AND N.TP_NOTA = INF.TP_NOTA)
                INNER JOIN ITEM ON (ITEM.CD_ITEM = INF.CD_ITEM)
                JOIN ITEMNOTAVENDEDOR INFV ON (INF.CD_EMPRESA = INFV.CD_EMPRESA
                    AND INF.NR_LANCAMENTO = INFV.NR_LANCAMENTO
                    AND INF.TP_NOTA = INFV.TP_NOTA
                    AND INF.CD_SERIE = INFV.CD_SERIE
                    AND INF.CD_ITEM = INFV.CD_ITEM)
                LEFT JOIN ITEMTABPRECO I ON (I.CD_TABPRECO = 1
                    AND I.CD_ITEM = INF.CD_ITEM)
                LEFT JOIN RETORNA_MAIORPARCELACONTAS(C.CD_EMPRESA, C.NR_LANCAMENTO, C.CD_PESSOA, C.CD_TIPOCONTA) RMAX ON (1 = 1)
                JOIN PESSOA PVI ON (INFV.CD_VENDEDOR = PVI.CD_PESSOA)
                LEFT JOIN MOVTOPROCESSO MP ON (MP.CD_EMPRCONTAS = C.CD_EMPRESA
                    AND MP.NR_LANCAMENTO = C.NR_LANCAMENTO
                    AND MP.CD_PESSOA = C.CD_PESSOA
                    AND MP.CD_TIPOCONTA = C.CD_TIPOCONTA
                    AND MP.NR_PARCELA = C.NR_PARCELA)
                LEFT JOIN PROCESSO PR ON (MP.CD_EMPRESA = PR.CD_EMPRESA
                    AND MP.NR_PROCESSO = PR.NR_PROCESSO
                    AND PR.ST_PROCESSO = 'L')
                WHERE PR.DT_PROCESSO BETWEEN '$dt_inicio' AND '$dt_fim'
                    AND C.DT_VENCIMENTO >= CURRENT_DATE - 120 -- DIAS A DESCONSIDERAR DE CONTAS VENCIADAS
                    AND C.ST_CONTAS NOT IN ('A', 'C')
                    AND C.CD_TIPOCONTA NOT IN (9)
                    AND C.TP_CONTAS IN ('S')
                    AND INF.VL_UNITARIO > 0
                    AND N.TP_NOTA = 'S'
                    AND PVI.CD_PESSOA = $cd_vendedor                    
                     " . (($cd_empresa == 0) ? "" : "and c.cd_empresa = $cd_empresa") . "
                    --AND C.NR_DOCUMENTO = 1096736
                    --AND C.NR_DOCUMENTO = 44783
                    AND INFV.CD_TIPO = '1'
                GROUP BY C.CD_EMPRESA,
                    C.VL_SALDO,
                    PVI.NM_PESSOA,
                    INFV.CD_VENDEDOR,
                    C.CD_PESSOA,
                    P.NM_PESSOA,
                    N.DT_EMISSAO,
                    N.NR_NOTAFISCAL,
                    C.NR_PARCELA,
                    C.DT_LANCAMENTO,
                    C.DT_VENCIMENTO,
                    C.VL_DOCUMENTO,
                    PR.DT_PROCESSO,
                    --MP.VL_DOCUMENTO,
                    INF.CD_ITEM,
                    ITEM.DS_ITEM,
                    INF.QT_ITEMNOTA,
                    INF.VL_UNITARIO,
                    INF.VL_LIQUIDO,
                    RMAX.O_NR_MAIORPARCELA,
                    INFV.PC_COMISSAO,
                    C.ST_CONTAS,
                    INFV.CD_TIPO,
                    INF.CD_MOVIMENTACAO,
                    I.VL_PRECO ";

        $data = DB::connection('firebird_rede')->select($query);

        return Helper::ConvertFormatText($data);
    }
}
