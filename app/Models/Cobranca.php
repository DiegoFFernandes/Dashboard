<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Cobranca extends Model
{
    use HasFactory;
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function clientesInadiplentes($cd_empresa, $cd_regiao, $cd_area)
    {
        $query = "SELECT DISTINCT P.NR_CNPJCPF,  
        CAST(AC.ds_areacomercial as varchar(40) character set utf8) Area,
        CONTAS.CD_EMPRESA,
        CAST(PE.NM_PESSOA as varchar(100) character set utf8) NM_PESSOAEMP,
        CAST(CONTAS.CD_PESSOA ||' - '||P.NM_PESSOA as varchar(70) character set utf8) NM_PESSOA,
        CAST(COALESCE(RGC.DS_REGIAOCOMERCIAL, 'Sem Região Comercial') as varchar(50) character set utf8) DS_REGIAOQ,
        SUM(COALESCE(CONTAS.VL_SALDO,0) + COALESCE(CJ.O_VL_JURO,0)) VL_TOTAL
            FROM CONTAS
            INNER JOIN PESSOA P ON (P.CD_PESSOA = CONTAS.CD_PESSOA) 
            INNER JOIN TIPOCONTA ON (TIPOCONTA.CD_TIPOCONTA = CONTAS.CD_TIPOCONTA) 
            INNER JOIN TIPOPESSOA TP ON (TP.CD_TIPOPESSOA = P.CD_TIPOPESSOA) 
            LEFT JOIN FORMAPAGTO FP ON (FP.CD_FORMAPAGTO = CONTAS.CD_FORMAPAGTO) 
            INNER JOIN EMPRESA E ON(E.CD_EMPRESA = CONTAS.CD_EMPRESA) 
            INNER JOIN PESSOA PE ON(PE.cd_pessoa = E.CD_PESSOA) 
            LEFT JOIN PARMUSUARIO PRU ON (PRU.CD_EMPRESA = E.CD_EMPRESA 
                                    AND PRU.CD_USUARIO = 'admin'  
                                    AND PRU.ST_RESTRICAOPESSOA = 'S') 
            LEFT JOIN PARMFINANC PFN ON (PFN.CD_EMPRESA = PRU.CD_EMPRESA) 
            LEFT JOIN RETORNA_JURODESCONTOPAGO (CONTAS.CD_EMPRESA, CONTAS.NR_LANCAMENTO, CONTAS.CD_PESSOA, 
                    CONTAS.CD_TIPOCONTA, CONTAS.NR_PARCELA) MP ON (1=1) 
            LEFT JOIN RETORNA_DADOSCADASTRAIS(CONTAS.CD_PESSOA, 'B') EP2 ON (1 = 1)
            LEFT JOIN RETORNA_DADOSCADASTRAIS(PE.CD_PESSOA, NULL) ENDP ON (1 = 1) 
            LEFT JOIN PESSOA C ON (C.CD_PESSOA = CONTAS.CD_COBRADOR) 
            LEFT JOIN RETORNA_TOTALINCOBRAVEL(CONTAS.CD_EMPRESA, CONTAS.NR_LANCAMENTO, 
                                        CONTAS.CD_PESSOA, CONTAS.CD_TIPOCONTA, 
                                        CONTAS.NR_PARCELA) RI ON (1=1) 
            
            
            LEFT JOIN PESSOA VEND ON (VEND.CD_PESSOA = CONTAS.CD_VENDEDOR) 
            LEFT JOIN VENDEDOR ON (VENDEDOR.CD_VENDEDOR = CONTAS.CD_VENDEDOR) 
            LEFT JOIN RETORNA_DADOSCADASTRAIS(CONTAS.CD_PESSOA, NULL) EP ON (1 = 1) 
            LEFT JOIN CALCULA_JUROMORA(CONTAS.CD_EMPRESA, CONTAS.NR_LANCAMENTO, CONTAS.CD_PESSOA, CONTAS.CD_TIPOCONTA, CONTAS.NR_PARCELA, CURRENT_DATE, NULL) CJ ON (0=0) 
            LEFT JOIN RETORNA_DADOSCADASTRAIS(CONTAS.CD_PESSOA, 'B') EPB ON (1 = 1)
            LEFT JOIN BANCO BC ON (BC.CD_BANCO = CONTAS.CD_BANCO) 
            LEFT JOIN REGIAOCOMERCIAL RGC ON (RGC.CD_REGIAOCOMERCIAL = EP.CD_REGIAOCOMERCIAL) 
            LEFT JOIN AREACOMERCIAL AC ON (AC.CD_AREACOMERCIAL = RGC.CD_AREACOMERCIAL) 
            LEFT JOIN MUNICIPIO M ON (M.CD_MUNICIPIO = EP.CD_MUNICIPIO) 
            
            LEFT JOIN VIEW_TIPOCONTASUSUARIO(CONTAS.CD_EMPRESA, 'TI02', CONTAS.CD_TIPOCONTA)TPC ON (1=1) 
            WHERE CONTAS.ST_CONTAS <> 'A'
            AND TIPOCONTA.TP_TIPOCONTA = 'CR'
            AND  TPC.O_CD_TIPOCONTA IS NOT NULL
            AND CONTAS.CD_EMPRESA IN ($cd_empresa)
            AND CONTAS.ST_CONTAS in ('T','P')
            AND CONTAS.DT_VENCIMENTO <= CURRENT_DATE-3
            AND P.CD_TIPOPESSOA = (SELECT O_CD_TIPOPESSOA FROM RETORNA_TIPOPESSOA_RESTRITA(1, 'TI02', P.CD_TIPOPESSOA))
            AND P.CD_TIPOPESSOA <> COALESCE(PFN.CD_TIPOPESSOA,'-1')
            AND AC.CD_AREACOMERCIAL NOT IN (11,12)            
            " . (($cd_area != "") ? "AND AC.CD_AREACOMERCIAL IN ($cd_area)" : "") . "
            " . (($cd_regiao != "") ? "AND RGC.CD_REGIAOCOMERCIAL IN ($cd_regiao)" : "") . "
            GROUP BY CONTAS.CD_EMPRESA, P.NR_CNPJCPF, AC.ds_areacomercial, NM_PESSOA, NM_PESSOAEMP, DS_REGIAOQ
            ORDER BY VL_TOTAL DESC";

        $key = 'inadimplentes_' . $cd_empresa . $cd_area . $cd_regiao;
        
        return Cache::remember($key, now()->addMinutes(15), function () use ($query) {
             return DB::connection($this->setConnet())->select($query);
        });
    }
    public function clientesInadiplentesCnpj($cnpj, $cd_empresa)
    {
        $query = "
        SELECT DISTINCT P.NR_CNPJCPF,  AC.ds_areacomercial Area,
        CONTAS.CD_EMPRESA,
        CAST(PE.NM_PESSOA as varchar(100) character set utf8) NM_PESSOAEMP,
        CAST(CONTAS.CD_PESSOA ||' - '||P.NM_PESSOA as varchar(100) character set utf8) NM_PESSOA,
        CASE CONTAS.ST_CONTAS  WHEN 'L' THEN 0 ELSE
        CASE WHEN (CURRENT_DATE - CONTAS.DT_VENCIMENTO) < 0 THEN 0 ELSE (CURRENT_DATE - CONTAS.DT_VENCIMENTO) END END NR_DIAS, 
        CONTAS.NR_DOCUMENTO, CONTAS.DT_LANCAMENTO,
        CONTAS.DT_VENCIMENTO, CONTAS.DT_LIQUIDACAO, CONTAS.VL_DOCUMENTO,
        CONTAS.VL_SALDO,
        CJ.O_VL_JURO VL_JUROS, 
        '' DS_QUEBRADENTRO,
        (COALESCE(CONTAS.VL_SALDO,0) + COALESCE(CJ.O_VL_JURO,0)) VL_TOTAL,
        CONTAS.NR_PARCELA||'/'||(SELECT MAX(XX.NR_PARCELA)
         FROM CONTAS XX 
         WHERE XX.CD_EMPRESA = CONTAS.CD_EMPRESA 
           AND XX.NR_LANCAMENTO = CONTAS.NR_LANCAMENTO 
           AND XX.CD_PESSOA = CONTAS.CD_PESSOA 
           AND XX.CD_TIPOCONTA = CONTAS.CD_TIPOCONTA) NR_MAXPARCELA, 
        CONTAS.ST_CARTORIO STATUS,
        CASE when CONTAS.ST_CARTORIO = 'J' then 'Juridico'
             when CONTAS.ST_CARTORIO = 'C' then 'Em Cartório'
             when CONTAS.ST_CARTORIO = 'S' then 'Protestado' 
             else '' 
        END ST_CARTORIO,
         CONTAS.CD_FORMAPAGTO,
         CAST(COALESCE(RGC.CD_REGIAOCOMERCIAL||' - '||RGC.DS_REGIAOCOMERCIAL, 'Sem RegiÃ£o Comercial') as varchar(50) character set utf8) DS_REGIAOQ
        FROM CONTAS
        INNER JOIN PESSOA P ON (P.CD_PESSOA = CONTAS.CD_PESSOA) 
        INNER JOIN TIPOCONTA ON (TIPOCONTA.CD_TIPOCONTA = CONTAS.CD_TIPOCONTA) 
        INNER JOIN TIPOPESSOA TP ON (TP.CD_TIPOPESSOA = P.CD_TIPOPESSOA) 
        LEFT JOIN FORMAPAGTO FP ON (FP.CD_FORMAPAGTO = CONTAS.CD_FORMAPAGTO) 
        INNER JOIN EMPRESA E ON(E.CD_EMPRESA = CONTAS.CD_EMPRESA) 
        INNER JOIN PESSOA PE ON(PE.cd_pessoa = E.CD_PESSOA) 
        LEFT JOIN PARMUSUARIO PRU ON (PRU.CD_EMPRESA = E.CD_EMPRESA 
                                AND PRU.CD_USUARIO = 'admin'  
                                AND PRU.ST_RESTRICAOPESSOA = 'S') 
        LEFT JOIN PARMFINANC PFN ON (PFN.CD_EMPRESA = PRU.CD_EMPRESA) 
        LEFT JOIN RETORNA_JURODESCONTOPAGO (CONTAS.CD_EMPRESA, CONTAS.NR_LANCAMENTO, CONTAS.CD_PESSOA, 
                CONTAS.CD_TIPOCONTA, CONTAS.NR_PARCELA) MP ON (1=1) 
        LEFT JOIN RETORNA_DADOSCADASTRAIS(CONTAS.CD_PESSOA, 'B') EP2 ON (1 = 1)
        LEFT JOIN RETORNA_DADOSCADASTRAIS(PE.CD_PESSOA, NULL) ENDP ON (1 = 1) 
        LEFT JOIN PESSOA C ON (C.CD_PESSOA = CONTAS.CD_COBRADOR) 
        LEFT JOIN RETORNA_TOTALINCOBRAVEL(CONTAS.CD_EMPRESA, CONTAS.NR_LANCAMENTO,
                                    CONTAS.CD_PESSOA, CONTAS.CD_TIPOCONTA, 
                                    CONTAS.NR_PARCELA) RI ON (1=1)
        LEFT JOIN PESSOA VEND ON (VEND.CD_PESSOA = CONTAS.CD_VENDEDOR) 
        LEFT JOIN VENDEDOR ON (VENDEDOR.CD_VENDEDOR = CONTAS.CD_VENDEDOR) 
        LEFT JOIN RETORNA_DADOSCADASTRAIS(CONTAS.CD_PESSOA, NULL) EP ON (1 = 1) 
        LEFT JOIN CALCULA_JUROMORA(CONTAS.CD_EMPRESA, CONTAS.NR_LANCAMENTO, CONTAS.CD_PESSOA, CONTAS.CD_TIPOCONTA, CONTAS.NR_PARCELA, CURRENT_DATE, NULL) CJ ON (0=0) 
        LEFT JOIN RETORNA_DADOSCADASTRAIS(CONTAS.CD_PESSOA, 'B') EPB ON (1 = 1)
        LEFT JOIN BANCO BC ON (BC.CD_BANCO = CONTAS.CD_BANCO) 
        LEFT JOIN REGIAOCOMERCIAL RGC ON (RGC.CD_REGIAOCOMERCIAL = EP.CD_REGIAOCOMERCIAL) 
        LEFT JOIN AREACOMERCIAL AC ON (AC.CD_AREACOMERCIAL = RGC.CD_AREACOMERCIAL) 
        LEFT JOIN MUNICIPIO M ON (M.CD_MUNICIPIO = EP.CD_MUNICIPIO) 

        LEFT JOIN VIEW_TIPOCONTASUSUARIO(CONTAS.CD_EMPRESA, 'TI02', CONTAS.CD_TIPOCONTA)TPC ON (1=1) 
        WHERE CONTAS.ST_CONTAS <> 'A'
        AND TIPOCONTA.TP_TIPOCONTA = 'CR'
        AND  TPC.O_CD_TIPOCONTA IS NOT NULL
        AND CONTAS.CD_EMPRESA IN ($cd_empresa)
        AND CONTAS.ST_CONTAS in ('T','P')
        AND CONTAS.DT_VENCIMENTO <= CURRENT_DATE-3
        AND P.CD_TIPOPESSOA = (SELECT O_CD_TIPOPESSOA FROM RETORNA_TIPOPESSOA_RESTRITA(1, 'TI02', P.CD_TIPOPESSOA))
        AND P.CD_TIPOPESSOA <> COALESCE(PFN.CD_TIPOPESSOA,'-1')
        AND AC.CD_AREACOMERCIAL NOT IN (11,12)
        AND P.NR_CNPJCPF = '$cnpj'
        ORDER BY  CONTAS.CD_EMPRESA ,  C.NM_PESSOA, P.NM_PESSOA, CONTAS.DT_VENCIMENTO";        

        $key = 'inadimplentescnpj_' . $cd_empresa . $cnpj;
        
        return Cache::remember($key, now()->addMinutes(15), function () use ($query) {
             return DB::connection($this->setConnet())->select($query);
        });
    }
}
