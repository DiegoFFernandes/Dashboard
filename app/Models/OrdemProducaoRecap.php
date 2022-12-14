<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdemProducaoRecap extends Model
{
    use HasFactory;

    protected $connection = 'firebird_campina';
    protected $table = 'ORDEMPRODUCAORECAP';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    public function setConnet()
    {
        return $this->connection = Auth::user()->conexao;
    }

    public function UnlockOrdem($ordem)
    {
        return DB::transaction(function () use ($ordem){

             DB::connection($this->setConnet())->select("EXECUTE PROCEDURE ACESSO_IVO");

            $query = "update ORDEMPRODUCAORECAP opr set opr.STALTERANDO = 'N' where opr.ID = $ordem";
            
            return DB::connection($this->setConnet())->statement($query);
        });
    }
}
