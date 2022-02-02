<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GQCBrigestone extends Model
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

    public function pneusFaturadosMarca($inicio_data, $fim_data)
    {
        $query = "SELECT map.dsmarca,
                        (CASE map.id
                            when 11 then 'BS'
                            when 1 then 'FS'
                            when 531 then 'DY'
                            when 3 then 'MI'
                            when 2 then 'GY'
                            when 4 then 'PI'
                            when 6 then 'CO'
                            when 7 then 'DU'
                            else 'OUTROS'
                            END) SIGLA,
                        MDP.DSMEDIDAPNEU, count(*) qtd
                    FROM PEDIDOPNEU PN 
                    INNER JOIN PESSOA PC ON (PC.CD_PESSOA = PN.IDPESSOA)
                    INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.IDPEDIDOPNEU = PN.ID) 
                    INNER JOIN PNEU P ON (P.ID = IPP.IDPNEU) 
                    INNER JOIN MODELOPNEU MP ON (MP.ID = P.IDMODELOPNEU) 
                    INNER JOIN MARCAPNEU MAP ON (MAP.ID = MP.IDMARCAPNEU) 
                    LEFT JOIN MEDIDAPNEU MDP ON (MDP.ID = P.IDMEDIDAPNEU) 
                    INNER JOIN SERVICOPNEU SP ON (SP.ID = IPP.IDSERVICOPNEU) 
                    INNER JOIN ITEM I ON (I.CD_ITEM = SP.ID) 
                    LEFT JOIN ORDEMPRODUCAORECAP OPR ON (OPR.IDITEMPEDIDOPNEU = IPP.ID)
                    LEFT JOIN PLUGORDRECAPPEDIDO PLO ON (PLO.IDORDEMPRODUCAORECAP = OPR.ID) 
                    LEFT JOIN PESSOA PVV ON (PVV.CD_PESSOA = PN.IDVENDEDOR) 
                    LEFT JOIN PEDIDO PE ON (PE.CD_EMPRESA = PLO.CD_EMPRESA 
                                        AND PE.NR_PEDIDO = PLO.NR_PEDIDO 
                                        AND PE.TP_PEDIDO = PLO.TP_PEDIDO)
                    WHERE PN.IDEMPRESA = 3
                        AND PN.STPEDIDO <> 'C'
                        AND OPR.STORDEM <> 'C' 
                        AND PN.IDEMPRESA = 3
                        AND OPR.DTFECHAMENTO between '$inicio_data' and '$fim_data'
                        And I.CD_SUBGRUPO IN (321)
                    GROUP BY map.dsmarca, sigla, MDP.DSMEDIDAPNEU, map.id
                    ORDER BY sigla";
        return DB::connection($this->setConnet())->select($query);
    }
}
