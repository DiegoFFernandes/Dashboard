<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebHook extends Model
{
    use HasFactory;

    public function __construct()
    {
        $this->connection = 'mysql';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function emailsDifferents($arryEmail)
    {
        $query = "SELECT 
                    X.cd_pessoa, X.nm_pessoa, X.ds_email, MODULO
                FROM (
                    SELECT P.cd_pessoa, cast(P.nm_pessoa as varchar(100) character set UTF8) nm_pessoa, P.ds_email, 'TFA001' MODULO
                    FROM PESSOA P
                    where p.cd_tipopessoa not in (5,8,7,6,2)

                    union all
                
                    SELECT cp.cd_pessoa, coalesce(cp.nmcontato,'SEM CONTATO') nm_pessoa, cp.dsemail ds_email, 'TFA029' MODULO
                    FROM contatopessoa cp ) X
                where X.ds_email IN ('$arryEmail')";

        return DB::connection($this->setConnet())->select($query);
    }
}
