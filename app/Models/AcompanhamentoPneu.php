<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AcompanhamentoPneu extends Model
{

    use HasFactory;
    protected $connection;

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
    public function IdOrdemProducao($consulta)
    {
        $query = "select IDITEMPEDIDOPNEU from ordemproducaorecap where id = $consulta";
        return DB::connection($this->setConnet())->select($query);
    }
    public function BuscaSetores($nr_ordem)
    {
        $query = "SELECT CAST(O_DS_ETAPA AS VARCHAR(20) character SET UTF8) as O_DS_ETAPA,
        O_HR_ENTRADA, O_HR_SAIDA, O_NM_USUARIO, 
        CAST(O_DS_COMPLEMENTOETAPA AS VARCHAR(100) character SET UTF8) as O_DS_COMPLEMENTOETAPA, 
        O_DT_ENTRADA, O_DT_SAIDA, (case O_ST_RETRABALHO WHEN 'N' THEN 'NAO' ELSE 'SIM' END) O_ST_RETRABALHO
        FROM RETORNA_ACOMPANHAMENTOPNEU ($nr_ordem) R
        ORDER BY CAST(R.O_DT_ENTRADA||' '||R.O_HR_ENTRADA AS DOM_TIMESTAMP)";

        return DB::connection($this->setConnet())->select($query);
    }
    public function showDataPneus($nr_ordem)
    {
        $query = "select IPP.idpedidopneu PEDIDO, OPR.id ORDEM, PP.idpessoa ||' - '|| P.NM_PESSOA CLIENTE, SP.dsservico SERVICO,
        MP.dsmodelo||' - '||M.dsmarca as MODELO, MD.dsmedidapneu MEDIDA,
        PN.nrserie SERIE, PN.nrfogo FOGO, PN.nrdot DOT, LOPR.idmontagemlotepcp LOTE, OPR.stalterando alterando
        FROM ITEMPEDIDOPNEU IPP
        INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.idpedidopneu)
        INNER JOIN PNEU PN on (PN.ID = IPP.idpneu)
        INNER JOIN PESSOA P ON (P.cd_pessoa = PP.idpessoa)
        INNER JOIN servicopneu SP ON (SP.id = IPP.idservicopneu)
        INNER JOIN ORDEMPRODUCAORECAP OPR ON ( OPR.iditempedidopneu = IPP.id)
        INNER JOIN MODELOPNEU MP ON ( MP.id = PN.idmodelopneu)
        INNER JOIN MARCAPNEU M ON (M.id = MP.idmarcapneu)
        INNER JOIN MEDIDAPNEU MD ON (MD.id = PN.idmedidapneu)
        LEFT JOIN LOTEPCPORDEMPRODUCAORECAP LOPR ON (LOPR.idordemproducao = OPR.ID)
        LEFT JOIN MONTAGEMLOTEPCPRECAP MLP ON (MLP.id = LOPR.idmontagemlotepcp)
        LEFT JOIN controlelotepcprecap CLR ON (CLR.id = MLP.idcontrolelotepcprecap)
        where OPR.id = $nr_ordem
        group by IPP.idpedidopneu, OPR.id, PP.idpessoa, P.NM_PESSOA, SP.dsservico, MODELO, MD.dsmedidapneu,
        PN.nrserie, PN.nrfogo, PN.nrdot, LOPR.idmontagemlotepcp, OPR.stalterando";

        return DB::connection($this->setConnet())->select($query);
    }
    public function ListPedidoPneu($cd_regiao)
    {
        $query = "SELECT PP.IDEMPRESA CD_EMPRESA, PP.ID, PPM.idpedidomovel, 
        cast(PP.IDPESSOA||' - '||PC.NM_PESSOA as varchar(80) character set utf8) PESSOA, 
        EP.cd_regiaocomercial,
        PP.DTEMISSAO, PP.DTENTREGA DTENTREGAPED,
        (CASE PP.stpedido
            WHEN 'A' THEN 'ATENDIDO'
            WHEN 'C' THEN 'CANCELADO'
            WHEN 'T' THEN 'EM PRODUCAO'
            WHEN 'N' THEN 'AGUARDANDO'
            WHEN 'B' THEN 'BLOQUEADO'
            WHEN 'P' THEN 'PRODUCAO PARCIAL'
            ELSE PP.stpedido
        END) STPEDIDO
        FROM PEDIDOPNEU PP
        INNER JOIN PESSOA PC ON (PC.CD_PESSOA = PP.IDPESSOA)
        LEFT JOIN ENDERECOPESSOA EP ON (EP.cd_pessoa = PC.cd_pessoa)
        LEFT JOIN PEDIDOPNEUMOVEL PPM ON(PPM.ID = PP.ID)
        WHERE PP.IDEMPRESA in (3,4,1,101,102,103,104,304)
        AND PP.dtemissao between current_date-30 and current_date        
        " . (($cd_regiao != "") ? "AND EP.cd_regiaocomercial IN ($cd_regiao)" : "") . " 
        ORDER BY PP.IDEMPRESA";

        //return DB::connection($this->setConnet())->select($query);
        $key = "PedidoAll_" . Auth::user()->id;
        return Cache::remember($key, now()->addMinutes(15), function () use ($query) {
            return DB::connection($this->setConnet())->select($query);
        });
    }
    public function ItemPedidoPneu($pedido)
    {
        $query = "SELECT PP.IDEMPRESA CD_EMPRESA, PP.ID PEDIDO, PPM.idpedidomovel, OPR.id NRORDEM, IPP.id, IPP.nrsequencia, (PP.IDPESSOA||' - '||PC.NM_PESSOA) PESSOA, SP.dsservico,
        MAC.DSMARCA, MOP.DSMODELO, P.NRDOT, P.NRSERIE, DP.DSDESENHO, IPP.VLUNITARIO,
        IPP.ID IDITEMPEDPNEU, PP.IDVENDEDOR, PP.DTEMISSAO
        FROM PEDIDOPNEU PP
        INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.IDPEDIDOPNEU = PP.ID)
        INNER JOIN PESSOA PC ON (PC.CD_PESSOA = PP.IDPESSOA)
        LEFT JOIN ORDEMPRODUCAORECAP OPR ON (OPR.IDITEMPEDIDOPNEU = IPP.ID AND OPR.STORDEM <> 'C')
        INNER JOIN PNEU P ON (P.ID = IPP.IDPNEU)
        INNER JOIN MODELOPNEU MOP ON (MOP.ID = P.IDMODELOPNEU)
        INNER JOIN MARCAPNEU MAC ON (MAC.ID = MOP.IDMARCAPNEU)
        INNER JOIN MEDIDAPNEU MD ON(MD.ID = P.IDMEDIDAPNEU)
        INNER JOIN DESENHOPNEU DP ON (DP.ID = IPP.IDDESENHOPNEU)
        INNER JOIN SERVICOPNEU SP ON(SP.ID = IPP.IDSERVICOPNEU)
        LEFT JOIN PEDIDOPNEUMOVEL PPM ON(PPM.ID = PP.ID)
        LEFT JOIN ITEMPEDIDOPNEUBORRACHEIRO IPPB ON(IPPB.IDITEMPEDIDOPNEU = IPP.ID)
        LEFT JOIN PESSOA PV ON (PV.CD_PESSOA = IPPB.IDBORRACHEIRO)
        WHERE PP.ID = $pedido /**informar o numero do pedido aqui */
        GROUP BY PP.IDEMPRESA, PP.ID, PPM.idpedidomovel, OPR.id, IPP.id, IPP.nrsequencia, PESSOA, SP.dsservico,
        MAC.DSMARCA, MOP.DSMODELO, P.NRDOT, P.NRSERIE, DP.DSDESENHO, IPP.VLUNITARIO,
        IPP.ID, PP.IDVENDEDOR, PP.DTEMISSAO 
        ORDER BY PP.IDEMPRESA, IPP.ID";
        return DB::connection($this->setConnet())->select($query);
    }
}
