<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'empresas_grupo';
    protected $connection;

    public function __construct()
    {
        $this->connection = 'mysql';
    }

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    // public function empresa()
    // {
    //     //$this->setConnet();
    //     //return Empresa::select('CD_EMPRESA', 'NM_EMPRESA')->get();

    //     $campina = $this->CarregaEmpresa('firebird_campina');
    //     $paravanai = $this->CarregaEmpresa('firebird_paranavai');

    //     return array_merge((array) $campina, (array) $paravanai);
    // }
    //inclui uma coluna de conexão firebird_campina ou firebird_paranavai
    public function CarregaEmpresa($firebird)
    {
        // $banco = DB::connection($firebird)->select("select CD_EMPRESA, (CD_EMPRESA||' - '||NM_EMPRESA) AS NM_EMPRESA from EMPRESA WHERE ST_ATIVO = 'S'");

        // foreach ($banco as $b) {
        //     $b->CONEXAO = $firebird;
        // }
        // return $banco;
        return Empresa::select([
            'CD_EMPRESA',
            DB::raw("CONCAT(cd_empresa,' - ',ds_local) NM_EMPRESA"), 'CONEXAO'
        ])->where('CONEXAO', $firebird)->get();
    }
    public function EmpresaFiscal($local)
    {
        return Empresa::where('regiao', $local)
            ->where('cd_loja', 1)
            ->get();
    }
    public function EmpresaFiscalAll()
    {
        return Empresa::select('cd_empresa_new', DB::raw('CONCAT(ds_local,"-",regiao) AS ds_local'))
                ->where('cd_loja', 1)
                ->whereIn('regiao', ['norte', 'sul'])
                ->whereIn('cd_empresa_new', [101,102,103,104,108,109,105,106,107,108])
                ->get();
        $key = "Empresas_2" . Auth::user()->id;
        return Cache::remember($key, now()->addMinutes(120), function () {
            
        });
    }
    public function EmpresaAll()
    {
        return Empresa::select([
            'CD_EMPRESA',
            DB::raw("CONCAT(cd_empresa,' - ',ds_local) NM_EMPRESA"), 'CONEXAO'
        ])->where('cd_loja', 1)->orderBy('cd_empresa')->get();
    }
}
