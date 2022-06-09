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
        return;
    }

}
