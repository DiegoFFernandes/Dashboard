<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoletoImpresso extends Model
{
    use HasFactory;
    private $protected = 'boletoimpresso';

    public function __construct()
    {
        $this->connection = 'firebird_campina';
    }
    public function setConnet($cd_empresa)
    {
        if ($cd_empresa == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';
    }

    public function Boleto($nr_doc, $emp)
    {
        $query = "SELECT DISTINCT BENF.nm_pessoa NMBENF,
        ENDBENF.ds_endereco||', '||ENDBENF.nr_endereco ENDBENF,
        ENDBENF.nr_cep CEPBENF,
        MUNBENF.ds_municipio MUNBENF,
        MUNBENF.sg_estado ESTBENF,        
        P.CD_PESSOA,
        cast(P.NM_PESSOA as varchar(60) character set utf8) NM_PESSOA,
        C.ST_CONTAS,
        DATETOSTR(C.DT_VENCIMENTO, '%Y-%m-%d') DT_VENC,
        C.NR_DOCUMENTO||'/'||C.NR_PARCELA NR_DOC,
        cast('R$ '||C.VL_SALDO as varchar(60) character set utf8) VL_TOTAL,
        C.NR_PARCELA||'/'|| MAX(
                                (SELECT MAX(CM.NR_PARCELA)
                                FROM CONTAS CM
                                WHERE CM.CD_EMPRESA = C.CD_EMPRESA
                                    AND CM.NR_LANCAMENTO = C.NR_LANCAMENTO
                                    AND CM.CD_PESSOA = C.CD_PESSOA
                                    AND CM.CD_TIPOCONTA = C.CD_TIPOCONTA)) DS_PARCELA,
        LIST(DISTINCT 'Nº '||N.NR_NOTAFISCAL||'-'||N.CD_SERIE, ', ') DS_NOTAS,
        LIST(DISTINCT DATETOSTR(N.DT_EMISSAO, '%Y-%m-%d'), ', ') DS_EMISSAONOTAS,
        BI.nr_sequencia,
        bi.cd_empresa,
        bi.nr_lancamento,
        bi.nr_parcela,
        bi.cd_banco,
        B.cd_codigocedente,
        B.dg_codigocedente,
        B.cd_agencia,
        B.cd_contacor,
        B.DG_CONTACOR,
        B.cd_convenio,
        cast(bi.ds_codigobanco as varchar(60) character set utf8) ds_codigobanco,
        cast(bi.ds_banco as varchar(60) character set utf8) ds_banco,
        cast(bi.ds_localpagamento as varchar(300) character set utf8) ds_localpagamento,
        bi.dt_vencimento,
        cast(bi.nm_cedente as varchar(60) character set utf8) nm_cedente,
        cast(bi.ds_enderecocedente as varchar(300) character set utf8) ds_enderecocedente,
        bi.nr_cnpjcpfcedente,
        bi.dt_documento,
        bi.nr_documento,
        bi.ds_especie,
        bi.tp_aceite,
        bi.dt_processamento,
        C.nr_boleto nr_nossonumero,
        bi.nr_nossonumero bi_nossonumero,
        bi.ds_usobanco,
        bi.nr_carteira,
        bi.ds_moeda,
        bi.qt_moeda,
        bi.vl_moeda,
        bi.vl_documento,
        cast(bi.ds_instrucao as varchar(600) character set utf8) ds_instrucao,
        bi.vl_descontoabatimento,
        bi.vl_deducao,
        bi.vl_multajuro,
        bi.vl_acrescimo,
        bi.vl_cobrado,
        bi.nm_sacado,
        bi.nr_cnpjcpfsacado,
        cast(EP.ds_endereco as varchar(300) character set utf8) ds_endereco,
        cast(EP.ds_bairro as varchar(300) character set utf8) ds_bairro,
        EP.nr_cep,
        cast(M.ds_municipio as varchar(300) character set utf8) ds_municipio,
        M.sg_estado,
        cast(bi.ds_enderecosacado as varchar(300) character set utf8) ds_enderecosacado,       
        bi.ds_parcelas,
        bi.nr_documentoparc,
        bi.nr_docserieparc,
        bi.cd_sacado        
        FROM CONTAS C
        INNER JOIN BOLETOIMPRESSO BI ON (BI.CD_EMPRESA = C.CD_EMPRESA
                                AND BI.NR_LANCAMENTO = C.NR_LANCAMENTO
                                AND BI.NR_PARCELA = C.NR_PARCELA
                                AND BI.NR_SEQUENCIA =
                                (SELECT FIRST 1 BI2.NR_SEQUENCIA
                                    FROM BOLETOIMPRESSO BI2
                                    WHERE BI2.CD_EMPRESA = C.CD_EMPRESA
                                    AND BI2.NR_LANCAMENTO = C.NR_LANCAMENTO
                                    AND BI2.NR_PARCELA = C.NR_PARCELA
                                    ORDER BY BI2.NR_SEQUENCIA DESC))
        INNER JOIN BOLETO B ON (B.cd_banco = BI.cd_banco
                                    AND B.cd_empresa = BI.cd_empresa
                                    AND B.cd_formapagto = C.cd_formapagto)
        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
        INNER JOIN ENDERECOPESSOA EP ON (EP.cd_pessoa = P.cd_pessoa)
        INNER JOIN MUNICIPIO M ON (M.cd_municipio = EP.cd_municipio)
        INNER JOIN PESSOA BENF ON (BENF.cd_pessoa = B.cd_titular AND BENF.nr_cnpjcpf = BI.nr_cnpjcpfcedente)
        INNER join enderecopessoa ENDBENF ON (ENDBENF.cd_pessoa = BENF.cd_pessoa)
        INNER JOIN MUNICIPIO MUNBENF ON (MUNBENF.cd_municipio = ENDBENF.cd_municipio)
        --INNER JOIN PESSOAPERFIL PF ON (P.CD_PESSOA = PF.CD_PESSOA)
        LEFT JOIN DESTINOREPARCELAMENTO D ON (D.CD_EMPRCONTAS = C.CD_EMPRESA
                                    AND D.NR_LANCAMENTO = C.NR_LANCAMENTO
                                    AND D.CD_PESSOA = C.CD_PESSOA
                                    AND D.CD_TIPOCONTA = C.CD_TIPOCONTA
                                    AND D.NR_PARCELA = C.NR_PARCELA)
        LEFT JOIN ORIGEMREPARCELAMENTO O ON (O.CD_EMPRESA = D.CD_EMPRESA
                                    AND O.NR_REPARCELAMENTO = D.NR_REPARCELAMENTO)
        LEFT JOIN CONTAS CO ON (CO.CD_EMPRESA = O.CD_EMPRESA
                        AND CO.NR_LANCAMENTO = O.NR_LANCAMENTO
                        AND CO.CD_PESSOA = O.CD_PESSOA
                        AND CO.CD_TIPOCONTA = O.CD_TIPOCONTA
                        AND CO.NR_PARCELA = O.NR_PARCELA)
        INNER JOIN NOTA N ON (N.CD_EMPRESA = COALESCE(CO.CD_EMPRESA, C.CD_EMPRESA)
                    AND N.CD_PESSOA = COALESCE(CO.CD_PESSOA, C.CD_PESSOA)
                    AND N.NR_LANCAMENTO = COALESCE(CO.NR_LANCTONOTA, C.NR_LANCTONOTA)
                    AND N.TP_NOTA = COALESCE(CO.TP_CONTAS, C.TP_CONTAS))
        WHERE C.ST_CONTAS NOT IN ('C',
                        'L',
                        'A')
        AND C.cd_empresa in ($emp)
        AND BI.nr_documento IN ('$nr_doc')
        GROUP BY BENF.nm_pessoa,
        ENDBENF,
        ENDBENF.nr_cep,
        ENDBENF.nr_cep,
        MUNBENF.ds_municipio,
        MUNBENF.sg_estado,        
        P.CD_PESSOA,
        P.NM_PESSOA,
        C.ST_CONTAS,
        DT_VENC,
        NR_DOC,
        C.NR_PARCELA,
        VL_TOTAL,
        BI.nr_sequencia,
        bi.cd_empresa,
        bi.nr_lancamento,
        bi.nr_parcela,
        bi.cd_banco,
        B.cd_codigocedente,
        B.dg_codigocedente,
        B.cd_agencia,
        B.cd_contacor,
        B.DG_CONTACOR,
        B.cd_convenio,
        bi.ds_codigobanco,
        bi.ds_banco,
        bi.ds_localpagamento,
        bi.dt_vencimento,
        bi.nm_cedente,
        bi.ds_enderecocedente,
        bi.nr_cnpjcpfcedente,
        bi.dt_documento,
        bi.nr_documento,
        bi.ds_especie,
        bi.tp_aceite,
        bi.dt_processamento,
        C.nr_boleto,
        bi.ds_usobanco,
        bi.nr_carteira,
        bi.ds_moeda,
        bi.qt_moeda,
        bi.vl_moeda,
        bi.vl_documento,
        bi.ds_instrucao,
        bi.vl_descontoabatimento,
        bi.vl_deducao,
        bi.vl_multajuro,
        bi.vl_acrescimo,
        bi.vl_cobrado,
        bi.nm_sacado,
        bi.nr_cnpjcpfsacado,
        EP.ds_endereco,
        EP.ds_bairro,
        EP.nr_cep,
        M.ds_municipio,
        M.sg_estado,
        bi.ds_enderecosacado,
        bi.ds_cepcidadesacado,       
        bi.bi_codigobarra,        
        bi.ds_parcelas,
        bi.nr_documentoparc,
        bi.nr_docserieparc,
        bi.cd_sacado,        
        bi.nm_avalista,
        bi.nr_cnpjcpfavalista,
        bi.nr_nossonumero";

        $key = "Boleto_". $nr_doc .'_'. Auth::user()->id;
        // return DB::connection($this->setConnet($emp))->select($query);       
        return Cache::remember($key, now()->addMinutes(15), function () use ($query, $emp) {
            return DB::connection($this->setConnet($emp))->select($query);
        });
        
        
    }
}
