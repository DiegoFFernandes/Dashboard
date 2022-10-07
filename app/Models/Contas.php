<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Contas extends Model
{
    use HasFactory;
    protected $table = 'contas';
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

    public function TicketsPendentsClient($cd_pessoa, $cd_empresa)
    {
        $query = "SELECT
                    E.nm_empresa empresa,
                    C.cd_pessoa||'-'||P.nm_pessoa pessoa,
                    C.nr_documento documento,
                    C.nr_documento||'/'||C.nr_parcela nr_documento,
                    c.dt_vencimento,
                    c.cd_formapagto,
                        case c.st_contas
                            when 'T' then 'Pendente Total'
                            when 'P' then 'Pendente Parcial'
                            else c.st_contas
                        end status,
                        c.vl_documento valor
                    FROM CONTAS C
                    INNER JOIN PESSOA P ON (P.cd_pessoa = C.cd_pessoa)
                    INNER JOIN EMPRESA E ON (E.cd_empresa = C.cd_empresa)
                    WHERE C.cd_empresa = $cd_empresa
                        AND C.cd_pessoa = $cd_pessoa
                        AND C.st_contas not in ('L', 'C')
                        and C.cd_formapagto NOT IN ('LD')
                        AND C.cd_tipoconta = 2";

        return DB::connection($this->setConnet())->select($query);
    }
}
