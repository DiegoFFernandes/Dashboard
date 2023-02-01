<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedidaPneu extends Model
{
    use HasFactory;
    protected $connection;

    public function __construct()
    {
        $this->connection = 'mysql';
    }

    // Essa consulta tras as informação do Mysql
    public function list(){
        $this->connection = 'mysql';
        
        return MedidaPneu::all();
     }

     public function setConnet($cd_empresa)
    {
        if ($cd_empresa == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';
    }
    // Essa consulta tras as informações do Firebird
    public function listMedidaPneu(){
        $query = "
                SELECT
                    MP.ID,
                CAST(MP.dsmedidapneu AS VARCHAR(60) CHARACTER SET UTF8) DS_MEDIDAPNEU
                FROM MEDIDAPNEU MP";
        return DB::connection($this->setConnet('SUL'))->select($query);
    }
}
