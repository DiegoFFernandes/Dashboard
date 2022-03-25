<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Inadimplencia extends Model
{
    use HasFactory;
    protected $connection;

    public function __construct()
    {
        $this->connection = 'Sempre setar o banco firebird com SetConnet';
    }
    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function dividaALL()
    {
        $query = "SELECT C.CD_EMPRESA,C.CD_PESSOA,C.CD_TIPOCONTA,C.NR_PARCELA,C.CD_FORMAPAGTO,C.TP_CONTAS,C.CD_SERIE,C.TP_DOCUMENTO,
        C.NR_DOCUMENTO,C.DT_LANCAMENTO,C.DT_VENCIMENTO,C.DT_LIQUIDACAO,C.VL_DOCUMENTO,C.VL_SALDO,C.ST_CONTAS,C.CD_VENDEDOR,
        C.PC_COMISSAO,CD.VL_CREDITOACUM,TC.TP_TIPOCONTA,TC.CD_TIPOCONTA,
        cast(TC.DS_TIPOCONTA as varchar(60) character set utf8) DS_TIPOCONTA,
        C.CD_COBRADOR,
        cast(P.NM_PESSOA as varchar(80) character set utf8) NM_PESSOA,
        EP.CD_REGIAOCOMERCIAL,P.NR_CNPJCPF
        FROM CONTAS C
        INNER JOIN TIPOCONTA TC ON (TC.CD_TIPOCONTA = C.CD_TIPOCONTA)
        INNER JOIN PESSOA P ON (P.CD_PESSOA = C.CD_PESSOA)
        LEFT JOIN ENDERECOPESSOA EP ON (EP.CD_PESSOA = P.CD_PESSOA
                                         AND EP.CD_ENDERECO = 1)
        LEFT JOIN CREDITO CD ON (CD.CD_PESSOA = P.CD_PESSOA
                                AND CD.CD_EMPRESA = C.CD_EMPRESA)
        WHERE C.ST_CONTAS IN ('T','P')
        AND C.ST_INCOBRAVEL = 'N'
        AND C.DT_VENCIMENTO between current_date-60 and current_date-6
        --and C.CD_COBRADOR IS NULL
        AND (TC.TP_TIPOCONTA IN ('CR','HR','CT') OR (TC.CD_TIPOCONTA in (10,17,28,29)))";

        return DB::connection($this->setConnet())->select($query);
        // $key = "dividaAll_";
        // return Cache::remember($key, now()->addMinutes(15), function () use ($query) {
        // });
    }
}
