<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Producao extends Model
{
    use HasFactory;

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function TrocaServico($data)
    {
        $banco = $this->setConnet();
        $query = "SELECT
                    R.O_IDORDEMPRODUCAORECAP ORDEM, R.CD_PESSOA||' - '|| R.NM_PESSOA PESSOA, R.DSETAPA, R.IDEXECUTOR||' - '||R.NMEXECUTOR OPERADOR,
                    R.DTALTERACAO, R.IDANTIGA, R.DSANTIGA, R.IDNOVA, R.DSNOVA
                    FROM RETORNA_TROCADESENHO($data,CURRENT_DATE)R
                    WHERE R.IDANTIGA <> R.IDNOVA";
        return DB::connection($banco)->select($query);;
    }
}
