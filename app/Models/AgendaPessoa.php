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
    }

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function AgendaOperador3Meses()
    {     
         
        $config = Config::get('constants.options');        
   
        $banco = $this->setConnet();
        $query = 
          "select x.cd_usuario, x.nm_usuario, sum(x.mes1) mes1, sum(x.mes2) mes2, sum(x.mes3) mes3
            from (
            select ap.cd_usuario, u.nm_usuario, count(ap.cd_usuario) mes1, 0 mes2, 0 mes3
            from agendapessoa ap
            inner join usuario u on (u.cd_usuario = ap.cd_usuario)
            where ap.dt_registro between '09-01-2021 00:00:00' and '09-30-2021 23:59:59'
            group by ap.cd_usuario, u.nm_usuario
        
            union all
        
            select ap.cd_usuario, u.nm_usuario, 0 mes1, count(ap.cd_usuario) mes2, 0 mes3
            from agendapessoa ap
            inner join usuario u on (u.cd_usuario = ap.cd_usuario)
            where ap.dt_registro between '08-01-2021 00:00:00' and '08-31-2021 23:59:59'
            group by ap.cd_usuario, u.nm_usuario
        
            union all
        
            select ap.cd_usuario, u.nm_usuario, 0 mes1, 0 mes2, count(ap.cd_usuario) mes3
            from agendapessoa ap
            inner join usuario u on (u.cd_usuario = ap.cd_usuario)
            where ap.dt_registro between '07-01-2021 00:00:00' and '07-31-2021 23:59:59'
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
              select dt.dt, ap.cd_usuario, u.nm_usuario, count(ap.dt_registro) as qtd
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
        $banco = $this->setConnet();
        $query = "select ap.cd_usuario, u.nm_usuario, count(*)
        from agendapessoa ap
        inner join usuario u on (u.cd_usuario = ap.cd_usuario)
        where ap.dt_registro between '$this->p_dia' and current_date
        group by ap.cd_usuario, u.nm_usuario";
        return DB::connection($banco)->select($query);
    }
}
