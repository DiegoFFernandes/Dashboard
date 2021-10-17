<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'EMPRESA';
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

        $campina = DB::connection('firebird_campina')->select('select CD_EMPRESA, NM_EMPRESA from EMPRESA');

        foreach ($campina as $c) {
            $c->CONEXAO = 'firebird_campina';
        }

        $paravanai = DB::connection('firebird_paranavai')->select('select CD_EMPRESA, NM_EMPRESA from EMPRESA');
        foreach ($paravanai as $p) {
            $p->CONEXAO = 'firebird_paranavai';
        }

        return array_merge((array) $campina, (array) $paravanai);
    }
}
