<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Financeiro extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet()
    {
        if (Auth::user() == null) {
            return $this->connection = 'firebird_rede';
        };
        return $this->connection = 'firebird_rede';
    }
    public static function Conciliacao($cd_empresa, $dt_ini, $dt_fim)
    {
        $query = "SELECT
                MC.CD_EMPRESA, MC.NR_LANCCAIXA, MC.NR_PROCESSO, MC.DT_LANCAMENTO, MC.CD_HISTORICO, 
                cast(CASE MC.CD_HISTORICO WHEN 206 THEN 'TARIFAS BANCARIAS' ELSE MC.DS_COMPLEMENTO END as varchar(1000) character set utf8) DS_COMPLEMENTO,
                MC.NR_DOCUMENTO, MC.NR_PARCELA, MC.VL_DOCUMENTO, MC.TP_DOCUMENTO, --MC.TP_MOVTOCAIXA, MC.CD_PESSOACAIXA,
                CASE WHEN CTB.CD_CONTADEBITO > 100000 THEN CPD.NR_CNPJCPF ELSE COALESCE(PD.CD_QUESTOR,CTB.CD_CONTADEBITO) END CONTA_DEBITO,
                CASE WHEN CTB.CD_CONTACREDITO > 100000 THEN CPC.NR_CNPJCPF ELSE COALESCE(PC.CD_QUESTOR,CTB.CD_CONTACREDITO) END CONTA_CREDITO,
            CASE
                WHEN MC.TP_DOCUMENTO = 'NT' AND MC.TP_MOVTOCAIXA = 'R' THEN 'PGTO'    --NOTA
                WHEN MC.TP_DOCUMENTO = 'NT' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'
                WHEN MC.TP_DOCUMENTO = 'AD' AND MC.TP_MOVTOCAIXA = 'R' THEN 'PGTO'    --ADTO
                WHEN MC.TP_DOCUMENTO = 'AD' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'
                WHEN MC.TP_DOCUMENTO = 'AV' AND MC.TP_MOVTOCAIXA = 'R' THEN 'RECTO'   --AVULSO
                WHEN MC.TP_DOCUMENTO = 'AV' AND MC.TP_MOVTOCAIXA = 'P' THEN 'PGTO'
                WHEN MC.TP_DOCUMENTO = 'CH' AND MC.TP_MOVTOCAIXA = 'R' THEN 'PGTO'    --CHEQUE
                WHEN MC.TP_DOCUMENTO = 'CH' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'
                WHEN MC.TP_DOCUMENTO = 'CT' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'   --CARTÃO
                WHEN MC.TP_DOCUMENTO = 'DE' AND MC.TP_MOVTOCAIXA = 'R' THEN 'PGTO'
                WHEN MC.TP_DOCUMENTO = 'DE' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'   --DESCONTO
                WHEN MC.TP_DOCUMENTO = 'DP' AND MC.TP_MOVTOCAIXA = 'R' THEN 'PGTO'
                WHEN MC.TP_DOCUMENTO = 'DP' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'   --DUPL AVULSA
                WHEN MC.TP_DOCUMENTO = 'JU' AND MC.TP_MOVTOCAIXA = 'R' THEN 'PGTO'
                WHEN MC.TP_DOCUMENTO = 'JU' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'   --JUROS
                WHEN MC.TP_DOCUMENTO = 'RP' AND MC.TP_MOVTOCAIXA = 'P' THEN 'RECTO'   --REPAR
            END TIPO,
                CPD.NR_CNPJCPF NR_CNPJCPF_DEB, CPC.NR_CNPJCPF NR_CNPJCPF_CRE,
                CTB.CD_CONTADEBITO CD_PLANO_DEB, CTB.CD_CONTACREDITO CD_PLANO_CRE,
                PD.CD_QUESTOR CD_QUESTOR_DEB, PC.CD_QUESTOR CD_QUESTOR_CRE
            FROM MOVTOCAIXA MC
                INNER JOIN MOVTOCAIXACTB CTB ON (CTB.CD_EMPRESA = MC.CD_EMPRESA
                                            AND CTB.NR_LANCCAIXA = MC.NR_LANCCAIXA)
                LEFT JOIN CONTASPESSOA CPD ON (CPD.CD_EMPRESA = CTB.CD_EMPRESA
                                        AND (CPD.CD_CONTACLI = CTB.CD_CONTADEBITO or CPD.CD_CONTAFOR = CTB.CD_CONTADEBITO))
                LEFT JOIN CONTASPESSOA CPC ON (CPC.CD_EMPRESA = CTB.CD_EMPRESA
                                        AND (CPC.CD_CONTACLI = CTB.CD_CONTACREDITO or CPC.CD_CONTAFOR = CTB.CD_CONTACREDITO))
                LEFT JOIN PLANOCONTAS PD ON (PD.CD_CONTA = CTB.CD_CONTADEBITO)
                LEFT JOIN PLANOCONTAS PC ON (PC.CD_CONTA = CTB.CD_CONTACREDITO)
            WHERE MC.ST_ESTORNO = 'N'
                AND MC.CD_EMPRESA = $cd_empresa
                AND MC.DT_LANCAMENTO BETWEEN '$dt_ini' AND '$dt_fim'
            ORDER BY MC.NR_LANCCAIXA, MC.NR_PROCESSO, MC.DT_LANCAMENTO
            ";

        if ($cd_empresa == 1) {
            $conciliacao = DB::connection('firebird_campina')->select($query);
        } elseif ($cd_empresa == 309) {  
            $conciliacao = DB::connection('firebird_paranavai')->select($query);
        } else {
            $conciliacao = DB::connection('firebird_rede')->select($query);
        }


        $conc = collect($conciliacao)->map(function ($c) {
            $c->DT_LANCAMENTO = \Carbon\Carbon::parse($c->DT_LANCAMENTO)->format('d/m/Y');
            return $c;
        });

        return $conc;
    }
}
