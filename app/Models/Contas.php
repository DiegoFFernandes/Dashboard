<?php

namespace App\Models;

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
    public function TicketsPendentsClient($empresa)
    {
        $query = "SELECT
                    E.nm_empresa empresa,
                    C.cd_pessoa||'-'||P.nm_pessoa pessoa,
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
                        AND C.cd_pessoa = " . Auth::user()->cd_pessoa . "
                        AND C.st_contas not in ('L', 'C')
                        and C.cd_formapagto NOT IN ('LD')
                        AND C.cd_tipoconta = 2";

        $key = "TicketsPendentsAll_" . Auth::user()->id . "_" . $empresa->cd_empresa;
        return Cache::remember($key, now()->addMinutes(60), function () use ($empresa, $query) {
            return DB::connection($this->setConnet($empresa->regiao))->select($query);
        });
    }
    public function InvoiceClient($empresa, $dt_ini, $dt_fim)
    {
        // return $this->setConnet($empresa->regiao);
        $query = "SELECT DISTINCT
            --EMPRESA
            N.CD_EMPRESA,
            PE.NR_CNPJCPF NR_CNPJ_EMI,
            N.NR_LANCAMENTO,
            N.TP_NOTA,
            N.CD_SERIE, 
            N.DT_EMISSAO, 
            DATETOSTR(N.DT_EMISSAO, '%Y-%m-%d') DS_DTEMISSAO,
            --NOTA--
            NFSE.NR_NOTASERVICO,
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
                AND N.cd_pessoa = " . Auth::user()->cd_pessoa . "
            ORDER BY N.DT_EMISSAO";

        return DB::connection($this->setConnet($empresa->regiao))->select($query);

        $key = "InvoiceAll_" . Auth::user()->id . "_" . $dt_ini . "_" . $dt_fim;
        return Cache::remember($key, now()->addMinutes(60), function () use ($query) {
        });
    }
}
