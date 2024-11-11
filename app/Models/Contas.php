<?php

namespace App\Models;

use Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Contas extends Model
{
    use HasFactory;
    protected $table = 'contas';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }

    public function setConnet($cd_empresa)
    {
        if ($cd_empresa == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';
    }
    public function TicketsPendentsClient($empresa, $cnpj)
    {
        $query = "SELECT
                    E.nm_empresa empresa,
                    C.cd_pessoa||'-'||P.nm_pessoa pessoa,
                    p.NR_CNPJCPF,
                    C.nr_documento documento,
                    C.nr_documento||'/'||C.nr_parcela nr_documento,
                    c.dt_vencimento,
                    c.cd_formapagto,
                    case c.st_contas
                        when 'T' then 'Pendente Total'
                        when 'P' then 'Pendente Parcial'                        
                        else c.st_contas
                    end status,
                    c.vl_documento valor
                    FROM CONTAS C
                    INNER JOIN PESSOA P ON (P.cd_pessoa = C.cd_pessoa)
                    INNER JOIN EMPRESA E ON (E.cd_empresa = C.cd_empresa)
                    WHERE C.cd_empresa = $empresa->cd_empresa                        
                        " . (($cnpj == 0) ? "and C.cd_pessoa = " . Auth::user()->cd_pessoa . "" : "AND p.nr_cnpjcpf in ('$cnpj')") . "                        
                        AND C.st_contas not in ('L', 'C', 'A')
                        and C.cd_formapagto NOT IN ('LD')
                        AND C.cd_tipoconta = 2";
        return DB::connection('firebird_rede')->select($query);

        $key = "TicketsPendentsAll_" . Auth::user()->id . "_" . $empresa->cd_empresa;
        return Cache::remember($key, now()->addMinutes(60), function () use ($empresa, $query) {
            return DB::connection($this->setConnet('firebird_rede'/*$empresa->regiao*/))->select($query);
        });
    }
    public function InvoiceClient($empresa, $dt_ini, $dt_fim, $cnpj)
    {
        // return $this->setConnet($empresa->regiao);
        $query = "SELECT DISTINCT
            --EMPRESA
            N.CD_EMPRESA,
            PE.nm_pessoa EMITENTE,
            PE.NR_CNPJCPF NR_CNPJ_EMI,
            N.NR_LANCAMENTO,
            N.TP_NOTA,
            N.CD_SERIE, 
            N.DT_EMISSAO, 
            DATETOSTR(N.DT_EMISSAO, '%Y-%m-%d') DS_DTEMISSAO,
            --NOTA--
            N.nr_notafiscal NR_NOTASERVICO,
            NFSE.DS_ENDERECOIMP,
            N.vl_notafiscal VL_NF,
            NFSE.CD_AUTENTICACAO,
            NFSE.NR_LOTE NR_LOTERPS, NFSE.NR_RPS,
            V.CD_PESSOA||'-'||V.NM_PESSOA NM_VENDEDOR,
            C.CD_CONDPAGTO, C.DS_CONDPAGTO,
            F.CD_FORMAPAGTO, F.DS_FORMAPAGTO,
            --CLIENTE--
            P.CD_PESSOA,
            P.NM_PESSOA,
            P.NR_CNPJCPF            
            FROM NOTA N
            INNER JOIN PESSOA P ON (P.CD_PESSOA = N.CD_PESSOA)
            INNER JOIN EMPRESA E ON (E.CD_EMPRESA = N.CD_EMPRESA)
            INNER JOIN CONDPAGTO C ON (C.CD_CONDPAGTO = N.CD_CONDPAGTO)
            INNER JOIN NFSE ON (NFSE.CD_EMPRESA = N.CD_EMPRESA
                            AND NFSE.NR_LANCAMENTO = N.NR_LANCAMENTO
                            AND NFSE.CD_SERIE = N.CD_SERIE
                            AND NFSE.TP_NOTA = N.TP_NOTA)
            INNER JOIN PESSOA PE ON (PE.CD_PESSOA = E.CD_PESSOA)
            LEFT JOIN PESSOA V ON (V.CD_PESSOA = N.CD_VENDEDOR)
            LEFT JOIN FORMAPAGTO F ON (F.CD_FORMAPAGTO = N.CD_FORMAPAGTO)
            WHERE N.TP_NOTA = 'S'
                AND N.CD_SERIE = 'F'
                AND N.ST_NOTA = 'V'
                AND N.DT_EMISSAO between '$dt_ini' and '$dt_fim'
                AND P.ds_email IS NOT NULL
                AND N.cd_empresa = $empresa->cd_empresa                
                " . (($cnpj == 0) ? "and N.cd_pessoa= " . Auth::user()->cd_pessoa . "" : "AND p.nr_cnpjcpf in ('$cnpj')") . "                        
                        
            ORDER BY N.DT_EMISSAO";

        $key = "InvoiceAll_" . Auth::user()->id . "_" . $dt_ini . "_" . $dt_fim;
        return Cache::remember($key, now()->addMinutes(60), function () use ($query, $empresa) {
            return DB::connection('firebird_rede')->select($query);
        });
    }
    public function SaldoAdiantamento($nr_cnpjcpf)
    {
        $query = "
            SELECT
                    CONTAS.CD_EMPRESA,
                    CONTAS.CD_PESSOA || ' - ' || P.NM_PESSOA NM_PESSOA,
                    CONTAS.VL_SALDO,
                    CONTAS.DT_VENCIMENTO,
                    CONTAS.DS_OBSERVACAO
                FROM CONTAS
                INNER JOIN PESSOA P ON (P.CD_PESSOA = CONTAS.CD_PESSOA)
                WHERE CONTAS.CD_TIPOCONTA in (14,11,4)
                    AND CONTAS.ST_CONTAS IN ('P', 'T')
                    AND P.NR_CNPJCPF = '$nr_cnpjcpf'";

        $results = DB::connection('firebird_rede')->select($query);

        return Helper::ConvertFormatText($results);
    }
    
}
