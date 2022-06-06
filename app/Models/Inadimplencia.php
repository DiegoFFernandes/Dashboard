<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Inadimplencia extends Model
{
    use HasFactory;
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet($banco)
    {
        if ($banco == 0) {
            return $this->connection = Auth::user()->conexao;
        } else {
            return $this->connection = $banco;
        }
    }
    public function dividaALL($banco)
    {
        $query = "select COALESCE(x.cd_areacomercial, 99) cd_areacomercial, COALESCE(cast(x.ds_areacomercial as varchar(50) character set utf8), 'SEM REGIAO DEFENIDA') ds_areacomercial,
                sum(x.atevencido60) atevencido60, sum(x.atevencido120) atevencido120, sum(x.maisvencido120) maisvencido120, sum(x.avencer) avencer
                from (
                    SELECT ac.cd_areacomercial, AC.ds_areacomercial, C.VL_SALDO atevencido60, 0 atevencido120, 0 maisvencido120, 0 avencer
                        FROM CONTAS C
                        INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                        LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                        LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                        LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                        LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                        AND CD.CD_EMPRESA = C.CD_EMPRESA)
                        WHERE C.ST_CONTAS IN ('T','P')
                        AND C.ST_INCOBRAVEL = 'N'
                        AND C.DT_VENCIMENTO between current_date-60 and current_date-6
                        and C.CD_COBRADOR IS NULL
                        AND C.cd_empresa IN (2,3,101,102)
                        AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
                
                    union all
                
                    SELECT ac.cd_areacomercial, AC.ds_areacomercial, 0 atevencido60, C.VL_SALDO atevencido120, 0 maisvencido120, 0 avencer
                        FROM CONTAS C
                        INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                        LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                        LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                        LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                        LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                        AND CD.CD_EMPRESA = C.CD_EMPRESA)
                        WHERE C.ST_CONTAS IN ('T','P')
                        AND C.ST_INCOBRAVEL = 'N'
                        AND C.DT_VENCIMENTO between current_date-119 and current_date-61
                        and C.CD_COBRADOR IS NULL
                        AND C.cd_empresa IN (2,3,101,102)
                        AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
                
                    union all
                
                    SELECT ac.cd_areacomercial, AC.ds_areacomercial, 0 atevencido60, 0 atevencido120, C.VL_SALDO maisvencido120, 0 avencer
                        FROM CONTAS C
                        INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                        LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                        LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                        LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                        LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                        AND CD.CD_EMPRESA = C.CD_EMPRESA)
                        WHERE C.ST_CONTAS IN ('T','P')
                        AND C.ST_INCOBRAVEL = 'N'
                        AND C.DT_VENCIMENTO <= current_date-120
                        and C.CD_COBRADOR IS NULL
                        AND C.cd_empresa IN (2,3,101,102)
                        AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
                
                    union all
                
                    SELECT ac.cd_areacomercial, AC.ds_areacomercial, 0 atevencido60, 0 atevencido120, 0 maisvencido120, C.VL_SALDO avencer
                        FROM CONTAS C
                        INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                        LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                        LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                        LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                        LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                        AND CD.CD_EMPRESA = C.CD_EMPRESA)
                        WHERE C.ST_CONTAS IN ('T','P')
                        AND C.ST_INCOBRAVEL = 'N'
                        AND C.DT_VENCIMENTO >= current_date-5
                        and C.CD_COBRADOR IS NULL
                        AND C.cd_empresa IN (2,3,101,102)
                        AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
                
                    ) x
                --where x.cd_areacomercial is not null
                group by x.cd_areacomercial, x.ds_areacomercial";



        $key = "dividaAll_" . Auth::user()->id . $banco;
        return Cache::remember($key, now()->addMinutes(60), function () use ($query, $banco) {
            return DB::connection($this->setConnet($banco))->select($query);
        });
    }
    public function chequeAll($tipoconta, $banco)
    {
        $query = "SELECT C.CD_EMPRESA,C.CD_PESSOA,C.CD_TIPOCONTA,C.NR_PARCELA,C.CD_FORMAPAGTO,C.TP_CONTAS,C.CD_SERIE,C.TP_DOCUMENTO,
        C.NR_DOCUMENTO,C.DT_LANCAMENTO,C.DT_VENCIMENTO,C.DT_LIQUIDACAO,C.VL_DOCUMENTO,C.VL_SALDO,C.ST_CONTAS,
        cast(P.NM_PESSOA as varchar(80) character set utf8) NM_PESSOA,
        EP.CD_REGIAOCOMERCIAL,P.NR_CNPJCPF
        FROM CONTAS C
        INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
        INNER JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA
                            AND EP.CD_ENDERECO = 1)
        LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                                AND CD.CD_EMPRESA = C.CD_EMPRESA)
        WHERE C.ST_CONTAS IN ('T','P')
        " . (($tipoconta == 'desc') ? "AND C.TP_CONTAS = 'E' AND TC.TP_TIPOCONTA IN ('AC') AND C.CD_TIPOCONTA = 20" : "") . "  
        " . (($tipoconta == 'pre') ? "AND C.ST_INCOBRAVEL = 'N' AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))" : "") . "   
        ";

        return DB::connection($this->setConnet($banco))->select($query);
    }
    public function dpDescontadas($banco)
    {
        $query = "SELECT C.CD_EMPRESA,C.CD_PESSOA,C.NR_PARCELA,C.CD_FORMAPAGTO,C.TP_CONTAS,C.CD_SERIE,C.TP_DOCUMENTO,
        C.NR_DOCUMENTO,C.DT_LANCAMENTO,C.DT_VENCIMENTO,C.VL_DOCUMENTO,C.VL_SALDO,C.ST_CONTAS,
        cast(P.NM_PESSOA as varchar(80) character set utf8) NM_PESSOA,
        EP.CD_REGIAOCOMERCIAL,P.NR_CNPJCPF
        FROM CONTAS C
        INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
        INNER JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA
                            AND EP.CD_ENDERECO = 1)
        LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                                AND CD.CD_EMPRESA = C.CD_EMPRESA)
        WHERE C.ST_CONTAS IN ('T','P')
        AND C.TP_CONTAS = 'E'
        --AND TC.TP_TIPOCONTA IN ('AC')
        AND C.CD_TIPOCONTA = 9";

        return DB::connection($this->setConnet($banco))->select($query);
    }
    public function DetailsArea($cd_area, $banco)
    {
        $query = "
                select x.cd_regiaocomercial, x.ds_regiaocomercial,
                sum(x.atevencido60) atevencido60, sum(x.atevencido120) atevencido120, sum(x.maisvencido120) maisvencido120, sum(x.avencer) avencer
            from (
                SELECT rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                    C.VL_SALDO atevencido60, 0 atevencido120, 0 maisvencido120, 0 avencer
                FROM CONTAS C
                INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                AND CD.CD_EMPRESA = C.CD_EMPRESA)
                WHERE C.ST_CONTAS IN ('T','P')
                AND C.ST_INCOBRAVEL = 'N'
                AND C.DT_VENCIMENTO between current_date-60 and current_date-6
                and C.CD_COBRADOR IS NULL
                AND C.cd_empresa IN (2,3,101,102)
                AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
            
                union all
            
                SELECT rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                        0 atevencido60, C.VL_SALDO atevencido120, 0 maisvencido120, 0 avencer
                    FROM CONTAS C
                    INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                    INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                    LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                    LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                    LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                    LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                    AND CD.CD_EMPRESA = C.CD_EMPRESA)
                    WHERE C.ST_CONTAS IN ('T','P')
                    AND C.ST_INCOBRAVEL = 'N'
                    AND C.DT_VENCIMENTO between current_date-119 and current_date-61
                    and C.CD_COBRADOR IS NULL
                    AND C.cd_empresa IN (2,3,101,102)
                    AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
                
                union all
            
                SELECT rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                    0 atevencido60, 0 atevencido120, C.VL_SALDO maisvencido120, 0 avencer
                FROM CONTAS C
                INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                AND CD.CD_EMPRESA = C.CD_EMPRESA)
                WHERE C.ST_CONTAS IN ('T','P')
                AND C.ST_INCOBRAVEL = 'N'
                AND C.DT_VENCIMENTO <= current_date-120
                and C.CD_COBRADOR IS NULL
                AND C.cd_empresa IN (2,3,101,102)
                AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
            
                union all
            
                SELECT rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                    0 atevencido60, 0 atevencido120, 0 maisvencido120, C.VL_SALDO avencer
                FROM CONTAS C
                INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                AND CD.CD_EMPRESA = C.CD_EMPRESA)
                WHERE C.ST_CONTAS IN ('T','P')
                AND C.ST_INCOBRAVEL = 'N'
                AND C.DT_VENCIMENTO >= current_date-5
                and C.CD_COBRADOR IS NULL
                AND C.cd_empresa IN (2,3,101,102)
                AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
            ) x
            where x.cd_areacomercial = $cd_area
            group by x.cd_regiaocomercial, x.ds_regiaocomercial";

        return DB::connection($this->setConnet($banco))->select($query);
    }
    public function DetailRegiao($cd_regiao, $banco)
    {
        $query = "select x.CD_EMPRESA, x.NM_PESSOA,
                x.NR_DOCUMENTO, x.cd_regiaocomercial, x.cd_regiaocomercial, x.ds_regiaocomercial,
                sum(x.atevencido60) atevencido60, sum(x.atevencido120) atevencido120, sum(x.maisvencido120) maisvencido120, sum(x.avencer) avencer
                from (
                    SELECT C.CD_EMPRESA, cast(C.CD_PESSOA||'-'||P.NM_PESSOA as varchar(80) character set utf8) NM_PESSOA,
                    C.NR_DOCUMENTO||'/'||C.NR_PARCELA NR_DOCUMENTO, rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                    C.VL_SALDO atevencido60, 0 atevencido120, 0 maisvencido120, 0 avencer
                    FROM CONTAS C
                    INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                    INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                    LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                    LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                    LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                    LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                    AND CD.CD_EMPRESA = C.CD_EMPRESA)
                    WHERE C.ST_CONTAS IN ('T','P')
                    AND C.ST_INCOBRAVEL = 'N'
                    AND C.DT_VENCIMENTO between current_date-60 and current_date-6
                    and C.CD_COBRADOR IS NULL
                    AND C.cd_empresa IN (3,2,101,102)
                    AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))

                union all

                    SELECT C.CD_EMPRESA, cast(C.CD_PESSOA||'-'||P.NM_PESSOA as varchar(80) character set utf8) NM_PESSOA,
                    C.NR_DOCUMENTO||'/'||C.NR_PARCELA NR_DOCUMENTO, rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                    0 atevencido60, C.VL_SALDO atevencido120, 0 maisvencido120, 0 avencer
                    FROM CONTAS C
                    INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                    INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                    LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                    LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                    LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                    LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                    AND CD.CD_EMPRESA = C.CD_EMPRESA)
                    WHERE C.ST_CONTAS IN ('T','P')
                    AND C.ST_INCOBRAVEL = 'N'
                    AND C.DT_VENCIMENTO between current_date-119 and current_date-61
                    and C.CD_COBRADOR IS NULL
                    AND C.cd_empresa IN (3,2,101,102)
                    AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))

                union all

                    SELECT C.CD_EMPRESA, cast(C.CD_PESSOA||'-'||P.NM_PESSOA as varchar(80) character set utf8) NM_PESSOA,
                    C.NR_DOCUMENTO||'/'||C.NR_PARCELA NR_DOCUMENTO, rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                    0 atevencido60, 0 atevencido120, C.VL_SALDO maisvencido120, 0 avencer
                    FROM CONTAS C
                    INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                    INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                    LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                    LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                    LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                    LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                    AND CD.CD_EMPRESA = C.CD_EMPRESA)
                    WHERE C.ST_CONTAS IN ('T','P')
                    AND C.ST_INCOBRAVEL = 'N'
                    AND C.DT_VENCIMENTO <= current_date-120
                    and C.CD_COBRADOR IS NULL
                    AND C.cd_empresa IN (3,2,101,102)
                    AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))

                union all

                    SELECT C.CD_EMPRESA, cast(C.CD_PESSOA||'-'||P.NM_PESSOA as varchar(80) character set utf8) NM_PESSOA,
                    C.NR_DOCUMENTO||'/'||C.NR_PARCELA NR_DOCUMENTO, rc.cd_regiaocomercial, rc.ds_regiaocomercial, ac.cd_areacomercial, AC.ds_areacomercial,
                    0 atevencido60, 0 atevencido120, 0 maisvencido120, C.VL_SALDO avencer
                    FROM CONTAS C
                    INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
                    INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
                    LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA AND EP.CD_ENDERECO = 1)
                    LEFT JOIN regiaocomercial RC ON (RC.cd_regiaocomercial = EP.cd_regiaocomercial)
                    LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
                    LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                    AND CD.CD_EMPRESA = C.CD_EMPRESA)
                    WHERE C.ST_CONTAS IN ('T','P')
                    AND C.ST_INCOBRAVEL = 'N'
                    AND C.DT_VENCIMENTO >= current_date-5
                    and C.CD_COBRADOR IS NULL
                    AND C.cd_empresa IN (3,2,101,102)
                    AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))
                ) x
                where x.cd_regiaocomercial = $cd_regiao
                group by x.CD_EMPRESA, x.NM_PESSOA, x.NR_DOCUMENTO, x.cd_regiaocomercial, x.cd_regiaocomercial, x.ds_regiaocomercial
                        ";

        return DB::connection($this->setConnet($banco))->select($query);
    }
}
