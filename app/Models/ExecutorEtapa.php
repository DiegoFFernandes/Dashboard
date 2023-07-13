<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExecutorEtapa extends Model
{
    use HasFactory;
    protected $table = 'executoretapas';

    public function __construct()
    {
        $this->connection = 'mysql';
    }

    public function setConnet($local)
    {
        if ($local == 'SUL') {
            return $this->connection = 'firebird_campina';
        }
        return $this->connection = 'firebird_paranavai';
    }
    public function searchExecutorEtapaJunsoft($local)
    {
        $query = 'select e.id matricula, cast(e.nmexecutor as varchar(60) character set utf8) nmexecutor, e.idempresa, e.stativo
                  from executoretapa e
                  where e.stativo = 1';
        return DB::connection($this->setConnet($local))->select($query);
    }
    public function StoreExecutorEtapa($executores, $localizacao)
    {
        $this->connection = 'mysql';
        try {
            foreach ($executores as $e) {
                ExecutorEtapa::updateOrInsert(
                    [
                        'matricula' => $e->MATRICULA,
                        'localizacao' => $localizacao,
                    ],
                    [
                        'matricula' => $e->MATRICULA,
                        'nmexecutor' => strtoupper($e->NMEXECUTOR),
                        'cd_empresa' => $e->IDEMPRESA,
                        'localizacao' => $localizacao,
                        'stativo' => $e->STATIVO,
                        "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                        "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                    ]
                );
            }
        } catch (\Throwable $th) {
            return $th;
        }
        return 1;
    }
    public function ListExecutor($local){
        $this->connection = 'mysql';
        return ExecutorEtapa::
        // where('localizacao', $local)->
        orderBy('nmexecutor')->get();
    }
    
}
