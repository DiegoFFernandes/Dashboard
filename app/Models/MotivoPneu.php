<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MotivoPneu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'ds_motivo',
        'ds_causa',
        'ds_recomendacoes',
        'tp_motivo'
    ];

    public function __construct()
    {
        $this->connection = 'mysql';
    }

    // Essa consulta tras as informação do Mysql
    public function list()
    {

        $this->connection = 'mysql';
        return MotivoPneu::all();
    }

    public function setConnet($cd_empresa)
    {
        if ($cd_empresa == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';
    }

    public function ImportaMotivoPneuJunsoft()
    {
        $query = "
                SELECT
                    MP.id,
                    cast(MP.dsmotivo as varchar(5000) character set utf8) ds_motivo,
                    cast(MP.dscausa as varchar(5000) character set utf8) ds_causa,
                    cast(MP.dsrecomendacoes as varchar(5000) character set utf8) ds_recomendacoes,                    
                    MP.tpmotivo,
                    MP.dtregistro
                    FROM MOTIVOPNEU MP
                    WHERE MP.dsmotivo NOT LIKE '%DESATIVADO%'
            ";
        $itens = DB::connection($this->setConnet('SUL'))->select($query);

        $status = $this->InsertItem($itens);
        if ($status == 1) {
            return 1;
        } else {
            return $status;
        }
    }
    public function InsertItem($itens)
    {
        $this->connection = 'mysql';
        foreach ($itens as $i) {
            try {
                MotivoPneu::updateOrInsert(
                    ['id' => $i->ID],
                    [
                        'id' => $i->ID,
                        'ds_motivo' => $i->DS_MOTIVO,
                        'ds_causa' => $i->DS_CAUSA,
                        'ds_recomendacoes' => $i->DS_RECOMENDACOES,
                        'tp_motivo' => $i->TPMOTIVO,
                        'created_at' => $i->DTREGISTRO,
                        'updated_at' => $i->DTREGISTRO,
                    ]
                );
            } catch (\Illuminate\Database\QueryException $ex) {
                return ($ex->errorInfo[2]);
                return "Houve algum erro ao importar os itens";
            }
        }
        return 1;
    }
}
