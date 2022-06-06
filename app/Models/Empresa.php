<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'empresas_grupo';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function empresa()
    {
        //$this->setConnet();
        //return Empresa::select('CD_EMPRESA', 'NM_EMPRESA')->get();

        $campina = $this->CarregaEmpresa('firebird_campina');
        $paravanai = $this->CarregaEmpresa('firebird_paranavai');
        
        return array_merge((array) $campina, (array) $paravanai);
    }

    public function CarregaEmpresa($firebird){
        $banco = DB::connection($firebird)->select("select CD_EMPRESA, (CD_EMPRESA||' - '||NM_EMPRESA) AS NM_EMPRESA from EMPRESA WHERE ST_ATIVO = 'S'");

        foreach ($banco as $b) {
            $b->CONEXAO = $firebird;
        }
        return $banco;
    }
}
