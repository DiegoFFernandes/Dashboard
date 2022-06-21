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

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }
    public function searchExecutorEtapaJunsoft(){
        $query = 'select e.id, cast(e.nmexecutor as varchar(60) character set utf8) nmexecutor, e.idempresa, e.stativo
                  from executoretapa e
                  where e.stativo = 1';
        return DB::connection($this->setConnet())->select($query);
    }

    public function StoreExecutorEtapa($executores){
        $this->connection = 'mysql';
        try {
            foreach ($executores as $e) {
                ExecutorEtapa::updateOrInsert(
                    [
                    'id' => $e->ID,
                ],
                    [
                    'id' => $e->ID,
                    'nmexecutor' => $e->NMEXECUTOR,
                    'cd_empresa' => $e->IDEMPRESA,
                    'stativo' => $e->STATIVO,
                    "created_at"    =>  \Carbon\Carbon::now(), # new \Datetime()
                    "updated_at"    => \Carbon\Carbon::now(),  # new \Datetime()
                ]
                );
            }
        }catch(\Throwable $th) {
            return $th;
        }
        return 1;
    }

}
