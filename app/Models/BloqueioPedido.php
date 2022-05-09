<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloqueioPedido extends Model
{
    use HasFactory;
    protected $connection;

    const STATUS_CLIENTE = [
        'NAO'  => '#f59898',
        'SIM'   => '#90EE90',
    ];
    const STATUS_SCPC = [
        'NAO'  => '#90EE90',
        'SIM'   => '#f59898',
    ];
    const STATUS_PEDIDO = [
        'BLOQUEADO'  => '#f59898',
        'LIBERADO '   => '#90EE90',
        'VERIFICAR' => '#FFFF99'
    ];

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
    public function BloqueioPedido($cd_regiao)
    {
        $query = "SELECT (CASE PP.STPEDIDO WHEN 'B' THEN 'BLOQUEADO' WHEN 'N' THEN 'LIBERADO' ELSE 'VERIFICAR' END) STPEDIDO,
            PP.idempresa, PP.DTEMISSAO DATA, PP.ID AS PEDIDO, PP.IDPEDIDOMOVEL AS MOBILE,
            cast(PP.IDPESSOA||' - '||PE.NM_PESSOA as varchar(200) character set utf8) CLIENTE,
            --PP.TP_BLOQUEIO AS MOTIVO,
            (CASE PP.TP_BLOQUEIO WHEN 'F' then 'FINANCEIRO' WHEN 'C' then 'COMERCIAL' ELSE 'LIBERADO' END) MOTIVO,
            EP.cd_regiaocomercial,
            (CASE PE.ST_ATIVA WHEN 'S' THEN 'SIM' WHEN 'N' THEN 'NAO' END) ST_ATIVA,
            (CASE PE.ST_SCPC WHEN 'S' THEN 'SIM' WHEN 'N' THEN 'NAO' END) ST_SCPC,
            cast( PP.IDVENDEDOR||' - '||PV.NM_PESSOA as varchar(200) character set utf8) VENDEDOR,
            AC.cd_areacomercial
            --cast(PP.DSBLOQUEIO as varchar(5000) character set utf8) DSBLOQUEIO
        FROM PEDIDOPNEU PP
        INNER JOIN PESSOA PE ON (PE.CD_PESSOA = PP.IDPESSOA)
        LEFT JOIN ENDERECOPESSOA EP ON (EP.cd_pessoa = PE.cd_pessoa)
        INNER JOIN VENDEDOR VE ON (VE.CD_VENDEDOR = PP.IDVENDEDOR)
        INNER JOIN PESSOA PV ON (PV.CD_PESSOA = VE.CD_VENDEDOR)
        LEFT  JOIN REGIAOCOMERCIAL RC ON (RC.CD_VENDEDOR = VE.CD_VENDEDOR AND EP.cd_regiaocomercial = RC.cd_regiaocomercial)
        LEFT JOIN AREACOMERCIAL AC ON (AC.cd_areacomercial = RC.cd_areacomercial)
        WHERE PP.STPEDIDO NOT IN ('C', 'A', 'T')
        AND PP.IDEMPRESA IN (1,2,3,4,101,102,103,104,304)
        AND (PP.STPEDIDO = 'B' OR PE.ST_ATIVA = 'N' OR PE.ST_SCPC = 'S')
        " . (($cd_regiao != "") ? "AND EP.cd_regiaocomercial IN ($cd_regiao)" : "") . "
        GROUP BY PP.STPEDIDO, PP.idempresa, PP.DTEMISSAO, PP.ID, PP.IDPEDIDOMOVEL, PP.IDPESSOA, PE.NM_PESSOA,
        PP.TP_BLOQUEIO, PE.ST_ATIVA, PE.ST_SCPC, (PP.IDVENDEDOR||' - '||PV.NM_PESSOA), PP.DSBLOQUEIO, EP.cd_regiaocomercial, AC.cd_areacomercial
        order by PP.idempresa, PP.DTEMISSAO";
        return DB::connection($this->setConnet())->select($query);
    }
}
