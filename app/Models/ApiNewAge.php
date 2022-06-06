<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiNewAge extends Model
{
    use HasFactory;

    protected $connection;
    protected $table = "pneus_ouro_bgw";    

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }

    public function setConnet()
    {
        if (Auth::user() == null) {
            return $this->connection = 'firebird_campina';
        };
        return $this->connection = Auth::user()->conexao;
    }

    public function pneusBGW()
    {
        $query = "SELECT DISTINCT 39193 ORD_CODBTS, PN.idempresa CD_EMP, OPR.ID ORD_NUMERO, PN.dtemissao ORD_DATEMI, PN.hremissao ORD_HOREMI, NF.nr_notaservico NUM_NF,
        n.dt_emissao DATA_NF, PC.nr_cnpjcpf CLI_CPF,
        cast(PC.nm_pessoa as varchar(100) character SET UTF8)  CLI_NOME,
        ep.nr_cep CLI_CEP,
        cast(ep.ds_endereco as varchar(100) character SET UTF8)  CLI_LOGRAD,
        ep.nr_endereco CLI_NUMERO,
        cast(ep.ds_complemento as varchar(100) character SET UTF8) CLI_COMPL, 
        cast(ep.ds_bairro as varchar(100) character SET UTF8) CLI_BAIRRO, 
        mu.ds_municipio CLI_CIDADE, mu.sg_estado CLI_UF, 
        pc.ds_email CLI_EMAIL,
        ep.nr_fone CLI_TEL1, MDP.dsmedidapneu MEDIDA, SPA.iditem BANDA, p.nrserie MATRICULA, 
        coalesce(CASE P.NRFOGO WHEN '' THEN 0 END, 0) NUM_FOGO,
        P.NRDOT DOT, MAP.dsmarca MARCA,  MP.DSMODELO MODELO,
        OPR.qt_reformas CICLOVIDA, PN.id CHV_COLETA, II.VL_UNITARIO PRECO,
        (CASE OPR.qt_reformas WHEN 0 THEN 'R1' WHEN 1 THEN 'R1' WHEN 2 THEN 'R1' END) COD_I_CICLO,
        (CASE map.id
               when 11 then 'BG'
               when 6 then 'CT'
               when 1 then 'FS'
               when 8 then 'FS'
               when 2 then 'GY'
               when 3 then 'MI'
               when 4 then 'PI'
               when 531 then 'DT'
        END) COD_I_MARCA, replace(MDP.dsmedidapneu, '.', ',') COD_I_MED, 
        (CASE OPR.qt_reformas WHEN 0 THEN 'BGW' WHEN 1 THEN 'BGW' WHEN 2 THEN 'BGW' END) COD_I_TPGAR,
        BP.dscodigorqg COD_I_BANDA      
        
        FROM NOTA N 
        INNER JOIN ITEMNOTA II ON (II.CD_EMPRESA = N.CD_EMPRESA 
                               AND II.NR_LANCAMENTO = N.NR_LANCAMENTO 
                               AND II.TP_NOTA = N.TP_NOTA 
                               AND II.CD_SERIE = N.CD_SERIE) 
        LEFT JOIN RETORNA_CHAVEPEDIDO(N.CD_EMPRESA, N.NR_LANCAMENTO, N.TP_NOTA, N.CD_SERIE) CHP ON (CHP.O_CD_ITEM = II.CD_ITEM) 
        INNER JOIN PLUGORDRECAPPEDIDO PLUG ON (PLUG.CD_EMPRESA = CHP.O_CD_EMPRESA 
                                           AND PLUG.NR_PEDIDO = CHP.O_NR_PEDIDO 
                                           AND PLUG.TP_PEDIDO = CHP.O_TP_PEDIDO) 
        INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = PLUG.IDORDEMPRODUCAORECAP) 
        INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU) 
        INNER JOIN PNEU P ON (P.ID = IPP.IDPNEU) 
        INNER JOIN SERVICOPNEU SP ON (SP.ID = II.CD_ITEM) 
        INNER JOIN SERVPRODADICIONAL SPA ON (SPA.idservico = SP.id)
        INNER JOIN ITEM I ON (I.CD_ITEM = SP.ID) 
        LEFT JOIN MOVIMENTACAO M ON (M.CD_MOVIMENTACAO = II.CD_MOVIMENTACAO) 
        LEFT JOIN MODELOPNEU MP ON (MP.ID = P.IDMODELOPNEU) 
        LEFT JOIN MARCAPNEU MAP ON (MAP.ID = MP.IDMARCAPNEU) 
        LEFT JOIN MEDIDAPNEU MDP ON (MDP.ID = P.IDMEDIDAPNEU) 
        INNER JOIN BANDAPNEU BP ON (BP.id = SP.idbandapneu)
        inner JOIN DESENHOPNEU DP ON (DP.id = BP.iddesenhopneu)
        INNER JOIN PEDIDOPNEU PN ON (PN.ID = IPP.IDPEDIDOPNEU)
        INNER JOIN PESSOA PC ON (PC.CD_PESSOA = N.cd_pessoa)
        LEFT JOIN PLUGORDRECAPPEDIDO PLO ON (PLO.IDORDEMPRODUCAORECAP = OPR.ID) 
        LEFT JOIN PEDIDO PE ON (PE.CD_EMPRESA = PLO.CD_EMPRESA 
                            AND PE.NR_PEDIDO = PLO.NR_PEDIDO 
                            AND PE.TP_PEDIDO = PLO.TP_PEDIDO) 
        LEFT JOIN ITEMPEDIDO IPE ON (IPE.CD_EMPRESA = PE.CD_EMPRESA 
                                 AND IPE.NR_PEDIDO = PE.NR_PEDIDO 
                                 AND IPE.TP_PEDIDO = PE.TP_PEDIDO 
                                 AND IPE.CD_ITEM = CASE WHEN (OPR.STEXAMEFINAL = 'R' AND SP.IDITEMCARCACA IS NOT NULL) 
                                                   THEN SP.IDITEMCARCACA ELSE I.CD_ITEM END)
        LEFT JOIN EXAMEFINALPNEU EF ON (EF.IDORDEMPRODUCAORECAP = OPR.ID) 
        LEFT JOIN RETORNA_CHAVENOTARRC014(IPP.IDEMPRESAPEDSAI, IPP.IDPEDIDOSAI, IPP.TPPEDIDOSAI, 
                                          IPP.IDEMPRESAPEDENT, IPP.IDPEDIDOENT, IPP.TPPEDIDOENT, 
        NULL, NULL ) RCNS ON (1=1) 
        INNER JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = PC.CD_PESSOA 
                                     AND EP.CD_ENDERECO = PN.IDENDERECO) 
        INNER JOIN MUNICIPIO MU ON (MU.CD_MUNICIPIO = EP.CD_MUNICIPIO) 
        LEFT JOIN REGIAOCOMERCIAL RC ON (RC.CD_REGIAOCOMERCIAL = EP.CD_REGIAOCOMERCIAL)
        LEFT JOIN PESSOA PVV ON (PVV.CD_PESSOA = PN.IDVENDEDOR)
        LEFT JOIN NFSE NF ON (NF.CD_EMPRESA = N.CD_EMPRESA 
                          AND NF.NR_LANCAMENTO = N.NR_LANCAMENTO 
                          AND NF.CD_SERIE = N.CD_SERIE 
                          AND NF.TP_NOTA = N.TP_NOTA)
        WHERE N.CD_EMPRESA = 3
          AND N.ST_NOTA = 'V' 
        AND PN.STPEDIDO <> 'C' AND OPR.STORDEM <> 'C' 
         AND COALESCE(OPR.STORDEM, 'A') NOT IN ('C', 'T') 
          AND PN.IDEMPRESA = 3
          AND N.DT_EMISSAO >= '06/01/2022'
          AND N.DT_EMISSAO <= '06/01/2022'
         AND EF.STEXAMEFINAL = 'A' AND EF.ST_ETAPA = 'F' 
         AND MAP.id IN (11,1,6,531,2,3,4)
         and OPR.idgarantiapneu = 4
        --AND OPR.id in (212376)
        ORDER BY  PC.NM_PESSOA, OPR.ID||'/'||IPP.NRSEQUENCIA";

        return DB::connection('firebird_campina')->select($query);
    }

    public function store($pneus)
    {
        $this->connection = 'mysql';
        try {
            foreach ($pneus as $p) {
                ApiNewAge::updateOrInsert(
                    [
                        'ORD_NUMERO' => $p->ORD_NUMERO,
                    ],
                    [
                        'ORD_CODBTS'    => 39193,
                        'CD_EMP' => $p->CD_EMP,
                        'ORD_NUMERO'    => $p->ORD_NUMERO,
                        'ORD_DATEMI' => $p->ORD_DATEMI,
                        'ORD_HOREMI' => $p->ORD_HOREMI,
                        'NUM_NF' => $p->NUM_NF,
                        'DATA_NF' => $p->DATA_NF,
                        'CLI_CPF' => $p->CLI_CPF,
                        'CLI_NOME' => $p->CLI_NOME,
                        'CLI_CEP' => $p->CLI_CEP,
                        'CLI_LOGRAD' => $p->CLI_LOGRAD,
                        'CLI_NUMERO' => $p->CLI_NUMERO,
                        'CLI_COMPL' => $p->CLI_COMPL,
                        'CLI_BAIRRO' => $p->CLI_BAIRRO,
                        'CLI_CIDADE' => $p->CLI_CIDADE,
                        'CLI_UF' => $p->CLI_UF,
                        'CLI_EMAIL' => $p->CLI_EMAIL,
                        'CLI_TEL1' => $p->CLI_TEL1,
                        'MEDIDA' => $p->MEDIDA,
                        'BANDA' => $p->BANDA,
                        'MATRICULA' => $p->MATRICULA,
                        'FOGO' => $p->NUM_FOGO,
                        'DOT' => $p->DOT,
                        'MARCA' => $p->MARCA,
                        'MODELO' => $p->MODELO,
                        'CICLOVIDA' => $p->CICLOVIDA,                        
                        'CHV_COLETA' => $p->CHV_COLETA,
                        'PRECO' => $p->PRECO,
                        'COD_I_CICLO' => $p->COD_I_CICLO,
                        'COD_I_MARCA' => $p->COD_I_MARCA,                        
                        'COD_I_MED' => $p->COD_I_MED,
                        'COD_I_BANDA' => $p->COD_I_BANDA,                        

                        "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                        "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                    ]
                );
            }
        } catch (\Throwable $th) {
            return $th;
        }
        return 1;
    }
}
