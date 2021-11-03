<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

class AgendaPessoa extends Model
{
    use HasFactory;

    protected $table = 'AGENDAPESSOA';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
        $this->p_dia = date("m/01/Y");
        $this->dti30dias = Config::get('constants.options.dti30dias');
        $this->dtf30dias = Config::get('constants.options.dtf30dias');
        $this->dti60dias = Config::get('constants.options.dti60dias');
        $this->dtf60dias = Config::get('constants.options.dtf60dias');
        $this->dti90dias = Config::get('constants.options.dti90dias');
        $this->dtf90dias = Config::get('constants.options.dtf90dias');
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function AgendaOperador3Meses()
    {
        $banco = $this->setConnet();
        $query =
            "select x.cd_usuario,
          DECODE(POSITION(' ',x.nm_usuario),0,x.nm_usuario, SUBSTRING(x.nm_usuario FROM 1 FOR POSITION(' ',x.nm_usuario))) nm_usuario, 
          sum(x.mes1) mes1, sum(x.mes2) mes2, sum(x.mes3) mes3
             from (
             select ap.cd_usuario, u.nm_usuario, count(ap.cd_usuario) mes1, 0 mes2, 0 mes3
             from agendapessoa ap
             inner join usuario u on (u.cd_usuario = ap.cd_usuario)
             where ap.dt_registro between '$this->dti30dias 00:00:00' and '$this->dtf30dias 23:59:59'
             group by ap.cd_usuario, u.nm_usuario
         
             union all
         
             select ap.cd_usuario, u.nm_usuario, 0 mes1, count(ap.cd_usuario) mes2, 0 mes3
             from agendapessoa ap
             inner join usuario u on (u.cd_usuario = ap.cd_usuario)
             where ap.dt_registro between '$this->dti60dias 00:00:00' and '$this->dtf60dias 23:59:59'
             group by ap.cd_usuario, u.nm_usuario
         
             union all
         
             select ap.cd_usuario, u.nm_usuario, 0 mes1, 0 mes2, count(ap.cd_usuario) mes3
             from agendapessoa ap
             inner join usuario u on (u.cd_usuario = ap.cd_usuario)
             where ap.dt_registro between '$this->dti90dias 00:00:00' and '$this->dtf90dias 23:59:59'
             group by ap.cd_usuario, u.nm_usuario
             ) x
           group by x.cd_usuario, x.nm_usuario";

        return DB::connection($banco)->select($query);
    }
    public function AgendaOperadorMes($operadores)
    {
        $banco = $this->setConnet();
        foreach ($operadores as $o) {
            $query = "with recursive dt as (
                select cast('$this->p_dia' as date) as dt
                from rdb\$database
                union all
                select dt.dt + 1
                from dt
                where dt < cast(current_date as date))              
              select dt.dt, ap.cd_usuario, DECODE(POSITION(' ',u.nm_usuario),0,u.nm_usuario, SUBSTRING(u.nm_usuario FROM 1 FOR POSITION(' ', u.nm_usuario))) nm_usuario, count(ap.dt_registro) as qtd
              from dt
              left join (select * from agendapessoa ap where ap.cd_usuario = '$o->CD_USUARIO'
                  and ap.dt_registro between '$this->p_dia' and current_timestamp) ap on ((cast(ap.dt_registro as date)) = dt.dt)
              left join usuario u on (u.cd_usuario = ap.cd_usuario)
              --inner join usuario u on (u.cd_usuario = ap.cd_usuario)
              group by dt.dt, ap.cd_usuario, u.nm_usuario";

            $operador[] = DB::connection($banco)->select($query);
        }

        return $operador;
    }
    public function Operadores()
    {        
        $query = "select ap.cd_usuario, 
        DECODE(POSITION(' ',u.nm_usuario),0,u.nm_usuario, SUBSTRING(u.nm_usuario FROM 1 FOR POSITION(' ', u.nm_usuario))) nm_usuario, 
        count(*)
        from agendapessoa ap
        inner join usuario u on (u.cd_usuario = ap.cd_usuario)
        where ap.dt_registro between '$this->dti30dias' and current_date
        group by ap.cd_usuario, u.nm_usuario";

        return DB::connection($this->setConnet())->select($query);
    }
    public function Detalhe($cdusuario, $data)
    {
        $query = "select ap.nr_sequencia,
        cast((ap.cd_pessoa||' - '|| p.nm_pessoa) as varchar(200) character set utf8) nm_pessoa, 
         ap.cd_usuario, 
        cast(ap.ds_agenda as blob sub_type text character set utf8) as ds_agenda, 
        ap.dt_registro,
            case ap.st_contato
            when 'D' then 'Entramos em contato'
            else 'Cliente ligou'
            end st_contato
        from agendapessoa ap
        inner join pessoa p on (p.cd_pessoa = ap.cd_pessoa)
        where cast(ap.dt_registro as date) = '$data' and ap.cd_usuario = '$cdusuario'";

        return DB::connection($this->setConnet())->select($query);
    }
    public function CadastroNovos()
    {
        $query = "select x.cd_usuariocad, 
        DECODE(POSITION(' ',x.nm_usuario),0,x.nm_usuario, SUBSTRING(x.nm_usuario FROM 1 FOR POSITION(' ',x.nm_usuario))) nm_usuario,
        sum(x.mes1) mes1, sum(x.mes2) mes2, sum(x.mes3) mes3
        from
        (select p.cd_usuariocad, u.nm_usuario, count(p.cd_usuariocad) mes1, 0 mes2, 0 mes3
        from pessoa p
        inner join usuario u on (u.cd_usuario = p.cd_usuariocad)
        where p.dt_cadastro between '$this->dti30dias' and '$this->dtf30dias'
            and p.cd_usuariocad not in ('ti02', 'ti04')
            and u.cd_emprpadrao = '3'
        group by p.cd_usuariocad, u.nm_usuario
        
        union all
        
        select p.cd_usuariocad, u.nm_usuario, 0 mes1, count(p.cd_usuariocad) mes2, 0 mes3
        from pessoa p
        inner join usuario u on (u.cd_usuario = p.cd_usuariocad)
        where p.dt_cadastro between '$this->dti60dias' and '$this->dtf60dias'
            and p.cd_usuariocad not in ('ti02', 'ti04')
            and u.cd_emprpadrao = '3'
        group by p.cd_usuariocad, u.nm_usuario
        
        union all
        
        select p.cd_usuariocad, u.nm_usuario, 0 mes1, 0 mes2, count(p.cd_usuariocad) mes3
        from pessoa p
        inner join usuario u on (u.cd_usuario = p.cd_usuariocad)
        where p.dt_cadastro between '$this->dti90dias' and '$this->dtf90dias'
            and p.cd_usuariocad not in ('ti02', 'ti04')
            and u.cd_emprpadrao = '3'
        group by p.cd_usuariocad, u.nm_usuario
        )x
        group by x.cd_usuariocad, x.nm_usuario";

        return DB::connection($this->setConnet())->select($query);
    }
    public function ClientesNovosMes($operadores){
        foreach ($operadores as $o) {
            $query = "with recursive dt as (
            select cast('$this->p_dia' as date) as dt
            from rdb\$database
            union all
            select dt.dt + 1
            from dt
            where dt < cast(current_date as date))
            select dt.dt, ap.cd_usuariocad, DECODE(POSITION(' ',u.nm_usuario),0,u.nm_usuario, SUBSTRING(u.nm_usuario FROM 1 FOR POSITION(' ', u.nm_usuario))) nm_usuario, count(ap.dt_cadastro) as qtd
            from dt
            left join (select * from pessoa ap where ap.cd_usuariocad = '$o->CD_USUARIO'
            and ap.dt_cadastro between '$this->p_dia' and current_timestamp) ap on ((cast(ap.dt_cadastro as date)) = dt.dt)
            left join usuario u on (u.cd_usuario = ap.cd_usuariocad)
            --inner join usuario u on (u.cd_usuario = ap.cd_usuario)
            group by dt.dt, ap.cd_usuariocad, u.nm_usuario";

            $operador[] = DB::connection($this->setConnet())->select($query);
        }
        return $operador;
        
    }
}
