<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogApiNewAge extends Model
{
    use HasFactory;
    protected $table = 'log_api_new_ages';
    
    public function storeUpdateLog($pneusLog)
    {
        $this->connection = 'mysql';
        foreach ($pneusLog as $p) {
            if (substr($p->OCORRENCIA, 129, 8) == 'superior' || substr($p->OCORRENCIA, 86, 8) == 'Invalido' || substr($p->OCORRENCIA, 191, 1) == '7') {
                LogApiNewAge::updateOrInsert(
                    [
                    'ordem' => $p->NUMERO_OS,
                ],
                    [
                    'cd_empresa' => $p->CODIGO_EMP,
                    'ordem' => $p->NUMERO_OS,
                    'pedido' => $p->CHAVE_COL,
                    'ocorrencia' => $p->OCORRENCIA,
                    'exportado' => 'C',
                    "created_at"    =>  \Carbon\Carbon::now(),
                    "updated_at"    => \Carbon\Carbon::now()
                ]
                );
            }else{
                LogApiNewAge::updateOrInsert(
                    [
                    'ordem' => $p->NUMERO_OS,
                ],
                    [
                    'cd_empresa' => $p->CODIGO_EMP,
                    'ordem' => $p->NUMERO_OS,
                    'pedido' => $p->CHAVE_COL,
                    'ocorrencia' => $p->OCORRENCIA,
                    'exportado' => $p->EXPORTADA,
                    "created_at"    =>  \Carbon\Carbon::now(),
                    "updated_at"    => \Carbon\Carbon::now()
                ]
                );
            }
        }
        return;
    }
    public function ListOrdemDivergente($ordem){
        return LogApiNewAge::
        join('pneus_ouro_bgw', 'pneus_ouro_bgw.ORD_NUMERO', 'log_api_new_ages.ordem')
        ->where('ordem', $ordem)->get();
    }

}
