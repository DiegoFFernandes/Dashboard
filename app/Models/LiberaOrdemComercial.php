<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LiberaOrdemComercial extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet($localizacao)
    {
        if ($localizacao == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';

        // return $this->connection = Auth::user()->conexao;
    }
    public function listOrdensBloqueadas($cd_regiao, $pedidos)
    {
        $query = "select
        pp.idempresa emp,
        pp.dtemissao,
        pp.id pedido,
        pp.stpedido,
        pp.tp_bloqueio,
        pp.idpedidomovel,
        p.nm_pessoa pessoa,
        cast(PP.dsbloqueio as varchar(8100) character set utf8) dsbloqueio,
        pp.dsliberacao,
        cast(pv.nm_pessoa as varchar(1000) character set UTF8) vendedor,
        ep.cd_regiaocomercial
        from itempedidopneuborracheiro ipb
        inner join itempedidopneu ipp on (ipp.id = ipb.iditempedidopneu)
        inner join pedidopneu pp on (pp.id = ipp.idpedidopneu)
        inner join pneu pn on (ipp.idpneu = pn.id)
        inner join item i on (ipp.idservicopneu = i.cd_item)
        left join itemtabpreco itp on (itp.cd_tabpreco = ipp.idtabpreco
        and itp.cd_item = ipp.idservicopneu)
        inner join pessoa p on (p.cd_pessoa = pp.idpessoa)
        inner join pessoa pv on (pv.cd_pessoa = ipb.idborracheiro)
        inner join enderecopessoa ep on (ep.cd_pessoa = p.cd_pessoa and ep.cd_endereco = 1)
        where ipb.dt_registro >= CURRENT_DATE-60
        and pp.stpedido in ('B')
        --and ipb.pc_comissao <= 3
        and ipb.cd_tipo = 1
        --and pp.dtemissao >= CURRENT_DATE-30
        and pp.idpessoa not in ('9')
        --and i.cd_subgrupo not in (306,307,324,325)
        and pp.tp_bloqueio <> 'F'
        and itp.cd_tabpreco = 1
        " . (($cd_regiao != "") ? "and ep.cd_regiaocomercial in ($cd_regiao)" : "") . "  
        " . (($pedidos != "") ? "and pp.id in ($pedidos)" : "and pp.id = 0") . "       
        --and ipb.iditempedidopneu = 466381        
        group by
        pp.stpedido,
        pp.tp_bloqueio,
        pp.idempresa,
        pp.dtemissao,
        pessoa,
        PP.dsbloqueio,
        pp.dsliberacao,
        vendedor,
        ep.cd_regiaocomercial,
        pp.id,
        pp.idpedidomovel";
        return DB::connection('firebird_rede')->select($query);
    }
    public function listPneusOrdensBloqueadas($id, $localizacao)
    {
        $query = "select
                    pp.stpedido,
                    pp.tp_bloqueio,
                    pp.id pedido,
                    pp.idempresa emp,
                    pp.dtemissao,                   
                    cast(p.nm_pessoa as varchar(1000) character set UTF8) pessoa,
                    --PP.dsbloqueio,
                    i.cd_subgrupo,
                    ipb.cd_tipo,
                    cast(pv.nm_pessoa as varchar(1000) character set UTF8) vendedor,
                    ipb.pc_comissao,
                    ipb.vl_comissao,
                    --ipp.id itempedido,
                    ipp.nrseqcriacao seq,
                    pp.idpedidomovel,
                    i.ds_item ,
                    ipp.vlunitario vl_venda,
                    cast(itp.vl_preco as numeric(15,2)) vl_preco,
                    cast(100 * (1 - (ipp.vlunitario/itp.vl_preco)) as numeric(15,2)) pc_desconto,
                    itp.cd_tabpreco
                from itempedidopneuborracheiro ipb
                inner join itempedidopneu ipp on (ipp.id = ipb.iditempedidopneu)
                inner join pedidopneu pp on (pp.id = ipp.idpedidopneu)
                inner join pneu pn on (ipp.idpneu = pn.id)
                inner join item i on (ipp.idservicopneu = i.cd_item)
                left join itemtabpreco itp on (itp.cd_tabpreco = ipp.idtabpreco
                                                and itp.cd_item = ipp.idservicopneu)
                inner join pessoa p on (p.cd_pessoa = pp.idpessoa)
                inner join pessoa pv on (pv.cd_pessoa = ipb.idborracheiro)
                where ipb.dt_registro >= CURRENT_DATE-60
                    and pp.stpedido in ('B')
                    and ipb.pc_comissao <= 3
                    and ipb.cd_tipo = 1
                    and pp.dtemissao >= CURRENT_DATE-30
                    and pp.idpessoa not in ('9')
                    and i.cd_subgrupo not in (306,307,324,325)
                    and pp.tp_bloqueio <> 'F'
                    and itp.cd_tabpreco = 1
                    " . (($id <> 0) ? " and pp.id = '". $id ."'" : "") . "                    
                --and ipb.iditempedidopneu = 466381
                --and pp.id = 155723";

        return DB::connection('firebird_rede')->select($query);

        
    }
}
