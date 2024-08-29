<?php

namespace App\Models;

use Helper;
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

    public function ContasBloqueadas($status)
    {
        $query = "
                SELECT
                    CONTAS.CD_EMPRESA,
                    CONTAS.NR_LANCAMENTO,
                    CONTAS.CD_PESSOA,
                    CONTAS.CD_PESSOA || ' - ' || P.NM_PESSOA NM_PESSOA,
                    CONTAS.CD_TIPOCONTA || ' ' || TC.DS_TIPOCONTA DS_TIPOCONTA,
                    CONTAS.NR_DOCUMENTO||' / '||RMAX.O_NR_MAIORPARCELA NR_DOCUMENTO,
                    RMAX.O_NR_MAIORPARCELA PARCELAS,
                    SUM(CONTAS.VL_DOCUMENTO) VL_DOCUMENTO,
                    CONTAS.DS_OBSERVACAO,
                    CONTAS.DS_LIBERACAO,
                    CONTAS.DT_LANCAMENTO,
                    COALESCE(CONTAS.ST_VISTO, 'N') ST_VISTO
                FROM CONTAS
                INNER JOIN RETORNA_MAIORPARCELACONTAS(CONTAS.CD_EMPRESA, CONTAS.NR_LANCAMENTO, CONTAS.CD_PESSOA, CONTAS.CD_TIPOCONTA) RMAX ON (1 = 1)
                INNER JOIN PESSOA P ON (P.CD_PESSOA = CONTAS.CD_PESSOA)
                INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = CONTAS.CD_TIPOCONTA)
                WHERE CONTAS.ST_BLOQUEADA = 'S'
                    AND CONTAS.ST_CONTAS NOT IN ('C', 'L')
                    --AND CONTAS.CD_PESSOA = 1107927
                    --AND CONTAS.NR_LANCAMENTO = 1334264
                    AND COALESCE(CONTAS.ST_VISTO, 'N') = '$status'
                GROUP BY
                    CONTAS.CD_EMPRESA,
                    CONTAS.NR_LANCAMENTO,
                    CONTAS.CD_PESSOA,
                    CONTAS.CD_PESSOA,
                    P.NM_PESSOA,
                    CONTAS.CD_TIPOCONTA,
                    TC.DS_TIPOCONTA,
                    CONTAS.NR_DOCUMENTO,
                    CONTAS.DS_LIBERACAO,
                    RMAX.O_NR_MAIORPARCELA,
                    CONTAS.DS_OBSERVACAO,
                    CONTAS.DT_LANCAMENTO,
                    CONTAS.ST_VISTO  ";

        $results = DB::connection('firebird_rede')->select($query);
        return $results =  Helper::ConvertFormatText($results);
    }
    public function listHistoricoContasBloqueadas($cd_empresa, $nr_lancamento)
    {
        $query = "
                SELECT
                    CH.CD_EMPRESA,
                    CH.NR_LANCAMENTO,
                    CH.CD_PESSOA,
                    CH.CD_HISTORICO || ' - ' || HISTORICO.DS_HISTORICO DS_HISTORICO,
                    CH.VL_DOCUMENTO,
                    CH.NR_PARCELA,
                    CONTAS.DT_LANCAMENTO,
                    CONTAS.DT_VENCIMENTO
                FROM CONTASHISTORICO CH
                INNER JOIN CONTAS ON (CH.CD_EMPRESA = CONTAS.CD_EMPRESA
                    AND CH.NR_LANCAMENTO = CONTAS.NR_LANCAMENTO
                    AND CH.CD_PESSOA = CONTAS.CD_PESSOA
                    AND CH.CD_TIPOCONTA = CONTAS.CD_TIPOCONTA
                    AND CH.NR_PARCELA = CONTAS.NR_PARCELA)
                INNER JOIN HISTORICO ON (HISTORICO.CD_HISTORICO = CH.CD_HISTORICO)
                WHERE CH.NR_LANCAMENTO = $nr_lancamento
                    AND CH.CD_EMPRESA = $cd_empresa
                    ";

        $results = DB::connection('firebird_rede')->select($query);
        return $results =  Helper::ConvertFormatText($results);
    }
    public function updateStatusContasBloqueadas($cd_empresa, $nr_lancamento, $status, $ds_liberacao)
    {
        return DB::transaction(function () use ($cd_empresa, $nr_lancamento, $status, $ds_liberacao) {

            DB::connection('firebird_rede')->select("EXECUTE PROCEDURE GERA_SESSAO");

            $query = "
                UPDATE CONTAS C
                SET C.ST_BLOQUEADA = '$status',
                    C.ST_VISTO = 'S',
                    C.DS_LIBERACAO = '$ds_liberacao'
                WHERE C.NR_LANCAMENTO = $nr_lancamento
                    AND C.CD_EMPRESA = $cd_empresa
                ";

            return DB::connection('firebird_rede')->select($query);
        });
    }
    public function listCentroCustoContasBloqueadas($cd_empresa, $nr_lancamento)
    {
        $query = "
                    SELECT
                        C.DT_LANCAMENTO,
                        C.CD_EMPRESA,
                        COALESCE(H.CD_CENTROCUSTO, N.CD_CENTROCUSTO) CD_CENTROCUSTO,
                        COALESCE(H.VL_CENTROCUSTO, N.VL_CENTROCUSTO) VL_CENTROCUSTO,
                        CC.DS_CENTROCUSTO,
                        C.NR_DOCUMENTO,
                        C.CD_PESSOA,
                        C.NR_PARCELA,
                        C.NR_LANCAMENTO
                    FROM CONTAS C
                    LEFT JOIN CONTASHISTORICOCC H ON (C.CD_EMPRESA = H.CD_EMPRESA
                        AND C.NR_LANCAMENTO = H.NR_LANCAMENTO
                        AND C.NR_PARCELA = H.NR_PARCELA
                        AND C.CD_PESSOA = H.CD_PESSOA
                        AND C.CD_TIPOCONTA = H.CD_TIPOCONTA)

                    LEFT JOIN NOTA NT ON (NT.NR_LANCAMENTO = C.NR_LANCTONOTA
                        AND NT.CD_EMPRESA = C.CD_EMPRESA
                        AND NT.CD_SERIE = C.CD_SERIE
                        AND NT.TP_NOTA = C.TP_CONTAS)
                    LEFT JOIN ITEMNOTACC N ON (NT.CD_EMPRESA = N.CD_EMPRESA
                        AND NT.NR_LANCAMENTO = N.NR_LANCAMENTO
                        AND NT.TP_NOTA = N.TP_NOTA
                        AND NT.CD_SERIE = N.CD_SERIE)

                    LEFT JOIN CENTROCUSTO CC ON (CC.CD_EMPRESA = COALESCE(H.CD_EMPRESA, N.CD_EMPRESA)
                        AND CC.CD_CENTROCUSTO = COALESCE(H.CD_CENTROCUSTO, N.CD_CENTROCUSTO))

                    WHERE C.NR_LANCAMENTO = $nr_lancamento
                        AND C.CD_EMPRESA = $cd_empresa
                    GROUP BY C.DT_LANCAMENTO,
                        C.CD_EMPRESA,
                        H.CD_CENTROCUSTO,
                        H.VL_CENTROCUSTO,
                        N.CD_CENTROCUSTO,
                        N.VL_CENTROCUSTO,
                        C.NR_DOCUMENTO,
                        C.CD_PESSOA,
                        C.NR_PARCELA,
                        C.NR_LANCAMENTO,
                        CC.DS_CENTROCUSTO  
                    ";

        $results = DB::connection('firebird_rede')->select($query);
        return $results =  Helper::ConvertFormatText($results);
    }
}
