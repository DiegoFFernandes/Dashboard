<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ParmContabil extends Model
{
    use HasFactory;
    
    protected $table = "parmcontabil";
    
    public function store($data)
    {
        $contabil = new ParmContabil();

        $contabil::UpdateOrInsert(
            ['id' => 1],
            [
                'dt_fechamento' => $data['date'],
                'status' => $data['status'],
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]
        );

        return true;
    }
    public function VerificaContabilAberta()
    {
        $query = "  
                SELECT
                    COUNT(*) qtd
                FROM PARMCONTABIL P
                WHERE P.DT_BLOQCONTABIL IS NULL
                AND P.CD_EMPRESA NOT IN (1,201,202,203,204,205,206,207,208,209,210)
                ";

        return DB::connection('firebird_rede')->select($query);
    }

    public function dt_fechamento($date)
    {
        $aberta = $this->VerificaContabilAberta();

        if ($aberta[0]->QTD > 0) {            
            return DB::transaction(function () use ($date) {

                DB::connection('firebird_rede')->select("EXECUTE PROCEDURE GERA_SESSAO");
                $query = " 
                      UPDATE PARMCONTABIL P
                    SET P.DT_BLOQFINANCEIRO = '$date',
                        P.DT_BLOQCONTABIL = '$date',
                        P.DT_BLOQCAIXA = '$date',
                        P.DT_BLOQESTOQUE = '$date'
                    WHERE P.CD_EMPRESA NOT IN (1,201,202,203,204,205,206,207,208,209,210)
            ";
                return DB::connection('firebird_rede')->update($query);
            });
        }
    }
    public function listEmpresaParmContabil()
    {
        $query = "  
                SELECT
                    P.CD_EMPRESA,
                    P.DT_BLOQESTOQUE,
                    P.DT_BLOQFINANCEIRO,
                    P.DT_BLOQCONTABIL,
                    P.DT_BLOQCAIXA
                FROM PARMCONTABIL P
                WHERE P.CD_EMPRESA NOT IN (1,201,202,203,204,205,206,207,208,209,210)
                ORDER BY P.CD_EMPRESA";

        return DB::connection('firebird_rede')->select($query);
    }


}
