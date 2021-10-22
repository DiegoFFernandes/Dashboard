<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendaPessoa extends Model
{
    use HasFactory;

    protected $table = 'AGENDAPESSOA';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function AgendaOperador()
    {
       $banco = $this->setConnet();
        $query =
            "select x.cd_usuario, x.nm_usuario, sum(x.hoje) hoje, sum(x.ontem) ontem, sum(x.anteontem) anteontem
            from (
                select ap.cd_usuario, u.nm_usuario, count(ap.cd_usuario) hoje, 0 ontem, 0 anteontem
                from agendapessoa ap
                inner join usuario u on (u.cd_usuario = ap.cd_usuario)
                where CAST(ap.dt_registro AS DATE) = CURRENT_DATE-100
                group by ap.cd_usuario, u.nm_usuario
            
                union all
            
                select ap.cd_usuario, u.nm_usuario, 0 hoje, count(ap.cd_usuario) ontem, 0 anteontem
                from agendapessoa ap
                inner join usuario u on (u.cd_usuario = ap.cd_usuario)
                where CAST(ap.dt_registro AS DATE) = CURRENT_DATE-101
                group by ap.cd_usuario, u.nm_usuario
            
                union all
            
                select ap.cd_usuario, u.nm_usuario, 0 hoje, 0 ontem, count(ap.cd_usuario) anteontem
                from agendapessoa ap
                inner join usuario u on (u.cd_usuario = ap.cd_usuario)
                where CAST(ap.dt_registro AS DATE) = CURRENT_DATE-102
                group by ap.cd_usuario, u.nm_usuario
            ) x
            group by x.cd_usuario, x.nm_usuario";

        return DB::connection($banco)->select($query);
    }
}
